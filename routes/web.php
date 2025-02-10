<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Helper\DisbursementApiHelper;
use App\Http\Controllers\Api\BankPaymentController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Middleware\CheckSessionTimeout;
use App\Http\Middleware\VerifyPasswordExpiry;
use App\Jobs\BankBalanceCheckForDisbursement;
use App\Jobs\ProcessBatch;
use App\Models\BankBatchProcessing;
use App\Models\Batch;
use App\Models\BatchProcessing;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => App\Http\Middleware\HttpsProtocol::class], function () {

    Route::get('errors', function () {

        return view('errors.404');

    });

    Route::get('/dashboard', 'HomeController@dashboard')->middleware([VerifyPasswordExpiry::class, CheckSessionTimeout::class]);
    Route::get('get-dashboard-data', 'HomeController@dashboardData')->name('dashboard-data');

    Route::get('send-mail', 'Mail\MailController@sendMail');

    Route::group(['prefix' => '/organization', 'middleware' => ['session-timeout-check', 'password-expiry']], function () {


        Route::get('/view/{organizationId}', 'Organization\OrganizationController@view');
        // user management
        Route::get('/users-all/{organizationId?}', 'Organization\OrganizationController@users');
        Route::get('/users/create/{organizationId?}', 'Organization\OrganizationController@createUsers');
        Route::get('/users/{id}/edit/{organizationId}', 'Organization\OrganizationController@userEdit')->name('organization-user-edit');
        Route::post('/users/store/{organizationId}', 'Organization\OrganizationController@storeUser');
        Route::post('/users/{id}/update/{organizationId}', 'Organization\OrganizationController@userUpdate')->name('organization-user-update');
        Route::get('/users/view/{userId}', 'Organization\OrganizationController@userView')->name('organization-user-view');
        Route::post('/number-approvals', 'Organization\OrganizationController@numberApprovals');
        Route::get('details-management', 'Organization\OrganizationController@detailsManagement');

        //setup initiator.

    });

    Route::group(['prefix' => '/initiator'], function () {
        Route::get('', 'Initiator\InitiatorController@index')->name('organization-initiator');;
        Route::get('/create', 'Initiator\InitiatorController@create')->name('initiator-create');
        Route::post('store', 'Initiator\InitiatorController@store');
        Route::get('/edit', 'Initiator\InitiatorController@edit')->name('initiator-edit');;
        Route::post('update/{id}', 'Initiator\InitiatorController@update');

    });

    Route::group(['prefix' => 'disbursement', 'middleware' => ['session-timeout-check', 'password-expiry']], function () {
        Route::post('/query-balance', 'Payment\PaymentController@queryOrgBalance')->name('query-balance');
        Route::get('/check-balance-availability/{tx_id}', 'Payment\PaymentController@checkBalanceAvailability')->name('query-balance');
        Route::get('/verification', 'Verification\VerificationController@verification')->name('disbursement.verifications');
        Route::get('/payments', 'Payment\PaymentController@payments')->name('disbursement.payments');
        Route::get('view/{batch_no}', 'Verification\VerificationController@verificationView');
        Route::get('create', 'Verification\VerificationController@create');
        Route::post('store', 'Verification\VerificationController@store')->middleware('has-approval-levels');
        Route::get('view', 'Verification\VerificationController@view');
        Route::get('progress', 'Verification\VerificationController@progress');
        Route::get('mnp-search-request/{batch_no}', 'Verification\VerificationController@mnpSearch')->middleware('has-permission:' . \App\Helper\PermissionList::VERIFY_EXCEL);
        Route::get('mnp-search-status/{batch_no}', 'Verification\VerificationController@mnpSearchStatus');
        Route::get('disbursement-status/{batch_no}', 'Payment\PaymentController@disbursementStatus');
        Route::get('payment-request', 'Payment\PaymentController@paymentRequest')->middleware('rejected', 'approve-payment', 'batch-approval-validation');
        Route::get('payment-retry/{batch_no}', 'Payment\PaymentController@paymentRetry')->middleware('approve-payment');
        Route::get('payment/view/{batch_no}', 'Payment\PaymentController@paymentView');

//start
        Route::get('verification-clear', function () {

            if (\Illuminate\Support\Facades\Auth::user()->username == 'baraka.machumu') {

                $batch = Batch::query()
                    ->whereDate('created_at', '=', date('Y-m-d'))->where(['batch_status_id' => 1])->get();

                Log::info('B-BATCH', ['TOTAL' => count($batch), 'MESSAGE' => $batch]);

                foreach ($batch as $row) {

                    $operation = 'verify';

                    $batch = Batch::query()->where(['id' => $row->id])->first();
                    $batch_id = $row->id;

                    Log::info('BATCH-DATA-ROW', ['MESSAGE' => $row]);
                    if (!PaymentController::checkBatchValidity($batch, $operation)) {
                        $re = DisbursementApiHelper::ERROR_COULD_NOT_QUEUE;
                        Log::info('NOT-QUEUED', ['MESSAGE' => $re]);
                    } else {
                        if ($operation == 'verify') {
                            $batch_processing = BatchProcessing::query()->where(['batch_id' => $batch_id])->first();
                            if (!$batch_processing) {
                                $batch_processing = BatchProcessing::addQueuedBatch($operation, $batch, $batch->created_by);

                            }

                            $time = strtotime($batch_processing->created_at) - strtotime(date('Y-m-d h:i:s'));

                            if ($batch_processing->operation == 'VERIFY' && $batch_processing->status == 'QUEUED') {
                                Log::info('VERIFY QUEUED MANUALLY');
                                Queue::later(0, new ProcessBatch($batch_id, $operation, $batch_processing->id));
                            }

                        }

                        $batch->update(['batch_status_id' => Batch::STATUS_QUEUED]);
                        Log::info('SUCCESSFUL-RE-QUEUED', ['MESSAGE' => DisbursementApiHelper::QUEUED]);
                    }

                }

            }


        });

        //end

//start bk


        Route::get('verification-clear-bk', function () {

            if (\Illuminate\Support\Facades\Auth::user()->username == 'baraka.machumu') {


                $operation = 'disburse';
                $batch_id = '898';
                $batch = \App\Models\BankBatchPayment::query()->where(['id' => $batch_id])->first();

                if (!BankPaymentController::checkBatchValidity($batch, $operation)) {
                    return DisbursementApiHelper::ERROR_COULD_NOT_QUEUE;
                } else {

                    $batch_processing = BankBatchProcessing::query()->where(['batch_id' => $batch_id])->first();
//                    if (!$batch_processing){
//                        BankBatchProcessing::addQueuedBatch($operation, $batch, Auth::id());
//                    }
//
                    Queue::later(0, new BankBalanceCheckForDisbursement($batch_id, Auth::id()));

                    $batch->update(['batch_status_id' => Batch::STATUS_QUEUED]);
                    return DisbursementApiHelper::QUEUED;
                }


            }


        });

//end bk

    });

    Route::group(['prefix' => 'bank-disbursement', 'middleware' => ['session-timeout-check', 'password-expiry']], function () {

        Route::get('/verifications', 'Verification\BankVerificationController@index')->name('bank-disbursement.payments');
        Route::get('create', 'Verification\BankVerificationController@create');
        Route::post('store', 'Verification\BankVerificationController@store');
        Route::get('view/{batch_no}', 'Verification\BankVerificationController@verificationView');
        Route::get('bankname-search-request/{batch_no}', 'Verification\BankVerificationController@bankNameSearch')->middleware('has-permission:' . \App\Helper\PermissionList::VERIFY_EXCEL);

        Route::get('mnp-search-status/{batch_no}', 'Verification\BankVerificationController@mnpSearchStatus');
        Route::get('disbursement-status/{batch_no}', 'Payment\BankPaymentController@disbursementStatus');

        Route::get('/payments', 'Payment\BankPaymentController@payments')->name('bank-disbursement.payments');
        Route::get('payment-request', 'Payment\BankPaymentController@paymentRequest')->middleware('rejected', 'approve-payment', 'batch-approval-validation');

    });

    Route::get('disbursement-payment/view/{batch_no}', 'Payment\PaymentController@disbusementView')->middleware(['session-timeout-check', 'password-expiry']);
    Route::get('bank-disbursement-payment/view/{batch_no}', 'Payment\BankPaymentController@disbusementView')->middleware(['session-timeout-check', 'password-expiry']);


    Route::group(['prefix' => 'roles', 'middleware' => ['session-timeout-check', 'password-expiry']], function () {
        Route::get('/', 'Role\RoleController@index');
        Route::get('/create', 'Role\RoleController@create');
        Route::post('/store', 'Role\RoleController@store');
        Route::get('/{roleId}/edit', 'Role\RoleController@edit')->name('role-edit');
        Route::post('/update/{roleId}', 'Role\RoleController@update');
        Route::get('/view/{roleId}', 'Role\RoleController@view');
        Route::post('/delete', 'Role\RoleController@delete');
    });


    Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('weblogin', 'Auth\LoginController@webLogin');

    Route::post('login-verify', 'Auth\TwoFactorLoginController@verified');
    Route::get('verify-code-login', 'Auth\TwoFactorLoginController@tokenVerify');
    Route::post('resend-token', 'Auth\TwoFactorLoginController@resendToken');
    Route::post('change-password', 'Auth\ResetPasswordController@reset');
    Route::get('first-change-password', 'Auth\TwoFactorLoginController@firstLogin')->name('login.first');
    Route::get('change-password', 'Auth\TwoFactorLoginController@firstLogin')->name('change.password');
    Route::post('weblogout', 'Auth\LoginController@logout');
    Route::post('logout-current-session', 'Auth\LoginController@logoutCurrentSession');
    Route::get('locked', 'Auth\LoginController@locked');
    Route::get('lock', 'Auth\LoginController@lock');
    Route::post('unlock', 'Auth\LoginController@unlock');
    Route::get('multiple-sessions', 'Auth\LoginController@hasMultipleSessions');
    Route::post('logout-other-devices', 'Auth\LoginController@logoutOtherSessions');

//helper urls

    Route::group(['prefix' => 'help', 'middleware' => ['session-timeout-check', 'password-expiry']], function () {
        Route::post('download/format', 'Helper\DownloadController@downloadUploadExcelFormat');

        Route::get('download/batch-verification/{batchNo}', 'Helper\DownloadController@downloadBatch')->name('download.batch');

        Route::get('download/batch-disbursement/{batchNo}', 'Helper\DownloadController@downloadBatchDisbursement')->name('download.batch-payment');
        Route::get('districts/get-all', 'Helper\HelperController@getDistrictByRegionId')->name('districts.get');

        Route::post('batch-reject', 'Helper\HelperController@rejectBatchPayment');


        //bank helper url

        Route::get('bank-download/batch-verification/{batchNo}', 'Helper\BankDownloadController@downloadBatch')->name('bank-download.batch');

        Route::get('bank-download/batch-disbursement/{batchNo}', 'Helper\BankDownloadController@downloadBatchDisbursement')->name('bank-download.batch-payment');

    });

// payment
    Route::group(['prefix' => 'payment', 'middleware' => ['session-timeout-check', 'password-expiry']], function () {

        Route::post('payment-request/disbursement', 'Payment\PaymentController@disbursement');
    });

    Route::group(['prefix' => 'reports', 'middleware' => ['session-timeout-check', 'password-expiry']], function () {

        Route::get('/', 'Report\ReportController@index');

        Route::get('disbursement-per-organization', 'Report\ReportController@disbursementPerOrganization');
        Route::post('disbursement-per-organization', 'Report\ReportController@getDisbursementPerOrganization');
        Route::get('/payment/view-all-batch/{batch_no}', 'Report\ReportController@viewAllInBatch');

        Route::post('/payment/batch-payment-per-batch/{batch_no}', 'Report\ReportController@downloadAllInBatch');

        //by batch
        Route::get('disbursement-per-batch', 'Report\ReportController@disbursementPerBatch');
        Route::post('disbursement-per-batch', 'Report\ReportController@fetchDisbursementPerBatch');

        //by date
        Route::get('disbursement-by-date', 'Report\ReportController@disbursementByDate');
        Route::post('disbursement-by-date', 'Report\ReportController@fetchDisbursementByDate');

        Route::post('disbursement-by-date-export/multiple', 'Report\ReportController@exportMultipleDisbursementByDate');


        //bank reports
        Route::group(['prefix' => 'bank', 'middleware' => ['session-timeout-check', 'password-expiry']], function () {
            Route::get('/', 'Report\BankReportController@index');
            Route::get('disbursement-per-organization', 'Report\BankReportController@disbursementPerOrganization');
            Route::post('disbursement-per-organization', 'Report\BankReportController@getDisbursementPerOrganization');
            Route::get('/payment/view-all-batch/{batch_no}', 'Report\BankReportController@viewAllInBatch');
            Route::post('/payment/batch-payment-per-batch/{batch_no}', 'Report\BankReportController@downloadAllInBatch');

            //by batch
            Route::get('disbursement-per-batch', 'Report\BankReportController@disbursementPerBatch');
            Route::post('disbursement-per-batch', 'Report\BankReportController@fetchDisbursementPerBatch');

            //by date
            Route::get('disbursement-by-date', 'Report\BankReportController@disbursementByDate');
            Route::post('disbursement-by-date', 'Report\BankReportController@fetchDisbursementByDate');

            Route::post('disbursement-by-date-export/multiple', 'Report\BankReportController@exportMultipleDisbursementByDate');
        });

        //general reports
        Route::group(['prefix' => 'general', 'middleware' => ['session-timeout-check', 'password-expiry']], function () {
            Route::get('/', 'Report\GeneralReportController@index');
            Route::get('disbursement-per-organization', 'Report\GeneralReportController@disbursementPerOrganization');
            Route::post('disbursement-per-organization', 'Report\GeneralReportController@getDisbursementPerOrganization');
            Route::get('/payment/view-all-batch/{batch_no}', 'Report\GeneralReportController@viewAllInBatch');
            Route::post('/payment/batch-payment-per-batch/{batch_no}', 'Report\GeneralReportController@downloadAllInBatch');

            //by date
            Route::get('disbursement-by-date', 'Report\GeneralReportController@disbursementByDate');
            Route::post('disbursement-by-date', 'Report\GeneralReportController@fetchDisbursementByDate');

            Route::post('disbursement-by-date-export/multiple', 'Report\GeneralReportController@exportMultipleDisbursementByDate');
        });


    });

// setup

//simulation
    Route::group(['prefix' => 'api-simulate'], function () {

        Route::post('network-name-search', 'Api\SimulatorController@networkNameSearch');
        Route::post('customer-name-search-vd', 'Api\SimulatorController@customerNameSearch');
        Route::post('customer-name-search-on', 'Api\SimulatorController@customerNameSearch');
        Route::post('disburse', 'Api\SimulatorController@disburse');
        Route::post('balance-check', 'Api\SimulatorController@balanceCheck');
        Route::post('kyc', 'Api\SimulatorController@kyc');

    });


});

