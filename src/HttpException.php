<?php

namespace DominionEnterprises;

use Exception;
use InvalidArgumentException;

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
     *
     * @throws InvalidArgumentException if $message is not a string
     * @throws InvalidArgumentException if $httpStatusCode is not an int
     * @throws InvalidArgumentException if $code is not an int
     * @throws InvalidArgumentException if $userMessage is not null and is not a string
     */
    public function __construct(
        string $message = 'Application Error',
        int $httpStatusCode = 500,
        int $code = 0,
        Exception $previous = null,
        string $userMessage = null
    ) {
        if ($userMessage !== null && !is_string($userMessage)) {
            throw new InvalidArgumentException('$userMessage was not null and not a string');
        }

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
