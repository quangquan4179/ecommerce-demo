<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    //

    public function getAllProducts(){
        $catalogs = Catalog::all();
        foreach($catalogs as $catalog){
            $products = $catalog->products;
            // $img = $catalog->products->images;
        }
        return response()->json([
            "status" => true,
            "catalogs"=>$catalogs
        ],200);
    }
 

    public function getProductByCatalogName(Request $req){

        $catalogs = Catalog::where('name',$req->name);
        if($catalogs){
            return response()->json([
                "status" => true,
                "catalogs"=>$catalogs
            ],200);
        }


        return response()->json([
            "status"=>false,
            "message"=>$req->name
        ],200);

    }

    
}
