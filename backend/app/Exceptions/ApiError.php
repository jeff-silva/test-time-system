<?php

namespace App\Exceptions;

use App\Models\AppError;
use Illuminate\Http\Response;

class ApiError extends \Exception
{
    protected $code = null;
    protected $message = null;
    protected $errors = null;

    public function __construct($code = 500, $message = '', $errors = [], \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->code = $code;
        $this->message = $message;
        $this->errors = $errors;
    }

    public function render(): Response
    {
        return response([
            'code' => $this->code,
            'message' => $this->message,
            'errors' => $this->errors,
        ], $this->code);
    }
}
