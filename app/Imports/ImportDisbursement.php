<?php

namespace App\Imports;

use App\Models\Disbursement;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportDisbursement implements ToModel, WithHeadingRow,WithValidation
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
     * @return Disbursement
     * @throws \Exception
     */
    public function model(array $row)
    {

        return new Disbursement([

            'first_name'     => @$row["first_name"],
            'last_name'    => @$row["last_name"],
            'phone_number'    => @$row["phone_number"],
            'amount'=>@$row["amount"],
            'batch_no'=> $this->batch_number,
            'payment_detail'=>$row["payment_details"],

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
            'phone_number'=>['required','regex:/^(0|(\+)?255)?[0-9]{9}$/'],
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
