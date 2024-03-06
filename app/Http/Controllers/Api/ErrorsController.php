<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\ErrorsResource;
use App\Models\Error as ModelsError;
class ErrorsController extends Controller
{
    public function index(){
        $errors = ModelsError::where('user_id',auth()->id())->get();
        $total = $errors->sum('degree');
        if($total <= 100){
            $percentage = (100-$total)/100;
        }
        else{
            $percentage=0;
        }
        return response()->json([
            'data' => [
                'percentage'=>$percentage,
                'errors' => ErrorsResource::collection($errors),
            ],
    ]);
    }
}
