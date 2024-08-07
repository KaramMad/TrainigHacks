<?php

namespace App\Http\Controllers\api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function createOrUpdateReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'calories' => 'required|integer|min:0',
            'Number_of_exercises' => 'required|integer|min:0',
            'total_time' => 'required|date_format:H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $userId = $request->user()->id;
        $today = Carbon::today();


        $user = User::find($userId);
        $height = $user->tall ? $user->tall / 100 : null;
        $weight = $user->weight;

        $newWeight = $this->calculateNewWeight($weight, $request->calories);
        $bmi = $height && $newWeight ? $this->calculateBMI($newWeight, $height) : null;

        $report = Report::where('user_id', $userId)
            ->where('report_date', $today)
            ->first();

        if ($report) {
            $existingTotalTimeSeconds = $this->timeToSeconds($report->total_time);
            $newTotalTimeSeconds = $this->timeToSeconds($request->total_time);

            $report->update([
                'calories' => $report->calories + $request->calories,
                'Number_of_exercises' => $report->Number_of_exercises + $request->Number_of_exercises,
                'total_time' => $this->secondsToTime($existingTotalTimeSeconds + $newTotalTimeSeconds),
                'weight' => $newWeight,
                'bmi' => $bmi,
            ]);
        } else {
            Report::create([
                'user_id' => $userId,
                'report_date' => $today,
                'calories' => $request->calories,
                'Number_of_exercises' => $request->Number_of_exercises,
                'total_time' => $request->total_time,
                'weight' => $newWeight,
                'bmi' => $bmi,
            ]);
        }

        // مشان غير وزن المستخدم الاصلي
       // $user->update(['weight' => $newWeight]);

        return response()->json(['message' => 'Report saved successfully']);
    }

    public function createOrUpdateStepsReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'steps' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $userId = $request->user()->id;
        $today = Carbon::today();

        $report = Report::where('user_id', $userId)
            ->where('report_date', $today)
            ->first();

        if ($report) {
            $report->update([
                'steps' => $report->steps + $request->steps,
            ]);
        } else {
            Report::create([
                'user_id' => $userId,
                'report_date' => $today,
                'steps' => $request->steps,
            ]);
        }

        return response()->json(['message' => 'Steps report saved successfully']);
    }

    public function getDailyReport(Request $request)
    {
        $userId = $request->user()->id;

        $today = Carbon::today()->format('Y-m-d');


        $report = Report::where('user_id', $userId)
            ->whereDate('report_date', $today)
            ->first();

        if (!$report) {
            return response()->json(['message' => 'No report found for today'], 404);
        }

        $totalCaloriesBurned = 0;
        $user = $request->user();
        $height = $user->tall ? $user->tall / 100 : null;

        $weight = $user->weight;
        $totalCaloriesBurned += $report->calories;

        $newWeight = $this->calculateNewWeight($weight, $totalCaloriesBurned);

        $bmi = $height && $weight ? $this->calculateBMI($weight, $height) : null;
        $bmiCategory = $this->getBMICategory($bmi);
        $dailyData = [
            'date' => $report->report_date,
            'calories' => $report->calories,
            'weight' =>  $newWeight,
            'bmi' => $bmi,
            'bmi_category' => $bmiCategory,
            'Number_of_exercises' => $report->Number_of_exercises,
            'total_time' => $report->total_time,
            'steps' => $report->steps,
        ];

        return response()->json($dailyData);
    }

    public function getWeeklyReport(Request $request)
    {
        $userId = $request->user()->id;
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $reports = Report::where('user_id', $userId)
            ->whereBetween('report_date', [$startOfWeek, $endOfWeek])
            ->orderBy('report_date', 'asc')
            ->get();

        if ($reports->isEmpty()) {
            return response()->json(['message' => 'No reports found for this week'], 404);
        }

        $user = $request->user();
        $height = $user->tall ? $user->tall / 100 : null;
        $currentWeight = $user->weight;

        $totalCaloriesBurned = 0;

        $weeklyData = [
            'total_steps' => 0,
            'total_calories' => 0,
            'total_exercises' => 0,
            'total_time_seconds' => 0,
            'daily_reports' => [],
        ];

        foreach ($reports as $report) {
            $reportDate = Carbon::parse($report->report_date);
            $dailyData = [
                'date' => $reportDate->toDateString(),
                'calories' => $report->calories,
                'weight' => $user->weight,
                'steps' => $report->steps,
            ];

            $weeklyData['total_steps'] += $report->steps;
            $weeklyData['total_calories'] += $report->calories;
            $weeklyData['total_exercises'] += $report->Number_of_exercises;
            $weeklyData['total_time_seconds'] += $this->timeToSeconds($report->total_time);
            $weeklyData['daily_reports'][] = $dailyData;

            $totalCaloriesBurned += $report->calories;
        }

        $newWeight = $this->calculateNewWeight($currentWeight, $totalCaloriesBurned);


        $bmi = $height && $newWeight ? $this->calculateBMI($newWeight, $height) : null;
        $bmiCategory = $bmi !== null ? $this->getBMICategory($bmi) : 'Unknown';
        $weeklyData['total_time'] = $this->secondsToTime($weeklyData['total_time_seconds']);

        $weeklyData['end_of_week'] = [
            'weight' => $newWeight,
            'bmi' => $bmi,
            'bmi_category' => $bmiCategory,
        ];

        return response()->json($weeklyData);
    }

    private function calculateNewWeight($currentWeight, $caloriesBurned)
    {
        $weightLoss = $caloriesBurned / 7700;
        return $currentWeight - $weightLoss;
    }

    private function calculateBMI($weight, $height)
    {

        if ($height <= 0 || $weight <= 0) {
            return null;
        }
       $bmi = $weight / ($height * $height);
        return $bmi;
    }

    private function getBMICategory($bmi)
    {
        if ($bmi < 18.5) {
            return 'Underweight';
        } elseif ($bmi >= 18.5 && $bmi < 25) {
            return 'Normal weight';
        } elseif ($bmi >= 25 && $bmi < 30) {
            return 'Overweight';
        } else {
            return 'Obesity';
        }
    }

    private function timeToSeconds($time)
    {
        list($hours, $minutes, $seconds) = explode(':', $time);
        return ($hours * 3600) + ($minutes * 60) + $seconds;
    }

    private function secondsToTime($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}

