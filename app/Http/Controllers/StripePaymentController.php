<?php

namespace App\Http\Controllers;


use App\Order;
use App\Plan;
use App\Utility;
use Illuminate\Http\Request;
use Session;
use Stripe;

class StripePaymentController extends Controller
{
    public function index()
    {
        $objUser = \Auth::user();
        if($objUser->type=='super admin'){
            $orders = Order::select(['orders.*','users.name as user_name'])->join('users','orders.user_id','=','users.id')->orderBy('orders.created_at','DESC')->get();
        }else{
            $orders = Order::select(['orders.*','users.name as user_name'])->join('users','orders.user_id','=','users.id')->orderBy('orders.created_at','DESC')->where('users.id','=',$objUser->id)->get();
        }
        return view('order.index',compact('orders'));
    }


    public function stripe($code)
    {
        $plan_id = \Illuminate\Support\Facades\Crypt::decrypt($code);
        $plan = Plan::find($plan_id);
        if($plan) {
            return view('stripe',compact('plan'));
        }else{
            return redirect()->back()->with('error',__('Plan is deleted.'));
        }
    }


    public function stripePost(Request $request)
    {

        $objUser = \Auth::user();
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan);
        $plan = Plan::find($planID);
        if($plan) {
            try{
                $orderID = strtoupper(str_replace('.','',uniqid('', true)));
                Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                $data = Stripe\Charge::create([
                                                  "amount" => 100 * $plan->price,
                                                  "currency" => "usd",
                                                  "source" => $request->stripeToken,
                                                  "description" =>    " Plan - ".$plan->name,
                                                  "metadata"=>["order_id" => $orderID]
                                              ]);
                if($data['amount_refunded'] == 0 && empty($data['failure_code']) && $data['paid'] == 1 && $data['captured'] == 1) {

                    Order::create([
                                      'order_id'=>$orderID,
                                      'name' => $request->name,
                                      'card_number' => $data['payment_method_details']['card']['last4'],
                                      'card_exp_month' => $data['payment_method_details']['card']['exp_month'],
                                      'card_exp_year' => $data['payment_method_details']['card']['exp_year'],
                                      'plan_name' => $plan->name,
                                      'plan_id' => $plan->id,
                                      'price' => $plan->price,
                                      'price_currency' => $data['currency'],
                                      'txn_id' => $data['balance_transaction'],
                                      'payment_status' => $data['status'],
                                      'receipt' => $data['receipt_url'],
                                      'user_id' => $objUser->id,
                                  ]);

                    if($data['status'] == 'succeeded') {
                        $assignPlan = $objUser->assignPlan($plan->id);
                        if($assignPlan['is_success']){
                            return redirect()->route('plans.index')->with('success', __('Plan successfully activated.'));
                        }else{
                            return redirect()->route('plans.index')->with('error',__($assignPlan['error']));
                        }
                    }else{
                        return redirect()->route('plans.index')->with('error',__('Your Payment has failed.'));
                    }
                }else{
                    return redirect()->route('plans.index')->with('error',__('Transaction has been failed.'));
                }
            }catch(\Exception $e){
                return redirect()->route('plans.index')->with('error',__($e->getMessage()));
            }
        }else{
            return redirect()->route('plans.index')->with('error',__('Plan is deleted.'));
        }
    }
}
