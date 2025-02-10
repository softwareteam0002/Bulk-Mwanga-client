<?php

namespace App\Imports;

use App\Models\BankDisbursementVerification;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportBankDisbursement implements ToModel, WithHeadingRow,WithValidation
{

    private $batch_number;

    public function __construct($batch_number)
    {
        $this->batch_number = $batch_number;
    }

    /**
     * ImportDisbursement constructor.
     */


    /**
     * @param array $row
     *
     * @return BankDisbursementVerification
     * @throws \Exception
     */
    public function model(array $row)
    {

        //dd($this->batch_number);
        //NOTE: Bank ID is the Bank ShortCode, Account number has a Prefix depending on the Bank
        return new BankDisbursementVerification([
            'first_name'=> @$row["first_name"],
            'last_name'=> @$row["last_name"],
            'bank'=> @$row["bank"],
            'amount'=>@$row["amount"],
            'account_number'=>DB::table('banks')->select('prefix')->where(['name'=> trim(@$row["bank"])])->first()->prefix.@$row["account_number"],
            'batch_no'=> $this->batch_number,
            'payment_detail'=>$row["payment_details"],
            'phone_number'=>$row["phone_number"],
            'bank_id'=>DB::table('banks')->select('bank_id')->where(['name'=> trim(@$row["bank"])])->first()->bank_id

        ]);

    }

    /**
     * @return array
     */

    public function rules(): array
    {

        return  [
            'first_name'=>'required',
            'last_name'=>'required',
            'bank'=>'required',
            'account_number'=>['required'],
            'amount'=>'required|numeric|between:0.0,10000000.0',

        ];

    }

    /**
     * @return array
     */

    public function customValidationMessages()
    {
        return [
        ];
    }
}
