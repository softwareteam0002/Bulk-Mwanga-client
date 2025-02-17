<?php

namespace App\Helper;

use App\Models\FspCode;

class FspCodeHelper
{
    public static function getFspName($fsp_id)
    {
        if (!$fsp_id) {
            return null;
        }
        return FspCode::query()->where('fsp_code', $fsp_id)->first()->fsp_name ?? null;
    }
}
