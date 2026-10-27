<?php

namespace App\Services\Import;

use RuntimeException;

/** Kesalahan pada satu baris workbook; baris dilewati dan dicatat di laporan import. */
class ImportRowException extends RuntimeException {}
