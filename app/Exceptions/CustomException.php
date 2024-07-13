<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    protected $custom_code;

    public function __construct($message, $customCode)
    {
        parent::__construct($message, 422);

        $this->custom_code = $customCode;
    }

    public function render(){
        return response()->json([
            "code" => $this->getCustomCode(),
            'message' => $this->getMessage()
        ], $this->getCustomCode());
    }

    public function getCustomCode()
    {
        return $this->custom_code;
    }
}
