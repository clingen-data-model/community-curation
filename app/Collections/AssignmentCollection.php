<?php

namespace App\Collections;

use App\Assignment;
use App\ExpertPanel;
use App\CurationActivity;
use Illuminate\Database\Eloquent\Collection;

class AssignmentCollection extends Collection
{

    public function isType(string $type)
    {
        return $this->filter(function (Assignment $assignment) use ($type) {
            return $assignment->assignable_type == $type;
        });
    }

    public function isCurationActivity()
    {
        return $this->isType(CurationActivity::class);
    }

    public function isExpertPanel()
    {
        return $this->isType(ExpertPanel::class);
    }    
}
