<?php

namespace Tests\Feature;

use App\Filament\Resources\RoleAssignments\RoleAssignmentResource;
use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\Role;
use App\Models\RoleAssignment;
use Tests\TestCase;

class MultiCompanyAccessTest extends TestCase
{
    public function test_company_admin_only_sees_and_edits_accounts_of_the_active_company(): void
    {
        [$a, $periodA] = $this->company('AAA');
        [$b] = $this->company('BBB');
        $adminA = $this->user('admin@aaa.test', 'company-admin', $a);
        $staffA = $this->user('staf@aaa.test', 'kpi-owner', $a);
        $staffB = $this->user('staf@bbb.test', 'kpi-owner', $b);
        $shared = $this->user('konsultan@ab.test', 'viewer', $a);
        $shared->companies()->attach($b->id);
        $shared->roles()->attach(Role::where('slug', 'company-admin')->value('id'), ['company_id' => $b->id]);

        $this->actingAs($adminA);
        $this->useContext($periodA);

        $visible = UserResource::getEloquentQuery()->pluck('email')->sort()->values()->all();
        $this->assertSame(['admin@aaa.test', 'konsultan@ab.test', 'staf@aaa.test'], $visible);
        $this->assertTrue(UserResource::canEdit($staffA));
        $this->assertFalse(UserResource::canEdit($shared->fresh()), 'akun yang juga admin di BBB hanya boleh diubah Super Admin');
        $this->assertFalse(UserResource::canDelete($adminA), 'tidak bisa menghapus akun sendiri');

        $this->assertSame([$a->id], RoleAssignmentResource::getEloquentQuery()->pluck('company_id')->unique()->values()->all());
        $this->assertFalse(RoleResource::canEdit(Role::where('slug', 'viewer')->first()), 'isi peran berlaku global, hanya Super Admin');
        $this->assertNotContains($staffB->email, $visible);
    }

    public function test_super_admin_sees_everything(): void
    {
        [$a, $periodA] = $this->company('AAA');
        [$b] = $this->company('BBB');
        $this->user('staf@bbb.test', 'kpi-owner', $b);
        $root = $this->user('root@ruci.test', 'super-admin', null);

        $this->actingAs($root);
        $this->useContext($periodA);

        $this->assertSame(2, UserResource::getEloquentQuery()->count());
        $this->assertSame(2, RoleAssignment::count());
        $this->assertTrue(RoleResource::canEdit(Role::where('slug', 'viewer')->first()));
    }

    public function test_users_cannot_switch_into_a_company_they_do_not_belong_to(): void
    {
        [$a] = $this->company('AAA');
        [$b] = $this->company('BBB');
        $adminA = $this->user('admin@aaa.test', 'company-admin', $a);

        $this->assertSame([$a->id], $adminA->accessibleCompanyIds());
        $this->assertNotContains($b->id, $adminA->accessibleCompanyIds());
    }

    public function test_context_page_explains_what_to_do_when_there_is_no_company_yet(): void
    {
        $root = $this->user('root@ruci.test', 'super-admin', null);

        $this->actingAs($root)->get('/admin/pilih-konteks')
            ->assertOk()
            ->assertSee('Belum ada satker BLU')
            ->assertSee('Tambah satker BLU');

        [$a] = $this->company('AAA');
        $a->periods()->delete();
        $this->get('/admin/pilih-konteks')->assertOk()->assertSee('belum punya perioda');
    }
}
