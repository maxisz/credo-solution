<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\tinyPesa;
use App\Models\payments;
use App\Models\orders;

use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
  



    /**
     * generate new payment link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


     public function initiate(Request $request)
     {
         $tinyPesa = new tinyPesa(env("tinypesa_api_key"));
        // amount , phone , type , type_id
         //
         $validator = Validator::make($request->all(), [
            "type" => "required|string",
            "phone" => "required|integer|min:12",
            "type_id" => "required|integer",
            "amount" => "required|integer|min:0",
           
        ]);
        
        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = [];

            foreach ($errors->messages() as $field => $messages) {
                $errorMessage = '';
                foreach ($messages as $message) {
                    $errorMessage .= $message . ' ';
                }
                $errorMessages[$field] = rtrim($errorMessage);
            }

            $code = 200;
            $response = \AppHelper::resp('fail', $code, ['errors' =>  $errorMessages]);


        } else {
       
        // generate the payment
        $type = $request->type;
        $amount = $request->amount;
        $type_id = $request->type_id;

        $if_r = $type == 'airtime' ? $amount : '0';
        $data = $tinyPesa->initiate($request->phone,$request->amount, $account_id= "$type,$type_id,$if_r");
 
        $my_data = explode(",",base64_decode($data->account_id));
         if($data->success)
         {


        //   save data to db
            $save_data = [
                "amount" => $if_r,
                "tinypesa_account_id" => $data->account_id,
                "tinypesa_request_id" => $data->request_id,
                "tinypesa_payment_amount" => $if_r,
                "tinypesa_result" => "not processed",
            ];
            $save_response =  payments::create($save_data);

            if($save_response)
            {

                
                             $response = \AppHelper::resp("success", 200, [
                                 "account_id" => $data->account_id,
                                 "order_type" => $my_data[0],
                                 "order_amount" => $my_data[2],
                                 "phone_number" => $my_data[3],
                                 "request_id" => $data->request_id,
                             ]);


            }




 
         }else {
 
             $response = \AppHelper::resp("fail", 200, ["message" => "tinypesa failed to generate transaction"]);
 
 
         }





        }


        return $response;

     }

   

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = payments::where("tinypesa_account_id", $id)->first();
        if($data)
        {
        //    $data->order ?  $data->order= $data->order : $data->order = '';

           
        //    $data->order->offer ?  $data->order->offer =  $data->order->offer:  $data->order->offer = '';
        //    $data->order->rate ?  $data->order->rate =  $data->order->rate :  $data->order->rate = '';



           if($data->orders)
           {
            $data->orders = $data->orders;

            if($data->orders->offer)
            {
                $data->orders->offer;
            }
            if($data->orders->rate)
            {
                $data->orders->offer;
            }

            
           }

      $response = \AppHelper::resp('success', 200, $data);


       }else {

        $response = \AppHelper::resp('fail', 200, ["message" => "transaction with account_id ".$id." not found"]);


       }
       return $response;
        
        
    }







    public function callback(request $request)
    {

        $content = $request->getContent();
        $data = json_decode($content, true);

        // Extract the resultCode
        // $resultCode = $data['Body']['stkCallback']['ResultCode'];
        $data = $data['Body']['stkCallback'];
        // get result code , amount , and result description
        $result_code = $data['ResultCode'];
        $result_desc = $data['ResultDesc'];
        $result_account_id = $data['ExternalReference'];
        $result_amount = $data['Amount'];


        // save the neccesary to db 
        $payment = payments::where('tinypesa_account_id', $result_account_id )->first();

        $payment->tinypesa_result_code = $result_code;
        $payment->tinypesa_result_desc = $result_desc;
        $payment->tinypesa_result = "processed";
        $payment->save();
        $my_data =  explode(",",base64_decode($result_account_id));
        if($result_code != 0)
        {
            if($my_data[0] == "airtime")
            {
                
         $save_data = [
                "type" => $my_data[0],
                "rate_id" => $my_data[1],
                "amount" => $my_data[2],
                "payment_id" => $payment->id,
                "is_fullfilled" => "no",
            ];
            }else {
            $save_data = [
                    "type" => $my_data[0],
                    "offer_id" => $my_data[1],
                    "amount" => $my_data[2],
                    "payment_id" => $payment->id,
                    "is_fullfilled" => "no",
                ];

            }
        orders::create($save_data);
           // success handler 
             // $table->id();
    // $table->string("type"); // offer , airtime
    // $table->in teger("amount")->nullable();
    // $table->integer("offer_id")->nullable();
    // $table->integer("rate_id")->nullable();
    // $table->integer("payment_id");
    // $table->string("is_fullfilled");
    // $table->timestamps();

        } 



        // generate new order with the parameters if its payed

       

       

        
       
    }
   
   /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
}
