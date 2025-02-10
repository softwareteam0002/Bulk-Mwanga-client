<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/* class that manage constants*/
class ConstantHelper extends Model
{
    //

    public  const   INTERNAL_USER =1;
    public  const   ORGANIZATION_USER =2;

    public  const SEND_OTP_BY_EMAIL=2;

    public  const  ALL_ORGANIZATION_GET_REPORT = 'A1001';

    public  const NOT_APPROVAL='NA1001';


    public  const DELEGATION_DESCRIPOTION =  "Delegating the role";
    public  const  UPDATING  =  "Updating";

    public  const  BATCH_REJECTED = "YES";
}
