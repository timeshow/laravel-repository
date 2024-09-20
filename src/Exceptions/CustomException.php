<?php
declare(strict_types=1);
namespace TimeShow\Repository\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CustomException extends Exception
{

    /**
     * @param $message
     * @param $code
     * @param Exception|null $previous
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Record exceptions in the log
     * @param Exception $exception
     * @return void
     */
    public function report(Exception $exception) : void
    {
        Log::error($exception->getMessage());
    }

    /**
     * Return JSON response
     * @return JsonResponse
     */
    public function render() : JsonResponse
    {
        return response()->json([
            'success' => false,
            'code' => 1,
            'message' => $this->message,
        ]);

    }

}