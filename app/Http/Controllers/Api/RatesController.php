<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rates;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ApiResource;

class RatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $data = ApiResource::collection(rates::paginate(10))->response()->getData(true);
       $response = \AppHelper::resp('success', 200 , $data);
       return $response;
       
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            "provider" => "required|string|min:3|unique:rates,provider",
            "is_buying" => "required",
            "is_selling" => "required",
            "selling_rate" => "required|integer|min:1",
            "buying_rate" => "required|integer|min:1",
            "balance" => "required|integer|min:1",
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
         $info = Rates::create($request->all());

         
         if($info)
         {
             $response =  \AppHelper::resp('success',200, ['message' => 'Provider & and Rate created succesfully' ]);

         }else 
         {
            $response = \AppHelper::resp('fail', $code, ['message' =>  'provider and plan not created']);
         }

        }

        return response()->json($response);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

      $data = Rates::find($id);

      if($data)
      {
        $response = \AppHelper::resp('success', 200 , [
            'provider' => $data
        ]);
      }else {
        $response = \AppHelper::resp('fail', 200 , [
            'message' => "provider not found"
        ]); 
      }
        
      return $response;
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
        $validator = Validator::make($request->all(), [
            "provider" => "required|string|min:3|unique:rates,provider,$id,id",
            "is_buying" => "required",
            "is_selling" => "required",
            "selling_rate" => "required|integer|min:1",
            "buying_rate" => "required|integer|min:1",
            "balance" => "required|integer|min:1",
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
            $rate = Rates::find($id);
            $rate->provider = $request->provider;
            $rate->is_buying = $request->is_buying;
            $rate->is_selling = $request->is_selling;
            $rate->buying_rate = $request->buying_rate;
            $rate->selling_rate = $request->selling_rate;
            $rate->balance = $request->balance;
            $data = $rate->save();
            if($data)
            {
                $response = \AppHelper::resp('success', 200, ['message' =>  $rate]);
            }else {


                $response = \AppHelper::resp('success', 200, ['error' =>  "rate & provider not updated"]);
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
      $rate = Rates::find($id);

      if($rate)
      {

          if($rate->delete())
          {
    
            $response = \AppHelper::resp('success', 200, ['rate' =>  "Provider & Rate deleted succesfully"]);
        }else{
            
            $response = \AppHelper::resp('fail', 200, ['rate' =>  "Provider & Rate Not deleted"]);
    
          }

      }else{

        $response = \AppHelper::resp('fail', 200, ['rate' =>  "Provider & Rate Not found"]);
      }


     return $response;
    
    
    }
}