//API callbacks
Route::group(['middleware' => ['ip-filter']], function () {
    Route::post('kyc-callback', 'Api\OrganizationController@kycCallback')->name('kyc-callback');
    Route::post('disbursement-callback', 'Api\PaymentController@disbursementCallback')->name('disbursement-callback');
    Route::post('bank-disbursement-callback', 'Api\BankPaymentController@bankDisbursementCallback')->name('bank-disbursement-callback');
    Route::post('balance-check-callback', 'Api\PaymentController@balanceCallback')->name('balance-callback');
    Route::get('check-balance', 'Api\BalanceInquiryController@checkBalance')->name('balance-check');

});

//Route::get('/dailyreport','Report\InternalReportController@generateReport');
//Route::get('/monthlyreport','Report\InternalReportController@generateMonthlyReport');
//Route::get('/monitoring', 'Report\InternalReportController@dailyTransactions');

//Added for Monitoring Tool V2
Route::group(['middleware' => ['monitoring-tool']], function () {
    //Route::get('/monitoring/v2', 'Report\InternalReportController@intervalReportTransactions');
});

Route::get('forgot_password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'index'])->name('forgot_password');
Route::get('set_credentials', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])->name('set_credentials');
Route::post('reset_link', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLink'])->name('reset_link');
Route::post('update_password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'updatePassword'])->name('update_password');
