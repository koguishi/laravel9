<?php

namespace core\domain\notification;

class Notification
{
    protected $errors = [];
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(array $error): void
    {
        array_push($this->errors, $error);
    }

    public function hasError(): bool
    {
        return count($this->errors) > 0;
    }

    public function getMessage(string $context = ''): string
    {
        $messages = '';
        foreach ($this->errors as $error) {
            if (empty($context) || $context === $error['context']) {
                $messages .= "{$error['context']}: {$error['message']}; ";
            }
        }
        return $messages;
    }

}