<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ModelDataHelper
{
    public static  function getOrganizationById($id){

        Log::info(' id '.$id);
        $org  =  DB::table('organizations')
            ->select('organizations.name')
            ->where(['id'=>$id])
            ->first();

        return $org->name;
    }
}