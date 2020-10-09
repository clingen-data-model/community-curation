<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Attestation;

interface AttestationFormResolver
{
    public function resolve(Attestation $attestation): string;
}
