<?php

class CultureFeed_Exception extends Exception
{
    private string $error_code;

    private string $userFriendlyMessage;

    function __construct($message, $error_code, $code = 0)
    {
        parent::__construct($message, $code);
        $this->error_code = $error_code;
    }

    public function getUserFriendlyMessage(): ?string
    {
        return $this->userFriendlyMessage;
    }

    public function setUserFriendlyMessage(string $message): void
    {
        $this->userFriendlyMessage = $message;
    }

    public function getErrorCode(): string
    {
        return $this->error_code;
    }
}
