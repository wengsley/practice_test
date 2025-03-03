<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function successWithData($data,$msg='')
    {
        return response()->json([            
            'status' => 'success',
            'status_code' => 200,
            'msg'=>$msg,
            'data' => $data,
        ], 200);
    }

    public function errorWithData($data, $msg='')
    {
        return response()->json([            
            'status' => 'error',            
            'status_code' => 422,
            'msg'=>$msg,
            'data' => $data,
        ],422);
    }
    
     public function validatorFails($errors)
    {
   
        return response()->json([
            'status' => 'error',
            'status_code' => 422,
            'msg'=>'Validation Error.',
            'errors' => $errors,            
        ],422);
    }
}
