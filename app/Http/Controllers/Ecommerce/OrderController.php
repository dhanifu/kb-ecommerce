<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
use App\Payment;
use Carbon\Carbon;
use DB;
use PDF;
use App\OrderReturn;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::withCount(['return'])
                        ->where('customer_id', auth()->guard('customer')->user()->id)
                        ->orderBy('created_at', 'DESC')->paginate(10);

        return view('ecommerce.orders.index', compact('orders'));
    }

    public function view($invoice)
    {
        $order = Order::with(['district.city.province', 'details', 'details.product', 'payment'])
                    ->where('invoice', $invoice)->first();

        if ( \Gate::forUser(auth()->guard('customer')->user())->allows('order-view', $order) ) {
            return view('ecommerce.orders.view', compact('order'));
        }

        return redirect(route('customer.orders'))->with(['error'=>'Anda Tidak Diizinkan Untuk Mengakses Order Orang Lain']);
    }

    public function paymentForm()
    {
        return view('ecommerce.payment');
    }

    public function storePayment(Request $request)
    {
        $this->validate($request, [
            'invoice' => 'required|exists:orders,invoice',
            'name' => 'required|string',
            'transfer_to' => 'required|string',
            'transfer_date' => 'required',
            'amount' => 'required|integer',
            'proof' => 'required|image|mimes:jpg,png,jpeg'
        ]);

        DB::beginTransaction();
        try {
            $order = Order::where('invoice', $request->invoice)->first();
            if ($order->subtotal != $request->amount){
                return redirect()->back()->with(['error' => 'Error, Pembayaran Harus Sama Dengan Tagihan']);
            } 

            if ( $order->status == 0 && $request->hasFile('proof') ) {
                $file = $request->file('proof');
                $filename = time(). '.'. $file->getClientOriginalExtension();
                $file->storeAs('public/payment', $filename);

                Payment::create([
                    'order_id' => $order->id,
                    'name' => $request->name,
                    'transfer_to' => $request->transfer_to,
                    'transfer_date' => Carbon::parse($request->transfer_date)->format('Y-m-d'),
                    'amount' => $request->amount,
                    'proof' => $filename,
                    'status' => false
                ]);

                $order->update(['status'=>1]);

                DB::commit();

                return redirect()->back()->with(['success'=>'Pesanan Dikonfirmasi']);
            }
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with(['error'=>$e->getMessage()]);
        }
    }

    public function pdf($invoice)
    {
        $order = Order::with(['district.city.province', 'details', 'details.product', 'payment'])
                    ->where('invoice', $invoice)->first();
        if ( !\Gate::forUser(auth()->guard('customer')->user())->allows('order-view', $order) ) {
            return redirect(route('customer.view_order', $order->invoice));
        }

        $pdf = PDF::loadView('ecommerce.orders.pdf', compact('order'));

        return $pdf->stream();
    }

    public function acceptOrder(Request $request)
    {
        $order = Order::find($request->order_id);

        if ( !\Gate::forUser(auth()->guard('customer')->user())->allows('order-view', $order) ) {
            return redirect()->back()->with(['error'=>'Bukan Pesanan Kamu']);
        }
        $order->update(['status'=>4]);

        return redirect()->back()->with(['success'=>'Pesanan Dikonfirmasi']);
    }

    public function returnForm($invoice)
    {
        $order = Order::where('invoice', $invoice)->first();

        return view('ecommerce.orders.return', compact('order'));
    }

    public function processReturn(Request $request, $id)
    {
        $this->validate($request, [
            'reason' => 'required|string',
            'refund_transfer' => 'required|string',
            'photo' => 'required|image|mimes:jpg,png,jpeg'
        ]);

        $return = OrderReturn::where('order_id', $id)->first();

        if ($return) redirect()->bakc()->with(['error'=>'Permintaan Refund Dalam Proses']);

        if ( $request->hasFile('photo') ) {
            $file = $request->file('photo');
            $filename = time(). Str::random(5). '.'. $file->getClientOriginalExtension();
            $file->storeAs('public/return', $filename);

            OrderReturn::create([
                'order_id' => $id,
                'photo' => $filename,
                'reason' => $request->reason,
                'refund_transfer' => $request->refund_transfer,
                'status' => 0
            ]);

            return redirect()->back()->with(['success'=>'Permintaan Refund Dikirim']);
        }
    }
    
}
