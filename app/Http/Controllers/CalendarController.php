<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Plan;

class CalendarController extends Controller
{
    function addDate(Request $request, $recipeId)
    {
        $user = Auth::user();

        $plan = new Plan;
        $plan->user_id = $user->id;
        $plan->recipe_id = $recipeId;
        $plan->date = $request->date;
        $plan->save();
        return response()->json(["plan" => $plan]);
    }

    function getDate()
    {
        $user_id = Auth::user()->id;

        $plans = Plan::with(['recipe'])->where('user_id', $user_id)->get();

        $schedule = $plans->map(function ($plan) {
            return [
                "date" => $plan->date,
                "title" => $plan->recipe->title,
            ];
        });

        return response()->json(["schedule" => $schedule]);
    }
}
