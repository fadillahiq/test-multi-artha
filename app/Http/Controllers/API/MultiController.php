<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\Input;

class MultiController extends Controller
{
    public function palindrome(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'words' => ['required', 'max:64', 'min:3', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $words = $request->words;
            $lower = Str::lower($words);
            $strev = strrev($lower);

            if ($strev == $lower) {
                $response = [
                    'Input' => $words,
                    'Output' => true
                ];

                return response()->json($response, Response::HTTP_OK);
            } else {
                $response = [
                    'Input' => $words,
                    'Output' => false
                ];

                return response()->json($response, Response::HTTP_OK);
            }
        } catch (QueryException $e) {
            return response()->json([
                'message' => ["Failed", $e->errorInfo]
            ]);
        }
    }

    public function merge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'words' => ['required', 'max:64', 'min:3', 'string'],
            'jumlah' => ['required', 'integer']
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $words = str_replace(' ', '', $request->words);
            $jumlah = $request->jumlah;

            $total = Str::length($words) / $jumlah;

            $index = 0;

            $output = [];

            // Mengelompokkan Sesuai Jumlah
            for ($a = 0; $a < $total; $a++) {
                $group = "";

                for ($i = 0; $i < $jumlah; $i++) {
                    $group = $group . $words[$index];
                    $index = $index + 1;
                }

                $seleksi = array_unique(str_split($group)); // Convert String ke Array dan Seleksi Jika Ada Duplicate Character
                $array_to_string = str_replace(',', '', implode(',', $seleksi));

                $output[] = $array_to_string;
            }

            return response()->json($output, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => ["Failed", $e->errorInfo]
            ]);
        }
    }
}
