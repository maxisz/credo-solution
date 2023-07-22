<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\orders;
 use App\Http\Resources\OrderResource;
 use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
            $orders =  OrderResource::collection(orders::with(['offer','rate'])->paginate(10))->response()->getData(true);
        
            $response = \AppHelper::resp('success',200, $orders);

        return $response;
       
    }

    public function pendingOrders()
    {

      $orders =  OrderResource::collection(orders::where('is_fullfilled','no')->with(['offer','rate'])->paginate(10))->response()->getData(true);
        
        $response = \AppHelper::resp('success',200, $orders);

    return $response;
    }
    public function fullfilledOrders()
    {

      $orders =  OrderResource::collection(orders::where('is_fullfilled','yes')->with(['offer','rate'])->paginate(10))->response()->getData(true);
        
        $response = \AppHelper::resp('success',200, $orders);

    return $response;
    }

    public function airtimeOrders()
    {

      $orders =  OrderResource::collection(orders::where('type','airtime')->with(['offer','rate'])->paginate(10))->response()->getData(true);
        
        $response = \AppHelper::resp('success',200, $orders);

    return $response;
    }
    public function offerOrders()
    {

      $orders =  OrderResource::collection(orders::where('type','offer')->with(['offer','rate'])->paginate(10))->response()->getData(true);
        $response = \AppHelper::resp('success',200, $orders);
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
        $order = orders::find($id);


        return \AppHelper::resp('success',200,['order' => $order]);

    }
 
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         //
         $validator = Validator::make($request->all(), [
            "is_fullfilled" => "required|string"
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
            $response = \AppHelper::resp('fail', 200, ['errors' =>  $errorMessages]);


        } else {
        $order = orders::find($id);

        $order->is_fullfilled = $request->is_fullfilled;

        if($order->save())
        {
            $response = \AppHelper::resp('success', 200, ['message' =>  "order fullfilled"]);

        }else {


            
        }

        }


    return $response;
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
