<?php


namespace App\Audit;


use App\Models\AuditActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Audit extends Model
{

    public static function saveActivityLogDb($doneBy, $affectedAccount, $activityDescription, $eventType, $action_type)
    {

        $sourceIp = self::getUserIP();

        try {
            $audit = new AuditActivity();

            $audit->username = $doneBy;
            $audit->affected_account = $affectedAccount;
            $audit->activity_description = $activityDescription;
            $audit->event_type = $eventType;
            $audit->source_ip = $sourceIp;
            $audit->action_type = $action_type;

            $audit->save();
        } catch (\Exception $e) {
            Log::error("Failed to save audit log: " . $e->getMessage());
        }

    }


    public static function getUserIP()
    {
        return request()->getClientIp() ?? 'UNKNOWN';
    }
}
