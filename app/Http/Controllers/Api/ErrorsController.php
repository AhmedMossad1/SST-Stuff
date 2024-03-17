<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\ErrorsResource;
use App\Models\Error as ModelsError;
use App\Services\ErrorsService;

class ErrorsController extends Controller
{
    protected $errorsService;

    public function __construct(ErrorsService $errorsService)
    {
        $this->errorsService = $errorsService;
    }
    public function index(){
        {
            $user = auth()->user();
            $percentage = (string) $this->errorsService->calculateErrorsPercentage($user);


            return response()->json([
                'data' => [
                    'percentage' => $percentage,
                    'errors' => ErrorsResource::collection(getDataForCurrentMonth($user,ModelsError::class)),
                ],
            ]);
        }
}
}
