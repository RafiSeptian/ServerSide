<?php
/**
 * Created by PhpStorm.
 * User: LKSWEB_2019
 * Date: 11/17/2019
 * Time: 9:52 AM
 */

namespace App\Libs;

class Response{
    public static function success($args){
        return response()->json($args, 200);
    }public static function invalid($args){
        return response()->json($args, 401);
    }public static function forbidden($args){
        return response()->json($args, 403);
    }public static function notvalid($args){
        return response()->json($args, 422);
    }
}