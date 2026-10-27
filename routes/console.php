<?php

use Illuminate\Support\Facades\Schedule;

// Pengingat siklus Ruci BSC setiap hari pukul 07.30 WIB
Schedule::command('rucibsc:reminders')->dailyAt('07:30')->timezone('Asia/Jakarta')->withoutOverlapping();

// Antrian (driver database) dijalankan dari scheduler — cocok untuk hosting cPanel
Schedule::command('queue:work --stop-when-empty --tries=3')->everyMinute()->withoutOverlapping();

// Bersihkan notifikasi yang sudah dibaca > 90 hari
Schedule::call(fn () => \Illuminate\Support\Facades\DB::table('notifications')->whereNotNull('read_at')->where('read_at', '<', now()->subDays(90))->delete())->weekly();
