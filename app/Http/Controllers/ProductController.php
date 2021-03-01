<?php

namespace App\Http\Controllers;

use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response['products'] = Product::all();

        return response()->json($response);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request;
        $this->validate($request, [
           'title' => 'required',
           'description' => 'required',
           'price' => 'required',
           'image' => 'mimes:jpeg,bmp,png,jpg',
        ]);

        try {
            //return $request;
            $image = $request->file('image');
            $current_date = Carbon::now()->toDateString();
            $destinationPath = public_path().'/store_documents/';

            $data = $request->only('title', 'description', 'price');

            if ($request->id){
                $product = Product::where('id', $request->id)->first();
                if (isset($image)){
                    $image_name = $current_date.'_'.uniqid().'.'.$image->getClientOriginalExtension();

                    if (file_exists($destinationPath.'/'.$product->image))
                    {
                        unlink($destinationPath.'/'.$product->image);
                    }

                    $image->move($destinationPath, $image_name);
                }else{
                    $image_name = $product->image;
                }

                $data['image'] = $image_name;
                $response['product'] = $product->update($data);
                $response['status'] = 1;

            }else{

                if (isset($image)){
                    $image_name = $current_date.'_'.uniqid().'.'.$image->getClientOriginalExtension();

                    $image->move($destinationPath, $image_name);
                }
                $data['image'] = $image_name;

                $response['product'] = Product::create($data);
                $response['status'] = 1;
            }

            $response['message'] = 'Data saved successfully!!';

        }catch (\Exception $e){
            $response['message'] = response()->json($e);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $response['formData'] = Product::findOrFail($id);
            $response['status'] = 1;

        }catch (\Exception $e){

            $response['message'] = response()->json($e);
        }

        return response()->json($response);
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destinationPath = public_path().'/store_documents/';

        try{
            $data = Product::findOrFail($id);
            if (file_exists($destinationPath.'/'.$data->image))
            {
                unlink($destinationPath.'/'.$data->image);
            }
            $data->delete();

            $response['message'] = 'Data deleted successfully!!';
            $response['status'] = 1;

        }catch (\Exception $e){
            $response['message'] = response()->json($e);
        }

        return response()->json($response);
    }
}
