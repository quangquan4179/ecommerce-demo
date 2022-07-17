<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection ;
class ProductController extends Controller
{
    //

    public function createProduct(Request $request){
        $validate = Validator::make($request->all(),[
            'product_name'=>'required',
            'brand'=>'required',
            'price'=>'required',
            'address'=>'required',
            'amount'=>'required',
            'catalog'=>'required'

        ]);

        if($validate->failed()){
            return response()->json([
                'status'=>false,
                'message'=>'validator error',
                'error'=> $validate->errors()
            ],401);
        }
        $file= $request->file('image');

     


        $filename=$file->hashName();
        Storage::disk('public')->put($filename, file_get_contents($file));
        $listImg=[];
        if($request->hasFile(('listImages'))){
            foreach($request->allFiles('listImages') as $f){
                $fname=$f->hashName();
                Storage::disk('public')->put($filename, file_get_contents($f));
            
                $url = Storage::url($fname);

                array_push($listImg, $url);
            }
        }
        $url = Storage::url($filename);

        $catalog = Catalog::where(
            "name",$request->catalog)->first();
      

       
        // $comments=[];
        // foreach($listImg as $comment){

        // }
       
      
        $images =collect($listImg)->map(function($image){
            return ["image"=>$image];
        });

            $product =Product::create([
            'product_name'=>$request->product_name,
            'brand'=>$request->brand,
            'price'=>$request->price,
            'address'=>$request->address,
            'image'=>$url,
            'amount'=>$request->amount,
            'catalog_id'=>$catalog->id
        ])->images()->createMany($images);


        return response()->json([
            "status" => true,
            "message"=>'Product Created Successfully',
            "product"=> $product
        ],200);
    }

    public function getAllProducts(){
         $products =Product::all();
         foreach($products as $product){
            $img = $product->images;
            $catalog = $product->catalog;
    }

         return response()->json([
            "status" => true,
            "products"=> $products
        ],200);
    
    }
    public function getProductsById(Request $request){
        $product= Product::where("id",$request->id)->first();
       
        if($product){
            $images =$product->images;
            $catalog =$product->catalog;
    
            return response()->json([
                "status" => true,
                "product"=> $product,
        
            ],200);

        }
        return response()->json([
            "status" => false,
            "message"=>" Not found product"
        ],200);
    }

    public function deleteProduct(Request $req){
        $product= Product::where("id",$req->id)->first();
       if($product){
        $product->images()->delete();
        $product->delete();
        return response()->json([
            'status' => true,
            'message' => "Product Deleted successfully!",
        ], 200);
       }

       return response()->json([
        'status' => false,
        'message' => "Product not exist !",
    ], 200);

    }
  
}
