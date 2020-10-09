<?php

namespace App\Http\Requests\Contracts;

interface VolunteerRequestContract
{
    public function rules();

    public function messages();
}
