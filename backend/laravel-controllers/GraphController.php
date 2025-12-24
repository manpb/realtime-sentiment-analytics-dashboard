<?php

namespace App\Http\Controllers;

use App\Models\Sentiment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GraphController extends Controller
{
    // list all sentiments
    public function list()
    {
        $list = Sentiment::orderBy('date')->get();
        return response()->json(['list' => $list]);
    }
    // list all monthly history sentiments
    public function listMonthly($selected_month)
    {
        // Convert the selected month to a Carbon instance
        $carbonMonth = Carbon::createFromFormat('Y-m', $selected_month);
        // Fetch monthly data for the selected month
        $listMonthly = Sentiment::selectRaw('date,
                                        AVG(negative) as average_negative,
                                        AVG(neutral) as average_neutral,
                                        AVG(positive) as average_positive,
                                        AVG(`very-positive`) as average_very_positive')
            ->whereYear('date', $carbonMonth->year)
            ->whereMonth('date', $carbonMonth->month)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json(['listMonthly' => $listMonthly]);
    }
    // list all hourly sentiments
    public function listHourly($selected_date)
    {
        $currentDate = now()->toDateString();
        // $currentDate = '2024-01-11';
        $listHourly = Sentiment::whereDate('date', $selected_date)
            ->orderBy('time')
            ->get();

        return response()->json(['listHourly' => $listHourly]);
    }
    // show last hour sentiment
    public function showLiveHour()
    {
        $currentDate = now()->toDateString();
        // dd($currentDate);
        $endTime = now()->format('H:00:00');
        // dd($endTime);
        $startTime = now()->subHour()->format('H:00:00');
        // dd($startTime);
        $showHourRange = Sentiment::whereDate('date', $currentDate)
            // ->whereBetween('time', [$startTime, $endTime])
            ->latest()
            ->first();
        // dd($showHourRange);
        if ($showHourRange === null) {
            $defaultResponse = [
                "showHourRange" => [
                    "id" => 0,
                    "negative" => 0,
                    "neutral" => 0,
                    "positive" => 0,
                    "very-positive" => 0,
                    "date" => $currentDate,
                    "time" => $endTime,
                    "created_at" => "2024-01-15T14:03:29.000000Z",
                    "updated_at" => "2024-01-15T14:03:29.000000Z"
                ]
            ];
            return response()->json($defaultResponse);
        }

        return response()->json(['showHourRange' => $showHourRange]);
    }
}
