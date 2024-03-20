<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\MessageService;
use App\Services\ProgramService;
use App\Services\AttendanceService;
use App\Services\ErrorsService;

class HomeController extends Controller
{
    protected $programService;
    protected $messageService;
    protected $attendanceService;
    protected $errorsService;

    public function __construct(
        ProgramService $programService,
        MessageService $messageService,
        AttendanceService $attendanceService,
        ErrorsService $errorsService
    )
    {
        $this->programService = $programService;
        $this->messageService = $messageService;
        $this->attendanceService = $attendanceService;
        $this->errorsService = $errorsService;
    }

    public function index()
    {
        $user = auth()->user();

        $programsPercentage = $this->programService->calculateProgramPercentage($user);
        $messagePercentage = $this->messageService->calculateMessagePercentage($user);

        $productivity = (string) max($programsPercentage, $messagePercentage);
        $attendanceService = (string) $this->attendanceService->calculatePointsForUser($user);
        $errorsService = (string) $this->errorsService->calculateErrorsPercentage($user);

        $finalPercentage =  (string) $this->calculateFinalPercentage($productivity, $attendanceService, $errorsService);
        $finalGrade = $this->getGrade($finalPercentage);

    //         // Fetch all users
    //         $users = User::all();
    //         // Calculate and store final_percentage for each user
    //         foreach ($users as $otherUser) {
    //             $otherProgramsPercentage = $this->programService->calculateProgramPercentage($otherUser);
    //             $otherMessagePercentage = $this->messageService->calculateMessagePercentage($otherUser);
    //             $otherProductivity = max($otherProgramsPercentage, $otherMessagePercentage);
    //             $otherAttendanceService = $this->attendanceService->calculatePointsForUser($otherUser);
    //             $otherErrorsService = $this->errorsService->calculateErrorsPercentage($otherUser->id);
    //             $otherUser->final_percentage = $this->calculateFinalPercentage($otherProductivity, $otherAttendanceService, $otherErrorsService);
    //         }

    //         // Sort users based on final_percentage
    //         $sortedUsers = $users->sortByDesc('final_percentage');

    //         // Determine user's rank
    //         $userRank = $sortedUsers->search(function ($otherUser) use ($user) {
    //             return $otherUser->id === $user->id;
    //         });

    //         // If user is not in top 3, set rank to 4
    //         if ($userRank === false || $userRank >= 3) {
    //             $userRank = 4;
    //         } else {
    //             $userRank++; // Adjust rank since index starts from 0
    //         }

    //        // Get top 3 users
    // $topThreeUsers = $sortedUsers->take(3)->map(function ($user) {
    //     return [
    //         'name' => $user->name,
    //         'final_percentage' => $user->final_percentage,
    //     ];
    // });

        return response()->json([
            'data' => [
                'percentage' => [
                'productivity_percentage' => [
                    'productivity'=>    $productivity,
                    'programs_target'=> (string)$user->section->programs_target,
                    'message_target'=> (string) $user->section->follow_up_target,
            ],
                'attend' => $attendanceService,
                'errors' => $errorsService,
                'final_percentage' => $finalPercentage,
                'final_grade' => $finalGrade,
                //'rank' => $userRank,
                ],
                //'top_three_users' => $topThreeUsers,

                // 'programs_target'=>  $user->section->programs_target,
                // 'message_target'=>  $user->section->follow_up_target,
            ],
        ]);
    }
    private function calculateFinalPercentage($productivity, $attendanceService, $errorsService)
    {
        return (($productivity * 30) + ($attendanceService * 50) + ($errorsService * 20)) / 100;
    }
    private function getGrade($finalPercentage)
    {
        if ($finalPercentage >= 90) {
            return 'A';
        } elseif ($finalPercentage >= 80) {
            return 'B';
        } elseif ($finalPercentage >= 70) {
            return 'C';
        } elseif ($finalPercentage >= 60) {
            return 'D';
        } else {
            return 'F';
        }
    }


}
