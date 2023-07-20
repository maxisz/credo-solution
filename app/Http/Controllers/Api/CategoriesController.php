<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use Illuminate\Support\Facades\Validator;
class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Categories::all();

       $response = \AppHelper::resp('success', 200 , ['categories' => $data]);
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
            "title" => "required|string|min:3",
            "slug" => "required|string|min:3|unique:categories,slug",
            "provider_id" => "required|integer",
            "description" => "required|string|min:3",
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
         $info = Categories::create($request->all());

         
         if($info)
         {
             $response =  \AppHelper::resp('success',200, ['message' => 'Category created succesfully' ]);

         }else 
         {
            $response = \AppHelper::resp('fail', $code, ['message' =>  'Category not created']);
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
        $data = categories::find($id);
        $data->offers = $data->offers;

        if($data)
        {
          $response = \AppHelper::resp('success', 200 , [
              'category' => $data
          ]);
        }else {
          $response = \AppHelper::resp('fail', 200 , [
              'message' => "category not found"
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
            "slug" => "required|string|min:3|unique:categories,slug,".$id.",id",
            "title" => "required|string|min:3",
            "provider_id" => "required|integer",
            "description" => "required|string|min:3",
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
            $category = Categories::find($id);
            if($category)
            {

                $category->title = $request->title;
                $category->slug = $request->slug;
                $category->provider_id = $request->provider_id;
                $category->description = $request->description;
    
               
                $data = $category->save();
                if($data)
                {
                    $response = \AppHelper::resp('success', 200, ['message' =>  $category]);
                }else {
                    
                    $response = \AppHelper::resp('success', 200, ['error' =>  "category not updated"]);
                }




            } else {
                $response = \AppHelper::resp('fail', 200, ['error' =>  "category not found"]);

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
        $category = category::find($id);

      if($category)
      {

          if($category->delete())
          {
    
            $response = \AppHelper::resp('success', 200, ['message' =>  "category deleted succesfully"]);
        }else{
            
            $response = \AppHelper::resp('fail', 200, ['message' =>  "category Not deleted"]);
    
          }

      }else{

        $response = \AppHelper::resp('fail', 200, ['message' =>  "category Not found"]);
      }


     return $response;
    
    }
}
