<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\offers;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ApiResource;

class OffersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $data = Offers::all();
        $data = ApiResource::collection(offers::with('category')->paginate(10))->response()->getData(true);
        $response = \AppHelper::resp('success', 200, ['offers' => $data]);
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
            "name" => "required|string|min:3",
            "price" => "required|integer|min:1",
            "category_id" => "required|integer",

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
            $response = \AppHelper::resp('fail', $code, ['errors' => $errorMessages]);


        } else {
            $info = Offers::create($request->all());


            if ($info) {
                $response = \AppHelper::resp('success', 200, ['message' => 'Offer created succesfully']);

            } else {
                $response = \AppHelper::resp('fail', 200, ['message' => 'Offer not created']);
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
        $data = Offers::find($id);
        $data->category = $data->category;

        if ($data) {
            $response = \AppHelper::resp('success', 200, [
                'offer' => $data,

            ]);
        } else {
            $response = \AppHelper::resp('fail', 200, [
                'message' => "offer not found"
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
            "name" => "required|string|min:3",
            "price" => "required|integer|min:1",
            "category_id" => "required|integer",
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
            $response = \AppHelper::resp('fail', $code, ['errors' => $errorMessages]);


        } else {
            $offer = Offers::find($id);
            if ($offer) {

                $offer->name = $request->name;
                $offer->price = $request->price;
                $offer->category_id = $request->category_id;



                $data = $offer->save();
                if ($data) {
                    $response = \AppHelper::resp('success', 200, ['message' => $offer]);
                } else {

                    $response = \AppHelper::resp('success', 200, ['error' => "offer not updated"]);
                }




            } else {
                $response = \AppHelper::resp('fail', 200, ['error' => "offer not found"]);

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
        $offer = offers::find($id);

        if ($offer) {

            if ($offer->delete()) {

                $response = \AppHelper::resp('success', 200, ['rate' => "offer deleted succesfully"]);
            } else {

                $response = \AppHelper::resp('fail', 200, ['rate' => "Offer Not deleted"]);

            }

        } else {

            $response = \AppHelper::resp('fail', 200, ['rate' => "Offer Not found"]);
        }


        return $response;
    }
}