<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Requests\filterByStatusRequest;
use App\Models\Bill;
use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Services\FatoorahServices;
use App\Providers\AppServiceProvider as AppSP;
use Mgcodeur\CurrencyConverter\Facades\CurrencyConverter;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $fatoorahServices;

    public function __construct(FatoorahServices $fatoorahServices)
    {
        $this->fatoorahServices = $fatoorahServices;
    }

    public function index()
    {
        $orders = Order::with(['user' => function ($query) {
            $query->select('id', 'name', 'email');
        }, 'bill', 'products' => function ($query) {
            $query->select('products.id', 'products.name', 'products.price', 'quantity');
        }]);

        if (Auth::guard('admin')->check()) {
            $orders = $orders->get();
            $orders = $orders->map(function ($order) {
                $order['product_count'] = $order->products->count();

                return $order;
            });
        } else {
            $orders = $orders->where('user_id', auth()->user()->id)->get();
            $orders = $orders->map(function ($order) {
                $order['product_count'] = $order->products->count();
                return $order;
            });
        }

        return $this->success($orders);
    }

    public function filterByStatus(filterByStatusRequest $request)
    {
        $data = $request->validated();
        $data = Order::with(['user' => function ($query) {
            $query->select('id', 'name', 'email');
        }, 'bill', 'products' => function ($query) {
            $query->select('products.id', 'products.name', 'products.price', 'quantity');
        }])->latest()->filter(request(['status', 'paid']))->get();
        return $this->success($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $order = $request->validated();
        $user = Auth::user();
        $order['user_id'] = $user['id'];
        $order['order_date'] = now();
        $order = Order::create($order);
        $products = collect($request->products)->map(function ($product) {
            return [
                'created_at' => now(),
                'updated_at' => now(),
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'price' => Product::find($product['product_id'])->price,
            ];
        });
        $order->products()->attach($products);
        $total = 0;
        foreach ($order->products as $product) {
            $product->decrement('stock', $product->pivot->quantity);
            $total += $product->pivot->quantity * $product->price;
            $order['product_count'] = $order->products->count();
            $order['total'] = $total;
        }
        $bill = new Bill();

        $bill['total'] = $total;
        $order->bill()->save($bill);
        return $this->success($order, 'order created successfully');

    }


    public function sent(Order $order)
    {
        if ($order->status != 'preparing') {
            return AppSP::apiResponse('order is not pending', null, 'data', false, 403);
        }
        $order->status = 'sent';
        $order->save();
    }


    public function receive(Order $order)
    {
        if ($order->status != 'sent') {
            return AppSP::apiResponse('order is not sent', null, 'data', false, 403);
        }
        $order->status = 'received';
        $order->save();
        foreach($order->products as $item){
            $item->increment('sales_count',$item->pivot->quantity);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {

        if ($order->status == 'preparing') {
            $invoiceId = $order->bill->InvoiceId;
            $total = $order->bill->total;
            $temp = $total * 0.10;


            $order->bill->total = $temp;
            $order->bill->save();

            $data = [
                'Key' => $invoiceId,
                'KeyType' => 'invoiceId',
                'RefundChargeOnCustomer' => false,
                'ServiceChargeOnCustomer' => false,
                'Amount' => $total - $temp,

            ];

            $response = $this->fatoorahServices->makeRefund($data);
            if ($response['IsSuccess']) {
                $RefundId = $response['Data']['RefundId'];
                $order->bill->RefundId = $RefundId;
                $order->bill->save();
            }
            foreach ($order->products as $product) {
                $product->increment('stock', $product->pivot->quantity);
            }
            $order->delete();
            return $this->success($order, 'order deleted successfully');
        } else if ($order->status !== 'pending')
            return $this->failed('The order has been already processed cannot cancel', 403);
        else {
            foreach ($order->products as $product) {
                $product->increment('stock', $product->pivot->quantity);
            }

            $order->delete();
            return $this->success($order, 'order deleted successfully');
        }


    }
}
