<?php


namespace App\Audit;


use App\Models\AuditActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Audit extends Model
{

    public static function saveActivityLogDb($doneBy,$affectedAccount,$activityDescription,$eventType,$action_type){

        $sourceIp  =  self::getUserIP();

        $audit  =  new AuditActivity();

        $audit->username  = $doneBy ;
        $audit->affected_account =$affectedAccount;
        $audit->activity_description = $activityDescription;
        $audit->event_type = $eventType;
        $audit->source_ip =$sourceIp;
        $audit->action_type =$action_type;

        $audit->save();

    }

    public function saveActivityLogFile($doneBy,$affectedAccount,$activityDescription,$eventType){

        $sourceIp  =  self::getUserIP();

        $message  =  "message: ["." Done By ".$doneBy.' Account Affected '
                    .$affectedAccount.' Description: '.$activityDescription.' Event Type: '
                     .$eventType.' IP ADDRESS '.$sourceIp;

        Log::channel('activityLogs')->info($message);

    }

    public function captureErrorsExceptions(){


    }

    static function getUserIP() {
        return request()->getClientIp()??'UNKNOWN';
    }
}
