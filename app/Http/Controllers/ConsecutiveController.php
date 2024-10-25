<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsecutiveController extends Controller
{
    function isLongestConsecutive($nums) {
        if (empty($nums)) {
            return 0;
        }

        $numSet = array_flip($nums);
        $longestStreak = 0;

        foreach ($numSet as $num => $_) {
            if (!isset($numSet[$num - 1])) {
                $currentNum = $num;
                $currentStreak = 1;

                while (isset($numSet[$currentNum + 1])) {
                    $currentNum++;
                    $currentStreak++;
                }

                $longestStreak = max($longestStreak, $currentStreak);
            }
        }

        return $longestStreak;
    }

    public function longestConsecutive(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'number'=> 'required|array'
        ]);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $numbers = [];
        foreach ($request->number as $value) {
            $numbers[] = (int)$value;
        }

        $consecutive = $this->isLongestConsecutive($numbers);

        return response()->json($consecutive);
    }
}
