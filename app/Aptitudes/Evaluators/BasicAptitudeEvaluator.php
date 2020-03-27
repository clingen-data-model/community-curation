<?php

namespace App\Aptitudes\Evaluators;

class BasicAptitudeEvaluator implements AptitudeEvaluatorContract
{
    protected $userAptitude;

    public function __construct($userAptitude)
    {
        $this->userAptitude = $userAptitude;
    }

    public function meetsCriteria():bool
    {
        if (is_null($this->userAptitude->trained_at)) {
            return false;
        }
        if (!$this->hasSignedAttestation()) {
            return false;
        }

        return true;
    }

    public function hasSignedAttestation()
    {
        if (is_null($this->userAptitude->attestation_id)) {
            return false;
        }

        return $this->userAptitude->attestation->isSigned();
    }
}
