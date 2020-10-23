<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Handle Exception and generate log on log output
    *
    * @param \Throwable $th
    * @param string $title
    * @return \Illuminate\Http\Response
    */
    public function handleException(\Throwable $th, $title = "")
    {
        logger()->error(
            "[Category erro] " . $title,
            [
                "user_id" => auth()->user()->id,
                "error" => $th->getMessage(),
                "line" => $th->getLine(),
                "file" => $th->getFile(),
                "trace" => $th->getTrace()
            ]
        );
        return response()->json([
            "success" => false,
            'message' => $th->getMessage()
        ], 400);
    }
}
