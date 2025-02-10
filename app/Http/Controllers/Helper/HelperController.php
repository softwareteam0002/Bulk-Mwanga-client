<?php


namespace App\Http\Controllers\Helper;


use App\Models\BankBatchPayment;
use App\Models\BankPaymentDisbursement;
use App\Models\BankVerificationBatch;
use App\Models\Batch;
use App\Models\BatchPayment;
use App\Models\ConstantHelper;
use App\Models\DisbursementPayment;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

/* class that manage all helper functions for web portal*/

class HelperController
{


    public function getDistrictByRegionId(Request $request)
    {

        $id = $request->id;

        return DB::table('districts')->where('region_id', $id)->get();

    }

    public static function generateBatchNumber($short_code, $transactionType = null)
    {


//        dd($transactionType);
        if ($transactionType == 'bank') {

            $bshort_code = 'B' . $short_code;
            $batch = BankVerificationBatch::query()->whereRaw("batch_no LIKE '{$bshort_code}%'")->orderBy('batch_no', 'DESC')->first();


        } else {
            $batch = Batch::query()->whereRaw("batch_no LIKE '{$short_code}%'")->orderBy('batch_no', 'DESC')->first();


        }

        if (empty($batch)) {
            return $short_code . '-' . str_pad('1', 5, "0", STR_PAD_LEFT);
        }
        $batch_nos = explode('-', $batch->batch_no);
        $last_no = count($batch_nos) > 1 ? $batch_nos[1] : '';
        if (empty($last_no)) {
            return $short_code . '-' . str_pad('1', 5, "0", STR_PAD_LEFT);
        }

        $last_no = $last_no + 2;

        if ($transactionType == 'bank') {


            return $short_code . '-' . str_pad($last_no, 5, "0", STR_PAD_LEFT);

        }
        return $short_code . '-' . str_pad($last_no, 5, "0", STR_PAD_LEFT);

    }


    public static function batch()
    {
        $date = Carbon::now('Africa/Nairobi')->toDateTimeString();

        $timestamp = trim(str_replace(['-', ':', ' '], '', $date));

        return $timestamp;
    }


    public static function checkUserAccountStatus()
    {

    }


    public static function checkOrganizationStatus()
    {

        $organizationId = Auth::user()->organization_id;

        $org = Organization::where('id', $organizationId)->first();

        if ($org->status === 0) {


            return 0;

        } else {

            return 1;
        }

    }


    public static function token()
    {
        if (env('APP_ENV') == 'local') {
            return 123456;
        }
        return random_int(100000, 999999);
    }

    public static function generatePasswod()
    {
        $alphabet = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ123456789';
        $pass = [];
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = random_int(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        array_splice($pass, random_int(1, count($pass) - 1), 0, (array)self::addSpecial()[0]);

        $pas = implode(',', $pass);

        return str_replace(',', '', $pas);
    }

    public static function addSpecial()
    {

        $alphabet = '*!.#';
        $pass = [];
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 3; $i++) {
            $n = random_int(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass);

    }

    public static function approveOrganizationByVoda($id)
    {


        $totalNumber = Organization::where('id', $id)->first();

        $totalNumber = $totalNumber->approved_by_voda_no;

        if ($totalNumber === 2) {

            return 0;

        }

        $totalNumber = $totalNumber + 1;

        $success = DB::table('organizations')->where('id', '=', $id)
            ->update(['approved_by_voda_no' => $totalNumber]);

        if ($success) {

            return 1;
        }

        return 0;
    }


    public static function checkIfHasPermission($permId)
    {

        $user = \App\Models\User::with('roles')->where('id', '=', Auth::user()->id)->first();

        $found = 'false';
        foreach ($user['roles'] as $perm) {
            foreach ($perm['permissions'] as $permission) {
                if ($permission->id == $permId) {

                    $found = 'true';

                }

            }
        }

        return $found;
    }

    /**
     * @param $batchId
     * @return RedirectResponse
     */

    public function rejectBatchPayment(Request $request)
    {


        $reason = $request->reason;

        $transactionType = $request->transactionType;

        try {

            $batchId = decrypt($request->batchNo);

        } catch (DecryptException $exception) {

            return back();
        }

        DB::beginTransaction();
        try {


            if ($transactionType == 'bank') {

                $success = BankBatchPayment::query()->where(['batch_no' => $batchId])
                    ->update(['batch_status_id' => Batch::STATUS_CANCELLED,
                        'is_rejected' => ConstantHelper::BATCH_REJECTED,
                        'reason_if_rejected' => $reason, 'rejected_by' => Auth::user()->id]);

                BankPaymentDisbursement::query()->where(['batch_no' => $batchId])
                    ->update(['payment_status' => 4]);
            } else {

                $success = BatchPayment::query()->where(['batch_no' => $batchId])
                    ->update(['batch_status_id' => Batch::STATUS_CANCELLED,
                        'is_rejected' => ConstantHelper::BATCH_REJECTED,
                        'reason_if_rejected' => $reason, 'rejected_by' => Auth::user()->id]);

                DisbursementPayment::query()->where(['batch_no' => $batchId])
                    ->update(['payment_status' => 4]);
            }

            DB::commit();
            Session::flash('alert-success', 'Successful Rejected');
        } catch (\Exception $exception) {

            DB::rollBack();
            Session::flash('alert-danger', 'Failed To Reject.');

        }

        return redirect()->back();
    }


}

