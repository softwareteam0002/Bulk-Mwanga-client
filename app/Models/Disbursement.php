<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/* class that manage all verification entries*/
class Disbursement extends Model
{
    const STATUS_NOT_PAID = 0;
    const STATUS_PAID = 1;
    const STATUS_ERROR = 2;

    protected $table = 'disbursements';
    protected $guarded =[];

    public  static function total($batch_no){

        $total  =  Disbursement::query()->where(['batch_no'=>$batch_no])->get();

        return $total;

    }

    public  static function currentVerified($batch_no){

        $total  =  Disbursement::query()->where(['batch_no'=>$batch_no,'payment_status'=>1])->get();

        return $total;

    }

    public  static function percentage($batch_no)
{

    return round(self::currentVerified($batch_no)/self::total($batch_no),0);

}

    public function batch(){
        return $this->belongsTo('App\Models\Batch','batch_no','batch_no');
    }

}
