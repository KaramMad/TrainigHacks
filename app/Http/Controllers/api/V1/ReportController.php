<?php

namespace App\Http\Controllers\api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Report;
use App\Models\User;

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
            ]);
        } else {

            Report::create([
                'user_id' => $userId,
                'report_date' => $today,
                'calories' => $request->calories,
                'Number_of_exercises' => $request->Number_of_exercises,
                'total_time' => $request->total_time,
            ]);
        }

        return response()->json(['message' => 'Report saved successfully']);
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
