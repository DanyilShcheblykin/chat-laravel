<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function ok($data)
    {
        return response()->json($data);
    }

    protected function fail($message, $code = 400)
    {
        return response()->json(["message" => $message], $code);
    }
}
