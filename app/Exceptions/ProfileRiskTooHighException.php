<?php

namespace App\Exceptions;

use RuntimeException;

class ProfileRiskTooHighException extends RuntimeException
{
    public function __construct(
        public readonly int $riskScore,
        string $message = ''
    ) {
        parent::__construct($message ?: "Profile risk score {$riskScore} exceeds threshold.");
    }
}
