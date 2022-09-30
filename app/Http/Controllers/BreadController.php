<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

use App\Models\PopularSearchedBeerds;
use Illuminate\Http\Request;

class BreadController extends Controller
{
    // curl --location --request GET 'https://api.thecatapi.com/v1/breeds?limit=10&page=0' \

    public $baseUr="https://api.thecatapi.com/v1/";

    public function index(){
        $response = Http::withHeaders([
            'x-api-key'=>env('api_auth'),
        ])->get($this->baseUr.'breeds');
        if($response->status()==200){
            $output['result']['success'] =  true;
            $output['result']['data'] = $response->collect();
            $output['result']['message'] = "Category Returened Succefully";
        }
        return $output;
    }



    public function updateRank($beerd_id){
        $beerd =PopularSearchedBeerds::where('beerd_id' , $beerd_id)->first();

        if($beerd){
            $beerd =PopularSearchedBeerds::where('beerd_id' , $beerd_id)->update([
                'search_count'=>$beerd['search_count']+1,
            ]);
        }else{
            $beerd =PopularSearchedBeerds::create([
                'beerd_id'=>$beerd_id,
                'search_count'=>1,
            ]);
        }
    }

    public function seacrh(Request $request){
        $requestBody = $request['userData'];

        if(isset($requestBody['beerd_id'])){

            $this->updateRank($requestBody['beerd_id']);

            $response = Http::withHeaders([
                'breed_id'=>$requestBody['beerd_id'],
                'x-api-key'=>env('api_auth')
            ])->get($this->baseUr. 'images/search?breed_id='.$requestBody['beerd_id']);
            if($response->status()==200){
                $output['result']['success'] =  true;
                $output['result']['data'] = $response->collect();
                $output['result']['message'] = "Search Returned Succefully";
            }else{
                $output['result']['success'] =  false;
                $output['result']['data'] = $response;
                $output['result']['message'] = "Server Error";
            }   
        }else{
            $output['success'] = false;
            $output['success'] = "Missing Required Data";
           
            
        }
        return $output;
    }




    public function getMoreImages(Request $request){
        
        $imagesArray = [];
        $requestBody = $request['userData'];
        if(isset($requestBody['beerd_id'])){

            for($i=0;$i<8;$i++){
                $response = Http::withHeaders([
                    'breeds'=>$requestBody['beerd_id'],
                    'limit'=>10,
                    'x-api-key'=>env('api_auth')
                ])->get($this->baseUr. 'images/search');
                if($response->status()==200){

                    array_push($imagesArray , $response->collect());
                   
                }
            }

            if(sizeof($imagesArray)>0){
                $output['result']['success'] =  true;
                $output['result']['data'] = $imagesArray;
                $output['result']['message'] = "Images Returned Succefully";
            }
          
        }else{
            $output['success'] = false;
            $output['success'] = "Missing Required Data"; 
        }
        return $output;
    }


    public function getPopular(){
        $Popualr = PopularSearchedBeerds::select('*')->orderBy('search_count' , 'DESC')->take(10)->get();

        $Populared_List = [];
        $count = 10;

        if(count($Popualr)>=10){
            $count = 10;
        }else{
            $count = count($Popualr);
        }
        for($i=0;$i<$count;$i++){
            
            $response = Http::withHeaders([
                'breed_id'=>$Popualr[$i]['beerd_id'],
                'x-api-key'=>env('api_auth')
            ])->get($this->baseUr. 'images/search?breed_id='.$Popualr[$i]['beerd_id']); 
            
    
            if($response->status()==200 && $response->ok()){
                array_push($Populared_List , $response->collect()); 
            }
        }
        if(sizeof($Populared_List)>0){
            $output['result']['success'] =  true;
            $output['result']['data']['list'] = $Populared_List;
            $output['result']['message'] = "Populared Beerds Returned Succefully";
        }
        return $output;
    }
}
