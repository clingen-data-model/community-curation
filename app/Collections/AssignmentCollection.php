<?php

namespace App\Collections;

use App\Assignment;
use App\CurationActivity;
use App\CurationGroup;
use App\Gene;
use Illuminate\Database\Eloquent\Collection;

class AssignmentCollection extends Collection
{
    public function isType($type)
    {
        return $this->filter(function (Assignment $assignment) use ($type) {
            if (is_string($type)) {
                return $assignment->assignable_type == $type;
            }
            if (is_iterable($type)) {
                foreach ($type as $t) {
                    if ($assignment->assignable_type == $t) {
                        return true;
                    }
                }
            }

            return false;
        });
    }

    public function isCurationActivity()
    {
        return $this->isType(CurationActivity::class);
    }

    public function isCurationGroup()
    {
        return $this->isType(CurationGroup::class);
    }

    public function isGene()
    {
        return $this->isType(Gene::class);
    }
}
