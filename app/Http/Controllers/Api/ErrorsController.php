<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\ErrorsResource;
use App\Models\Error as ModelsError;
use Carbon\Carbon;
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
            $userId = auth()->id();
            $percentage = (string) $this->errorsService->calculateErrorsPercentage($userId);
            $errors = $this->errorsService->getErrorsForCurrentMonth($userId);

            return response()->json([
                'data' => [
                    'percentage' => $percentage,
                    'errors' => ErrorsResource::collection($errors),
                ],
            ]);
        }
}
}
