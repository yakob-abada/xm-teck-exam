<?php

namespace App\Service;

class SymbolNotFoundException extends \Exception {
    public function __construct(string $symbol)
    {
        $message = 'Symbol ' .$symbol. ' does not exist';

        parent::__construct($message, 0, null);
    }
}