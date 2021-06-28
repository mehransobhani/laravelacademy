<?php

namespace App\Http\Controllers\api;

use App\Custom\Jibit\Jibit;
use App\Custom\Pasargad\Pasargad;
use App\Http\Controllers\Controller;
use App\Models\ClassTrans;
use App\Models\Course;
use App\Models\course_user;
use App\Models\Gift;
use App\Models\GiftUsage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function checkDiscount(Request $request)
    {
        $response = Gift::is_discount_ok($request->discountCode , $request->course);

        return response()->json($response);
    }



    public function request(Request $request){

        $course = Course::find($request->class_id);
        $user = Auth::guard('api')->user();

        if (!$course){
            $response = [
                'success' => false,
                'data' => [],
                'message' => 'مشکلی پیش آمد لطفا با پشتیبانی سایت ارتباط برقرار کنید.',
            ];
            return response()->json($response , 400);
        }

        if ($course->price === 0){

            course_user::registerUserCourse($user->id , $course , 0);

            $response = [
                'success' => true,
                'data' => ['free' => true],
                'message' => 'دوره آموزشی با موفقیت به پروفایل شما اضافه شد.',
            ];
            return response()->json($response , 200);

        }

        $discountRate = 0;
        $giftCodeId = null;
        if ($request->discount_code){
            $response = Gift::is_discount_ok($request->discount_code , $request->class_id);

            if ($response['success']){
                $discountRate = $response['data']['discountRate'];
                $giftCodeId = $response['data']['discount_id'];
            }
        }

        if ($course->off > 0){
            $price = $course->off - $discountRate;
        }
        else{
            $price = $course->price - $discountRate;
        }

//        $order_id = rand(0,9).date('y').rand(0,9).date('d').rand(0,9).date("s").rand(0,9);
        $class_trans = new ClassTrans;

        $class_trans->user_id = $user->id;
        $class_trans->price = $price;
        $class_trans->kind = $course->kind === "bundle" ? "bundle" : "course_class" ;
        $class_trans->class_register_id = $request->class_id;
        $class_trans->gift_id = $giftCodeId;
        $class_trans->status = 0;
        $class_trans->created_at = time();
        $class_trans->bank = 'pasargad';

        $class_trans->save();

        $pasargad = new Pasargad;

        $params['amount'] = $price*10;
        $params['invoiceNumber'] = $class_trans->id;
        $params['invoiceDate'] = date("Y/m/d H:i:s");


        $response = $pasargad->getToken($params);

        if ($response->IsSuccess) {
            return "https://pep.shaparak.ir/payment.aspx?n=" .$response->Token ;
        }
        else {
            $response = [
                'success' => false,
                'data' => [],
                'message' => 'مشکلی پیش آمد لطفا با پشتیبانی سایت ارتباط برقرار کنید.',
            ];
            return response()->json($response , 400);
        }


//        $apiKey = 'Ikl2i1NDzfaC9-b0P9t8_WP9bQArRhs0';
//        // Your Api Secret :
//        $apiSecret = 'Im7c3t44bQorJOrfDwDKLS4_eMF62_0YdSmbLVUa4YuXsAR7kbRxpgNLE6V8';
//
//        /** @var Jibit $jibit */
//        $jibit = new Jibit($apiKey, $apiSecret);
//
//        // Making payments request
//        // you should save the order details in DB, you need if for verify
//
//        $requestResult = $jibit->paymentRequest($price, $course->id, $user->username, "http://class.honari.devel/callback?site=jibit" );
//
//        if (!empty($requestResult['pspSwitchingUrl'])) {
//
//
//            $class_trans = new ClassTrans;
//
//            $class_trans->user_id = $user->id;
//            $class_trans->price = $price;
//            $class_trans->kind = 'course_class';
//            $class_trans->bank_ref = $requestResult['orderIdentifier'];
//            $class_trans->class_register_id = $request->class_id;
//            $class_trans->gift_id = $giftCodeId;
//            $class_trans->status = 0;
//            $class_trans->created_at = time();
//            $class_trans->bank = 'jibit';
//
//            $class_trans->save();
//
//            return $requestResult['pspSwitchingUrl'];
//        }
//        if (!empty($requestResult['errors'])) {
//            //fail result and show the error
//            $response = [
//                'success' => false,
//                'data' => $requestResult,
//                'message' => 'مشکلی پیش آمد لطفا با پشتیبانی سایت ارتباط برقرار کنید.',
//            ];
//            return response()->json($response , 400);
//
//            //return $requestResult['errors'][0]['code'] . ' ' . $requestResult['errors'][0]['message'];
//        }


    }

    public function callback(Request $request){


        if ($request->site === "pasargad"){
            $pasargad = new Pasargad;

            if (!$request->iN){
                $content = 'پرداخت با مشکل مواجه شد لطفا با پشتیبانی تماس بگیرید.';
                return view('payments.failed' , array('content' => $content ));
            }
            $order_id = $request->iN;
            $TransactionReferenceID = $request->tref;

            $class_trans = ClassTrans::findOrFail($order_id);

            $class_trans->bank_ref = $TransactionReferenceID;
            $class_trans->save();

            if ($request->tref){
                $result = $pasargad->checkTransactionResult(['TransactionReferenceID' => $TransactionReferenceID]) ;

                if ($result->IsSuccess) {
                    if (!$class_trans){
                        $content = 'پرداخت با مشکل مواجه شد لطفا با پشتیبانی تماس بگیرید.';
                        return view('payments.failed' , compact('content' , 'order_id'));
                    }
                    $class_id = $class_trans->class_register_id;
                    $course = Course::find($class_id);
                    if (!$course){
                        $content = 'پرداخت با مشکل مواجه شد لطفا با پشتیبانی تماس بگیرید.';
                        return view('payments.failed' , compact('content' , 'order_id'));
                    }

                    $verResult = $pasargad->verifyPayment(['InvoiceNumber' => $request->iN , 'InvoiceDate' => $request->iD , 'Amount' => $class_trans->price*10 ]) ;

                    if ($verResult->IsSuccess) {



                        ClassTrans::successful_trans($class_trans , $course , $class_trans->price , $class_id , $TransactionReferenceID);

                        $slug = $course->kind === "bundle" ? "/bundles/". $course->urlfa : "/courses/".$course->urlfa;

                        return view('payments.success' , compact('slug'));



                    } else {
                        $content = 'پرداخت شما ناموفق بود.';
                        return view('payments.failed' , array('content' => $content , 'order_id' => $order_id ));
                    }


                } else {
                    $content = 'پرداخت شما ناموفق بود.';
                    return view('payments.failed' , array('content' => $content , 'order_id' => $order_id ));
                }

            }
            else {
                $pasargad->checkTransactionResult(['InvoiceNumber' => $request->iN , 'InvoiceDate' => $request->iD ]) ;
                $content = 'پرداخت شما ناموفق بود.';
                return view('payments.failed' , array('content' => $content , 'order_id' => $order_id ));
            }
        }

//        else if ($request->site === "jibit") {
//
//
//            //get data from query string
//            $refNum = $_GET['refnum'];
//            $amount = $_GET['amount'];
//            $state = $_GET['state'];
//
//            $class_trans = ClassTrans::where('bank_ref' , $refNum)->first();
//            if (!$class_trans){
//                $content = 'پرداخت با مشکل مواجه شد لطفا با پشتیبانی تماس بگیرید.';
//                return view('payments.failed' , compact('content' , 'refNum'));
//            }
//
//            if ($class_trans->price != $amount){
//                $class_trans->status = 2;
//                $class_trans->save();
//                $content = 'پرداخت با مشکل مواجه شد لطفا با پشتیبانی تماس بگیرید.';
//                return view('payments.failed' , compact('content' , 'refNum'));
//            }
//
//            $class_id = $class_trans->class_register_id;
//
//            $course = Course::find($class_id);
//            $slug = $course->urlfa;
//            if (!$course){
//                $content = 'پرداخت با مشکل مواجه شد لطفا با پشتیبانی تماس بگیرید.';
//                return view('payments.failed' , compact('content' , 'refNum'  , 'slug'));
//            }
//
//            if (empty($_GET['refnum'])) {
//                $content = 'مشکلی پیش آمده لطفا با پشتیبانی در تماس باشید.';
//                return view('payments.failed' , compact('content' , 'refNum' , 'slug'));
//            }
//
//
//            if (empty($_GET['amount']) || empty($_GET['state'])) {
//                $content = 'پرداخت شما ناموفق بود.';
//                return view('payments.failed' , compact('content' , 'refNum' , 'slug'));
//            }
//
//            if ($state !== 'SUCCESSFUL') {
//                $content = 'پرداخت شما ناموفق بود.';
//                return view('payments.failed' , compact('content' , 'refNum' , 'slug'));
//            }
//
//            // Your Api Key :
//            $apiKey = 'Ikl2i1NDzfaC9-b0P9t8_WP9bQArRhs0';
//            // Your Api Secret :
//            $apiSecret = 'Im7c3t44bQorJOrfDwDKLS4_eMF62_0YdSmbLVUa4YuXsAR7kbRxpgNLE6V8';
//
//
//            /** @var Jibit $jibit */
//            $jibit = new Jibit($apiKey, $apiSecret);
//
//
//
//            // Making payments verify
//            $requestResult = $jibit->paymentVerify($refNum);
//
//            if (!empty($requestResult['status']) && $requestResult['status'] === 'Successful') {
//                //successful result
//
//                ClassTrans::successful_trans($class_trans , $course , $amount , $class_id);
//
//                return view('payments.success' , compact('slug'));
//
//
//            }
//            //fail result and show the error
//            $content = 'پرداخت شما ناموفق بود.';
//            return view('payments.failed' , compact('content' , 'refNum' , 'slug'));
//
//
//        }


        $content = 'پرداخت با مشکل مواجه شد لطفا با پشتیبانی تماس بگیرید.';
        return view('payments.failed' , compact('content'));



    }



}
