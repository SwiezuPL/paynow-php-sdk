<?php

namespace Paynow\Exception;

class Error
{
    /**
     * @var string
     */
    private $errorType;

    /**
     * @var string
     */
    private $message;

    public function __construct($errorType, $message)
    {
        $this->errorType = $errorType;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getErrorType(): string
    {
        return $this->errorType;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}