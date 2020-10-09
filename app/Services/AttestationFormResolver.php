<?php

declare(strict_types=1);

namespace App\Services;

use App\Attestation;
use App\Contracts\AttestationFormResolver as AttestationFormResolverContract;
use Exception;

class AttestationFormResolver implements AttestationFormResolverContract
{
    public function resolve(Attestation $attestation): string
    {
        $viewFileName = snake_case(preg_replace('/, /', '', $attestation->aptitude->name));
        if (file_exists(base_path('resources/views/attestations/forms/'.$viewFileName.'.blade.php'))) {
            return 'attestations.forms.'.$viewFileName;
        }

        throw new Exception('Form template '.$viewFileName.' not found for attestation with aptitude '.$attestation->aptitude->name);
    }
}
