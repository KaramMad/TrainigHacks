<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;


class FatoorahServices{
    private $base_url;
    private $headers,$request_client;
    public  function __construct(Client $request_client){
        $this->request_client=$request_client;
        $this->base_url=env('FATOORAH_BASE_URL');
        $this->headers=[
            'Content-Type'=>'application/json',
            'Authorization'=>'Bearer '.env('FATOORAH_TOKEN')
        ];
    }

    private function buildRequest($uri,$method,$data=[])
    {
        $request=new Request($method,$this->base_url.$uri,$this->headers);
        if(!$data)
            return false;
            $response=$this->request_client->send($request,[
                'json'=>$data
            ]);

        if($response->getStatusCode() !=200){
            return false;
        }
        $response=json_decode($response->getBody(),true);
        return $response;
    }
    public function sendPayment($data){
        $response  = $this->buildRequest('v2/SendPayment','POST', $data);
        return $response;
    }
    public function makeRefund($data)
    {
        $response  = $this->buildRequest('v2/MakeRefund','POST', $data);
        return $response;
    }
    public function transactionCallBack($request){
            $data=$request->all();
    }

    public function getPaymentStatus(array $data)
    {
        $response  = $this->buildRequest('v2/getPaymentStatus','POST', $data);
        return $response;
    }

    public function getRefundStatus(array $data)
    {
        $response  = $this->buildRequest('v2/GetRefundStatus','POST', $data);

    }
}















?>
