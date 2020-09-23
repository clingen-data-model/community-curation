<?php

namespace App\Http\Controllers;

use App\Country;
use App\Http\Requests\RequiredUserInfoRequest;
use App\Surveys\SurveyOptions;
use App\User;
use Illuminate\Support\Facades\Auth;

class RequiredInfoController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        if (!is_null($user->country_id) && !is_null($user->timezone) && $user->timezone != 'UTC') {
            return redirect('/');
        }

        $countries = Country::all();
        $timezones = (new SurveyOptions())->timezones();

        return view('users.required_info_form', compact('user', 'countries', 'timezones'));
    }

    public function update(RequiredUserInfoRequest $request)
    {
        $user = User::find($request->user_id);

        $user->update([
            'timezone' => $request->timezone,
            'country_id' => $request->country_id,
        ]);
        session()->flash('success', 'Your information has been updated.  Thanks!');

        return redirect('/');
    }

    public function bypass()
    {
        session()->put('app_impersonate_required_info_bypass', true);

        return redirect('/');
    }
}
