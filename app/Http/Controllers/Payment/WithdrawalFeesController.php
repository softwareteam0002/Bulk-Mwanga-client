<?php
/*
 *
 * Class Created By Arnold Chamu BCX 2019
 *
 * Arndeverafter
 *
 */

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Redirect;
use Response;
use Session;
use View;


class WithdrawalFeesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * @var array
     */
    protected $rules =
        [
            'max' => 'required|gte:0',
            'min' => 'required|gte:0',
            'charge' => 'required|gte:0',
        ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $charges = DB::table('withdrawal_fees')->get();
        return view('withdrawal_fees.index', ['charges' => $charges]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('withdrawal_fees.add_form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Permission::canCreateWithdrawalFees()) {
            return redirect()->route('withdrawal_fees')->with('alert-warning', 'You do not have permission to perform this action!');
        }

        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return redirect()->route('withdrawal_fees.create')->with('alert-danger', $validator->errors()->first());
        } else {
            $min_amount = $request->post('min');
            $max_amount = $request->post('max');
            $charge_amount = $request->post('charge');

            if ($min_amount >= $max_amount) {
                $message = 'Minimum amount can not be greater than the Maximum Amount!';
                return redirect()->route('withdrawal_fees.create')->with('alert-danger', $message);
            }

            $overlapping_range = DB::select(
                DB::raw("SELECT * FROM withdrawal_fees Where NOT ( `min_amount` < :st and `max_amount` < :st_ OR `min_amount` > :et and `max_amount` > :et_ )"),
                array(
                    ':st' => $min_amount,
                    ':et' => $max_amount,
                    ':st_' => $min_amount,
                    ':et_' => $max_amount,
                )
            );

            if (empty($overlapping_range)) {
                $data_insert['min_amount'] = $min_amount;
                $data_insert['max_amount'] = $max_amount;
                $data_insert['fee'] = $charge_amount;

                //Insert the record into the database
                if (DB::table('withdrawal_fees')->insert($data_insert)) {
                    $message = 'Withdrawal fee was added Successfully';
                    return redirect()->route('withdrawal_fees')->with('alert-success', $message);
                } else {
                    $message = "Faile to add new withdrawal fee!";
                    return redirect()->route('withdrawal_fees.create')->with('alert-danger', $message);
                }
            } else {
                $message = "The provided amount ranges overlaps with other existing amounts, please crosscheck";
                return redirect()->route('withdrawal_fees.create')->with('alert-danger', $message);
            }
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $charge = DB::table('withdrawal_fees')->find($id);
        return view('withdrawal_fees.edit_form', ['charge' => $charge]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return RedirectResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Permission::canCreateWithdrawalFees()) {
            return redirect()->route('withdrawal_fees')->with('alert-warning', 'You do not have permission to perform this action!');
        }

        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return redirect()->route('withdrawal_fees.create')->with('alert-danger', $validator->errors()->first());
        } else {
            $min_amount = $request->post('min');
            $max_amount = $request->post('max');
            $charge_amount = $request->post('charge');

            $overlapping_range = DB::select(
                DB::raw("SELECT * FROM withdrawal_fees WHERE id<> :id AND NOT ( `min_amount` < :st and `max_amount` < :st_ OR `min_amount` > :et and `max_amount` > :et_ )"),
                array(
                    ':id' => $id,
                    ':st' => $min_amount,
                    ':et' => $max_amount,
                    ':st_' => $min_amount,
                    ':et_' => $max_amount,
                )
            );

            if (!empty($overlapping_range)) {
                $message = "The provided amount ranges overlaps with other existing amounts, please crosscheck";
                return redirect()->route('withdrawal_fees.edit', $id)->with('alert-danger', $message);
            }

            $data_update['min_amount'] = $min_amount;
            $data_update['max_amount'] = $max_amount;
            $data_update['fee'] = $charge_amount;
            if (DB::table('withdrawal_fees')->where(['id' => $id])->update($data_update)) {
                $message = 'Withdrawal fee was updated Successfully';
                return redirect()->route('withdrawal_fees')->with('alert-success', $message);
            } else {
                $message = "Could not update withdrawal fee!";
                return redirect()->route('withdrawal_fees.edit', $id)->with('alert-danger', $message);
            }
        }

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {
        if (!Permission::canCreateWithdrawalFees()) {
            return redirect()->route('withdrawal_fees')->with('alert-warning', 'You do not have permission to perform this action!');
        }

        $withdrawal_fee = DB::table('withdrawal_fees')->where(['id' => $id]);
        if ($withdrawal_fee->exists()) {
            $withdrawal_fee->delete();
            return redirect()->route('withdrawal_fees')->with('alert-success', 'The entry was successful deleted!');
        } else {
            return redirect()->route('withdrawal_fees')->with('alert-danger', 'Operation could not be completed!');
        }
    }

}
