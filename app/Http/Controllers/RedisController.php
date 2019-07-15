<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RedisController extends Controller
{
    //

    public function newsearch(Request $request)
    {
        if($request->newsearch) {
            $random_key = mt_rand(1,1000000);
            Redis::set('search:key'.$random_key,serialize([]),'EX',3600);
            return response()->json($random_key);
        }
    }

    public function change(Request $request)
    {

//        return $request->random_key;
//        return Redis::get('search:key'.$request->random_key);
//        echo response()->json($request->id);

        $array = unserialize(Redis::get('search:key'.$request->random_key));
//        return $array;
//        return 123;
//        echo response()->json($array);
        $key = array_search($request->id,$array);
//        echo response()->json($key);

        if($key!==false) {
            unset($array[$key]);
        }
        else {
            $array[] = $request->id;
        }
        Redis::set('search:key'.$request->random_key,serialize($array),'EX',3600);
        return response()->json(count($array));
//        return response()->json($array);
    }
    
    
    
    
}
