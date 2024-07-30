<?php

namespace App\Http\Controllers\api\V1;

use App\Models\Bill;
use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Services\FatoorahServices;
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
        }, 'bill', 'products'=>function ($query) {
            $query->select('products.id', 'products.name', 'products.price','quantity');
        }]);

        if (Auth::guard('admin')->check()) {
            $orders = $orders->get();
        } else {
            $orders = $orders->where('user_id', auth()->user()->id)->get();
        }

        return $this->success($orders);
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
        }
        $bill = new Bill();

        $bill['total'] = $total;
        $order->bill()->save($bill);
        return $this->success($order, 'order created successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
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
        if($order->status!=='pending'){
            return $this->failed('The order has been already processed cannot cancel',403);
        }
        foreach ($order->products as $product){
            $product->increment('stock', $product->pivot->quantity);
        }
//        $invoiceId=$order->bill->InvoiceId;
//        $total=$order->bill->total;

//        $data = [
//            'Key' => 4221222,
//            'KeyType' => 'invoiceId',
//            'RefundChargeOnCustomer' => false,
//            'Amount' => 3476,
//        ];
//        return $this->fatoorahServices->makeRefund($data);
        $order->delete();
        return $this->success($order, 'order deleted successfully');
    }
}
