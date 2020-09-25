<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\OrderMail;
use App\Order;
use Mail;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer.district.city.province'])
                        ->orderBy('created_at', 'DESC');

        if ( request()->q != '' ) {
            $orders = $orders->where(function($q){
                $r = request()->q;
                $q->where('customer_name', 'LIKE','%'.$r.'%')
                ->orWhere('invoice', 'LIKE','%'.$r.'%')
                ->orWhere('customer_address', 'LIKE','%'.$r.'%');
            });
        }

        if ( request()->status != '' ) {
            $orders = $orders->where('status', request()->status);
        }

        $orders = $orders->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function view($invoice)
    {
        $order = Order::with(['customer.district.city.province', 'payment', 'details.product'])
                    ->where('invoice', $invoice)->first();
        
        return view('orders.view', compact('order'));
    }

    public function acceptPayment($invoice)
    {
        $order = Order::with(['payment'])->where('invoice', $invoice)->first();
        $order->payment()->update(['status'=>1]);
        $order->update(['status'=>2]);

        return redirect(route('orders.view', $order->invoice));
    }

    public function shippingOrder(Request $request)
    {
        $order = Order::with(['customer'])->find($request->order_id);
        $order->update(['tracking_number'=>$request->tracking_number, 'status'=>3]);

        Mail::to($order->customer->email)->send(new OrderMail($order));

        return redirect()->back();
    }

    public function destroy(Order $order)
    {
        $order->details()->delete();
        $order->payment()->delete();
        $order->delete();

        return redirect(route('orders.index'));
    }
}
