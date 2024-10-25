<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IntervalsController extends Controller
{
    public function isMergeIntervals($intervals) {
        if (empty($intervals)) {
            return [];
        }

        usort($intervals, function($a, $b) {
            return $a[0] <=> $b[0];
        });

        $merged = [$intervals[0]];

        for ($i = 1; $i < count($intervals); $i++) {
            $current = $intervals[$i];
            $lastMerged = end($merged);

            if ($current[0] <= $lastMerged[1]) {
                $merged[count($merged) - 1][1] = max($lastMerged[1], $current[1]);
            } else {
                $merged[] = $current;
            }
        }

        return $merged;
    }

    public function mergeIntervals(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'number'=> 'required|array'
        ]);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $numbers = [];
        foreach ($request->number as $key => $value) {
            foreach ($value as $val) {
                $numbers[$key][] = (int)$val;
            }
        }

        $merge = $this->isMergeIntervals($numbers);

        return response()->json($merge);
    }

}
