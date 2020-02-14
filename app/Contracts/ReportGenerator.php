<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface ReportGenerator
{
    public function generate():Collection;    
}