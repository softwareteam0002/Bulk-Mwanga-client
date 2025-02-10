<?php
/**
 * Author: Memory Mwageni <memorymwageni@gmail.com>
 * Date: 10/07/2019
 * Time: 11:57
 */

namespace App\Helper;


use App\Models\ApiCredential;
use App\Models\Initiator;

class CredentialsRepo
{

    public static $credentials = [];
    /**
     * Check if the given credentials are valid
     * @param $partnerId
     * @param $username
     * @param $password
     * @return bool
     */
    public static function customApiAuthenticate($username, $password){
        return self::getMpesaApiPassword()==$password && self::getMpesaApiUsername()==$username;
    }

    public static function getInitiatorPassword($organization_id){
        return self::fetchInitiatorCredentials($organization_id)['password'];

    }

    public static function getInitiatorUsername($organization_id){
        return self::fetchInitiatorCredentials($organization_id)['username'];
    }

    public static function getMpesaApiPassword(){
        return self::fetchCredentials('MPESA_CUSTOM_API')['password'];

    }

    public static function getMpesaApiUsername(){
        return self::fetchCredentials('MPESA_CUSTOM_API')['username'];
    }

    private static function fetchCredentials($name='MPESA_CUSTOM_API')
    {
        if (empty(self::$credentials[$name])){
            $credenitals = ApiCredential::query()->where(['name'=>$name])->first();

            if (empty($credenitals)){
                throw new \Exception("Credentials for {$name} not set");
            }
            self::$credentials[$name]['password'] = decrypt($credenitals->password);
            self::$credentials[$name]['username'] =  $credenitals->username;
            self::$credentials[$name]['pin'] =  $credenitals->pin;
        }

        return self::$credentials[$name];
    }
	
	private static function fetchPinCredentials($name='MPESA_CUSTOM_API')
    {
        if (empty(self::$credentials[$name])){
            $credenitals = ApiCredential::query()->where(['name'=>$name])->first();

            if (empty($credenitals)){
                throw new \Exception("Credentials for {$name} not set");
            }
            self::$credentials[$name]['pin'] =  $credenitals->pin;
        }

        return self::$credentials[$name];
    }

    private static function fetchInitiatorCredentials($organization_id)
    {

        $credential = Initiator::query()->where(['organization_id'=>$organization_id,'status'=>'ACTIVE'])->first();
        if (empty($credential)){
            throw new \Exception("Credentials for OID {$organization_id} not set");
        }


        self::$credentials[$organization_id]['password'] = self::encryptInitiatorPassword(decrypt($credential->password));
        self::$credentials[$organization_id]['username'] =  $credential->username;

        return self::$credentials[$organization_id];
    }


    private static function hasValidInitiator($organization_id){
        return Initiator::query()->where(['organization_id'=>$organization_id,'status'=>'ACTIVE'])->exists();
    }


    public static function encryptInitiatorPassword($password)
    {
        $plain_key = file_get_contents(storage_path('/key/vd-public.key'));
        $publicKey = openssl_get_publickey($plain_key);
        if (!$publicKey) {
            throw new \Exception("Public key NOT OK\n".openssl_error_string());
        }
        if (!openssl_public_encrypt($password, $cipher, $publicKey)){
            throw new \Exception("Failed to encrypt");
        }
        return base64_encode($cipher);
    }
	
	public static function getMPesaBankDisbursementAPIUsername(){
        //return "bcxportal";
        return self::fetchCredentials('MPESA_BANK_CUSTOM_API')['username'];
    }

    public static function getMPesaBankDisbursementAPIPassword(){
        //return "Jndhwie-3173";
        return self::fetchCredentials('MPESA_BANK_CUSTOM_API')['password'];
    }

    public static function getMPesaBankAccountNameSearchAPIPin(){
        //return "4321";
        return self::fetchCredentials('MPESA_BANK_CUSTOM_API')['pin'];
    }
}
