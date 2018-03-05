<?php

namespace TraderInteractive;

use Exception;

/**
 * Exception to throw when an http status code should be included.
 *
 * Defaults to a 500 error.
 */
final class HttpException extends Exception
{
    private $httpStatusCode;
    private $userMessage;

    /**
     * Constructs
     *
     * @param string $message @see Exception::__construct()
     * @param int $httpStatusCode a valid http status code
     * @param int $code @see Exception::__construct()
     * @param Exception $previous @see Exception::__construct()
     * @param string|null $userMessage a nicer message to display to the user sans sensitive details
     */
    public function __construct(
        string $message = 'Application Error',
        int $httpStatusCode = 500,
        int $code = 0,
        Exception $previous = null,
        string $userMessage = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->httpStatusCode = $httpStatusCode;

        if ($userMessage !== null) {
            $this->userMessage = $userMessage;
        } else {
            $this->userMessage = $message;
        }
    }

    /**
     * Getter for $httpStatusCode
     *
     * @return int the http status code
     */
    public function getHttpStatusCode() : int
    {
        return $this->httpStatusCode;
    }

    /**
     * Getter for $userMessage
     *
     * @return string the user message
     */
    public function getUserMessage() : string
    {
        return $this->userMessage;
    }
}
