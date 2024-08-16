<?php

namespace App\Http\Controllers\api\V1;
use App\Models\Bill;
use App\Models\Order;
use App\Providers\AppServiceProvider as AppSP;
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
            'time' => 'required|date_format:H:i:s',
            'total_calories' => 'required|integer|min:0',
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
            $existingTotalTimeSeconds = $this->timeToSeconds($report->time);
            $newTotalTimeSeconds = $this->timeToSeconds($request->time);

            $report->update([
                'calories' => $report->calories + $request->calories,
                'Number_of_exercises' => $report->Number_of_exercises + $request->Number_of_exercises,
                'total_calories'=>$report->total_calories+$request->total_calories,
                'total_time'=>$this->secondsToTime($existingTotalTimeSeconds+$newTotalTimeSeconds),
                'time' => $this->secondsToTime($existingTotalTimeSeconds + $newTotalTimeSeconds),
                'weight' => $newWeight,
                'bmi' => $bmi,

            ]);
        } else {
            Report::create([
                'user_id' => $userId,
                'report_date' => $today,
                'calories' => $request->calories,
                'Number_of_exercises' => $request->Number_of_exercises,
                'time' => $request->time,
                'weight' => $newWeight,
                'bmi' => $bmi,
                'total_calories' => $request->total_calories,
                'total_time' => $request->total_time,
            ]);
        }

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

        $user = $request->user();
        $height = $user->tall ? $user->tall / 100 : null;
        $weight = $user->weight;

        $totalCaloriesBurned = $report->calories;

        $stepsCaloriesBurned = $this->calculateCaloriesFromSteps($report->steps, $weight);

        $totalCaloriesBurned += $stepsCaloriesBurned;


        $newWeight = $this->calculateNewWeight($weight, $totalCaloriesBurned);

        $currentBMI = $height && $weight ? $this->calculateBMI($weight, $height) : null;
        $newBMI = $height && $newWeight ? $this->calculateBMI($newWeight, $height) : null;

        $bmiCategory = $this->getBMICategory($currentBMI);

        $timeMinutes = intval($this->timeToSeconds($report->time) / 60);
        $totalTimeMinutes = intval($this->timeToSeconds($report->total_time) / 60);

        $dailyData = [
            'date' => $report->report_date,
            'calories' => $report->calories,
            'total-calories' => $report->total_calories,
            'current_weight' => $weight,
            'new_weight' => $newWeight,
            'current_bmi' => $currentBMI,
            'new_bmi' => $newBMI,
            'bmi_category' => $bmiCategory,
            'Number_of_exercises' => $report->Number_of_exercises,
            'time' => $timeMinutes,
            'total_time' => $totalTimeMinutes,
            'steps' => $report->steps,
            'level'=>$user->level,
        ];

        return response()->json($dailyData);
    }

    public function getWeeklyReport(Request $request)
    {
        $userId = $request->user()->id;
        $endOfWeek = Carbon::now()->endOfDay();
        $startOfWeek = Carbon::now()->subDays(6)->startOfDay();
        $reports = Report::where('user_id', $userId)
            ->whereBetween('report_date', [$startOfWeek, $endOfWeek])
            ->orderBy('report_date', 'asc')
            ->get()
            ->keyBy('report_date');

        $user = $request->user();
        $height = $user->tall ? $user->tall / 100 : null;
        $currentWeight = $user->weight;

        $weeklyData = [
            'total_steps' => 0,
            'total_calories' => 0,
            'calories' => 0,
            'total_time_seconds' => 0,
            'time' => 0,
            'exercises' => 0,
            'daily_reports' => [],
        ];

        for ($i = 6; $i >= 0; $i--) {
            $currentDate = Carbon::now()->subDays($i)->startOfDay()->toDateString();
            $report = $reports->get($currentDate);

            if ($report) {
                $timeMinutes = intval($this->timeToSeconds($report->time) / 60);
                $totalTimeMinutes = intval($this->timeToSeconds($report->total_time) / 60);

                $dailyData = [
                    'date' => $currentDate,
                    'calories' => $report->calories,
                    'weight' => $currentWeight,
                    'steps' => $report->steps,
                    'exercise' => $report->Number_of_exercises,
                    'total_calories' => $report->total_calories,
                    'time' => $timeMinutes,
                    'total_time' => $totalTimeMinutes,
                ];

                $weeklyData['total_steps'] += $report->steps;
                $weeklyData['calories'] += $report->calories;
                $weeklyData['total_calories'] += $report->total_calories;
                $weeklyData['exercises'] += $report->Number_of_exercises;
                $weeklyData['time'] += $timeMinutes;
                $weeklyData['total_time_seconds'] += $this->timeToSeconds($report->total_time);
            } else {

                $dailyData = [
                    'date' => $currentDate,
                    'calories' => 0,
                    'weight' => $currentWeight,
                    'steps' => 0,
                    'exercise' => 0,
                    'total_calories' => 0,
                    'time' => 0,
                    'total_time' => 0,
                ];
            }

            $weeklyData['daily_reports'][] = $dailyData;
        }

        $caloriesFromSteps = $this->calculateCaloriesFromSteps($weeklyData['total_steps'], $currentWeight);
        $totalCaloriesBurned = $weeklyData['total_calories'] + $caloriesFromSteps;
        $newWeight = $this->calculateNewWeight($currentWeight, $totalCaloriesBurned);

        $user->weight = $newWeight;
        $user->save();


        $bmi = $height && $newWeight ? $this->calculateBMI($newWeight, $height) : null;
        $bmiCategory = $bmi !== null ? $this->getBMICategory($bmi) : 'Unknown';


        $totalMinutes = intval($weeklyData['total_time_seconds'] / 60);

        $weeklyData['total_time'] = $totalMinutes;


        $weeklyData['end_of_week'] = [
            'weight' => $newWeight,
            'bmi' => $bmi,
            'bmi_category' => $bmiCategory,
            'total_calories' => $weeklyData['total_calories'],
            'total_time' => $weeklyData['total_time'],
        ];

        return response()->json($weeklyData);
    }

    private function calculateNewWeight($currentWeight, $caloriesBurned)
    {
        $caloriesPerKg = 7700;
        $weightLost = $caloriesBurned / $caloriesPerKg;

        return $currentWeight - $weightLost;
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
    private function calculateCaloriesFromSteps($steps, $weight)
    {
        return $steps * 0.05 * ($weight / 70);
    }

    public function salesByMonth()
    {
        $validator = Validator::make(request()->only('month'), [
            'month' => 'required|date_format:Y-m',
        ]);
        if ($validator->fails()) {
            return AppSP::apiResponse('validation error', $validator->errors(), 'errors', false, 422);
        }
        $selectedMonth = request()->month;

        $totalSales = Order::where('status', 'received')
            ->whereMonth('updated_at', '=', substr($selectedMonth, 5, 2))
            ->whereYear('updated_at', '=', substr($selectedMonth, 0, 4))
            ->with('products')
            ->get()
            ->map(function ($order) {
                return $order->products->map(function ($product) use ($order) {
                    return [
                        'product_id' => $product->id,
                        'quantity' => $product->pivot->quantity,
                        'total_sale' => $product->pivot->quantity * $product->pivot->price,
                    ];
                });
            })
            ->flatten(1)
            ->groupBy('product_id')
            ->map(function ($sales) {
                return [
                    'total_quantity' => $sales->sum('quantity'),
                    'total_sales' => $sales->sum('total_sale'),
                ];
            });
        $totalSales = collect($totalSales
            ->map(function ($sales, $product_id) {
                return [
                    'product_id' => $product_id,
                    'total_quantity' => $sales['total_quantity'],
                    'total_sales' => $sales['total_sales'],
                ];
            })
            ->sortByDesc('total_sales')
            ->values()
            ->toArray());
        $totalRefundingvalue=Bill::where('billable_type',"App\Models\Order")->where('paid','=',1)->whereNot('refunding','=',0)->sum('total');
        $totalSalesAllProducts = $totalSales->sum('total_sales')+$totalRefundingvalue;
        $totalQuantityAllProducts = $totalSales->sum('total_quantity');
        $finalData = [
            'total_sales' => $totalSalesAllProducts,
            'total_quantity' => $totalQuantityAllProducts,
            'products' => $totalSales,
        ];
        return AppSP::apiResponse('retrieved report', $finalData);
    }
    public function totalRefund()  {
        $totalrefundedValue=Bill::where('billable_type',"App\Models\Order")->where('paid','=',1)->sum('refunding');
        return $this->success($totalrefundedValue);
    }
    public function totalSale(){
        $totalrefundedValue=Bill::where('billable_type',"App\Models\Subscription")->where('paid','=',1)->sum('total');
        return $this->success($totalrefundedValue);

    }
}

