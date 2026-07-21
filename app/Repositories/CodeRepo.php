<?php

namespace App\Repositories;

use App\Master\StoreProspek;
use DB;

class CodeRepo
{
    public static function generateCustomer()
    {
        return self::generate('C', StoreProspek::class);
    }

    private static function generate($pre = '', $class)
    {
        $count = $class::withTrashed()->count() + 1;
        $code = $pre . sprintf('%05d', $count);

        return $code;
    }
}