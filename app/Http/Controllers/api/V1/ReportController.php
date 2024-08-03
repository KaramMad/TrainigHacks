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
            $report->update([
                'calories' => $request->calories,
                'Number_of_exercises' => $request->Number_of_exercises,
                'total_time' => $request->total_time,
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

}
