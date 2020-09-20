<?php
namespace App\Exceptions;

use Illuminate\Http\Response;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AppException extends HttpException
{
    protected $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;

    public function __construct($message = null, Exception $previous = null, $statusCode = null)
    {
        parent::__construct($statusCode ?? $this->statusCode, $message, $previous);
    }
}
