<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Requests\PaymentRequest;
use App\Models\Bill;
use App\Models\Order;
use App\Models\Subscription;
use App\Services\FatoorahServices;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Mgcodeur\CurrencyConverter\Facades\CurrencyConverter;

class FatoorahController extends Controller
{

    private $fatoorahServices;
    private $notificationService;

    public function __construct(FatoorahServices $fatoorahServices, NotificationService $notificationService)
    {
        $this->fatoorahServices = $fatoorahServices;
        $this->notificationService = $notificationService;
    }

    public function payOrder(PaymentRequest $request)
    {
        $payment = $request->validated();
        if (isset($payment['order_id'])) {
            $order = Order::with('bill')->where('id', $payment['order_id'])->first();
            if (!$order) {
                return $this->failed('No order found');
            } else {
                $customer = $order->user;
                $totalInvoiceItem = $order->bill->total;
                $data = [
                    'CustomerName' => $customer->name,
                    'CustomerEmail' => $customer->email,
                    'InvoiceValue' => $totalInvoiceItem,//$order['total']
                    'CustomerReference' => $order->id,//$order_id
                    'DisplayCurrencyIso' => 'USD',//$order['currency'],
                    'NotificationOption' => 'EML',
                    'CallBackUrl' => env('success_url'),
                    'ErrorUrl' => env('error_url'),
                    'Language' => 'en',
                    'MobileCountryCode' => '+963',
                ];
                $response = $this->fatoorahServices->sendPayment($data);

                if ($response['IsSuccess']) {
                    $InvoiceId = $response['Data']['InvoiceId'];
                    $order->bill->InvoiceId = $InvoiceId;
                    $order->bill->save();
                }
                return $response;
            }
        }
        if (isset($payment['subscription_id'])) {
            $subscribe = Subscription::with('bill')->where('id', $payment['subscription_id'])->first();
            if (!$subscribe) {
                return $this->failed('No Subscription found');
            } else {
                $customer = $subscribe->user;
                $totalInvoiceItem = $subscribe->bill->total;
                $data = [
                    'CustomerName' => $customer->name,
                    'CustomerEmail' => $customer->email,
                    'InvoiceValue' => $totalInvoiceItem,
                    'CustomerReference' => $subscribe->id,
                    'DisplayCurrencyIso' => 'USD',
                    'NotificationOption' => 'EML',
                    'CallBackUrl' => env('success_url'),
                    'ErrorUrl' => env('error_url'),
                    'Language' => 'en',
                    'MobileCountryCode' => '+963',
                ];
                $response = $this->fatoorahServices->sendPayment($data);

                if ($response['IsSuccess']) {
                    $InvoiceId = $response['Data']['InvoiceId'];
                    $subscribe->bill->InvoiceId = $InvoiceId;
                    $subscribe->bill->save();
                }
                return $response;
            }
        }
        //check the build in myfatoorahController
        return response([], 400);
    }


    public function callBack(Request $request)
    {
        // save the transaction to DB,Order status or Subscription
        $data = [
            'Key' => $request->paymentId,
            'KeyType' => 'paymentId',
        ];
        $response = $this->fatoorahServices->getPaymentStatus($data);

        if (!isset($response['Data']['InvoiceId']))
            return response()->json(["error" => 'error', 'status' => false], 404);

        if ($response['IsSuccess'] && $response['Data']['InvoiceStatus'] == "Paid") {
            $InvoiceId = $response['Data']['InvoiceId'];

            $yourOrder = Bill::where('InvoiceId', $InvoiceId)->first();

            if ($yourOrder) {
                //$currencies = CurrencyConverter::convert($yourOrder->total)->from('EUR')->to('KWD')->get();
                if ($yourOrder->billable_type === "App\\Models\\Order") {
                    $yourOrder->paid = true;
                    $yourOrder->save();
                    $yourOrder->billable->status = 'preparing';
                    $yourOrder->billable->order_date=now();
                    $yourOrder->billable->save();

                    $this->notificationService->SendTrainingNotification(
                        $yourOrder->billable->user->fcm_token,"Paid Successfully","TrainingHacks");
                    return redirect('https://tan-dionne-10.tiiny.site/');
                } elseif ($yourOrder->billable_type === "App\Models\Subscription") {
                    $yourOrder->paid = true;
                    $yourOrder->save();
                    $this->notificationService->SendTrainingNotification(
                        $yourOrder->billable->user->fcm_token,"Paid Successfully","TrainingHacks");
                    $yourOrder->billable->status = true;
                    $yourOrder->billable->save();
                    return redirect('https://tan-dionne-10.tiiny.site/');

                } else {
                    return response()->json(['status' => false]);
                }
            }
            if (!$yourOrder) {
                return $this->failed('No order found with this invoiceId');
            }
        } else {
            $InvoiceId = $response['Data']['InvoiceId'];

            $yourOrder = Bill::where('InvoiceId', $InvoiceId)->first();

            if ($yourOrder) {
                //$currencies = CurrencyConverter::convert($yourOrder->total)->from('EUR')->to('KWD')->get();
                if ($yourOrder->billable_type === "App\\Models\\Order") {

                    $yourOrder->billable->delete();
                    $yourOrder->billable->save();
                } elseif ($yourOrder->billable_type === "App\Models\Subscription") {
                    $yourOrder->billable->delete();
                    $yourOrder->billable->save();

                } else {
                    return response()->json(['status' => false]);
                }
            }
            if (!$yourOrder) {
                return $this->failed('No order found with this invoiceId');
            }
        }
        return redirect('https://green-lorna-95.tiiny.site/');

    }

    public function makePaymentRefunded(Request $request)
    {
        $data = [
            'Key' => 4217201,
            'KeyType' => 'invoiceId',
            'RefundChargeOnCustomer' => false,
            'Amount' => 10.0,
        ];
        return $this->fatoorahServices->makeRefund($data);
    }

    public function refundError(Request $request)
    {
        $data = [
            'Key' => $request->refundId,
            'KeyType' => 'refundId',
        ];
        return $this->fatoorahServices->getRefundStatus($data);

    }
}
