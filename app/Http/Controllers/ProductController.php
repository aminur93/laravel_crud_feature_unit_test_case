<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();

        return $products;
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);

        if($request->method() == 'POST')
        {
            DB::beginTransaction();

            try{

                //create product

                $product = new Product();

                $product->name = $request->name;
                $product->title = $request->title;
                $product->description = $request->description;
                $product->code = $request->code;
                $product->price = $request->price;

                $product->save();

                DB::commit();

                return response()->json([
                    'data' => $product,
                    'message' => 'Product store successful'
                ],Response::HTTP_CREATED);

            }catch(ValidationException $e){
                DB::rollBack();

                return response()->json([
                    'error' => $e->errors(),
                ], Response::HTTP_BAD_REQUEST);
            }
            catch(Exception $e){
                DB::rollBack();

                $error = $e->getMessage();

                return response()->json([
                    'error' => $error
                ],Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        // If the request method is not PUT, you may want to handle it accordingly.
        return response()->json([
            'error' => 'Invalid request method',
        ], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return $product;
    }

    public function update(Request $request, $id): JsonResponse
    {
        if($request->method() == 'PUT')
        {
            DB::beginTransaction();

            try{

                //create product

                $product = Product::findorFail($id);

                $product->name = $request->name;
                $product->title = $request->title;
                $product->description = $request->description;
                $product->code = $request->code;
                $product->price = $request->price;

                $product->save();

                DB::commit();

                return response()->json([
                    'data' => $product,
                    'message' => 'Product update successful'
                ],Response::HTTP_OK);

            }catch(ValidationException $e){
                DB::rollBack();

                return response()->json([
                    'error' => $e->errors(),
                ], Response::HTTP_BAD_REQUEST);
            }catch(Exception $e){
                DB::rollBack();

                $error = $e->getMessage();

                return response()->json([
                    'error' => $error
                ],Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        // If the request method is not PUT, you may want to handle it accordingly.
        return response()->json([
            'error' => 'Invalid request method',
        ], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function destroy($id): JsonResponse
    {
        $product = Product::findorFail($id);
        $product->delete();

        return response()->json([
            'message' => 'Product destroy successful'
        ],Response::HTTP_OK);

    }
}
