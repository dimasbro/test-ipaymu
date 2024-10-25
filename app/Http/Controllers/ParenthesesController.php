<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ParenthesesController extends Controller
{
    function isValidBracketSequence($s) {
        $bracketMap = [
            ')' => '(',
            '}' => '{',
            '>' => '<',
            ']' => '[',
        ];

        $stack = [];
        for ($i = 0; $i < strlen($s); $i++) {
            $char = $s[$i];
            if (!isset($bracketMap[$char]) && !in_array($char, $bracketMap)) {
                return false;
            }

            if (in_array($char, $bracketMap)) {
                $stack[] = $char;
            } elseif (isset($bracketMap[$char])) {
                if (empty($stack) || array_pop($stack) !== $bracketMap[$char]) {
                    return false;
                }
            }
        }

        return empty($stack);
    }

    public function validateParentheses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'character'=> 'required'
        ]);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $validate = $this->isValidBracketSequence($request->character);

        return response()->json($validate);
    }

}
