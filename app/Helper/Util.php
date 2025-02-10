<?php


namespace App\Helper;


class Util
{
	const  TO_MNO =  1;
    const TO_BANK =  2;
	
        public static function generateRandom($length){
        $date = new \DateTime('now');
        $date = $date->format('ymdHisv');
       return $date.bin2hex(random_bytes((min($length,20)-15)/2));
    }


    public static function addPhonePrefix($phone)
    {
        return strlen($phone)<=9?'255'.$phone:preg_replace('/^0/','255',$phone);
    }
}
