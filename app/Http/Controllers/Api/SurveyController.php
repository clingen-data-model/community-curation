<?php

namespace App\Http\Controllers\Api;

use App\Country;
use App\Http\Controllers\Controller;
use App\Jobs\CreateVolunteerFromApplication;
use App\Mail\ApplicationCompletedMail;
use App\Priority;
use App\SurveyJsonResponse;
use App\Surveys\SurveyOptions;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SurveyController extends Controller
{
    private static $slugToFile = [
        'volunteer-three-month1' => 'volunteer-three-month',
        'volunteer-six-month1' => 'volunteer-six-month',
        'application1' => 'application',
        'priorities1' => 'priorities',
    ];

    private static $authRequiredSlugs = [
        'volunteer-three-month1',
        'volunteer-six-month1',
    ];

    private static $guestAllowedSlugs = [
        'application1',
        'priorities1',
    ];

    public function definition(Request $request, $slug)
    {
        $definition = $this->loadDefinition($slug);
        if (!$definition) {
            abort(404, 'Survey definition not found');
        }

        return response()->json($definition);
    }

    public function choices($type)
    {
        $options = new SurveyOptions();

        switch ($type) {
            case 'curation-activities':
                return response()->json($options->curationActivities());
            case 'curation-groups':
                return response()->json($options->allCurationGroups());
            case 'countries':
                return response()->json(Country::allAsOptions());
            case 'timezones':
                return response()->json($options->timezones());
            case 'self-descriptions':
                return response()->json($options->selfDescriptions());
            case 'ad-campaigns':
                return response()->json($options->adCampaigns());
            case 'motivations':
                return response()->json($options->motivations());
            case 'goals':
                return response()->json($options->goals());
            case 'interests':
                return response()->json($options->interests());
            case 'accepting-curation-groups':
                return response()->json($options->acceptingCurationGroups());
            default:
                abort(404, 'Unknown choice type');
        }
    }

    public function getResponse(Request $request, $slug)
    {
        $this->enforceAuth($slug);

        // Guest users manage progress client-side (localStorage)
        if (in_array($slug, self::$guestAllowedSlugs) && !Auth::check()) {
            return response()->json(null);
        }

        $user = Auth::user();
        $response = SurveyJsonResponse::where('survey_slug', $slug)
            ->where('respondent_id', $user->id)
            ->where('respondent_type', get_class($user))
            ->latest()
            ->first();

        if (!$response) {
            return response()->json(null);
        }

        return response()->json([
            'id' => $response->id,
            'response_data' => $response->response_data,
            'last_page' => $response->last_page,
            'started_at' => $response->started_at,
            'finalized_at' => $response->finalized_at,
        ]);
    }

    public function saveResponse(Request $request, $slug)
    {
        if (!isset(self::$slugToFile[$slug])) {
            abort(404, 'Survey not found');
        }

        $this->enforceAuth($slug);

        $request->validate([
            'response_data' => 'required|array',
            'last_page' => 'nullable|string',
            'finalize' => 'nullable|boolean',
        ]);

        if (in_array($slug, self::$guestAllowedSlugs) && !Auth::check()) {
            return $this->saveGuestResponse($request, $slug);
        }

        $user = Auth::user();

        $response = SurveyJsonResponse::where('survey_slug', $slug)
            ->where('respondent_id', $user->id)
            ->where('respondent_type', get_class($user))
            ->whereNull('finalized_at')
            ->latest()
            ->first();

        if ($response && $response->finalized_at) {
            throw new AuthorizationException('Cannot update a finalized survey response');
        }

        if (!$response) {
            $response = new SurveyJsonResponse();
            $response->survey_slug = $slug;
            $response->respondent_id = $user->id;
            $response->respondent_type = get_class($user);
            $response->started_at = now();
        }

        $response->response_data = $request->input('response_data');
        $response->last_page = $request->input('last_page');

        if ($request->input('finalize')) {
            $response->finalized_at = now();
        }

        $response->save();

        if ($request->input('finalize')) {
            $this->handleFinalization($slug, $response);
        }

        return response()->json([
            'id' => $response->id,
            'response_data' => $response->response_data,
            'last_page' => $response->last_page,
            'started_at' => $response->started_at,
            'finalized_at' => $response->finalized_at,
        ]);
    }

    private function enforceAuth($slug)
    {
        if (in_array($slug, self::$authRequiredSlugs) && !Auth::check()) {
            abort(401, 'Authentication required');
        }
    }

    private function saveGuestResponse(Request $request, $slug)
    {
        $responseData = $request->input('response_data');
        $lastPage = $request->input('last_page');
        $finalize = $request->input('finalize');

        // For non-finalized saves, progress is tracked client-side (localStorage)
        if (!$finalize) {
            return response()->json([
                'id' => null,
                'response_data' => $responseData,
                'last_page' => $lastPage,
                'started_at' => now()->toISOString(),
                'finalized_at' => null,
            ]);
        }

        // On finalization, create the actual database record
        $response = new SurveyJsonResponse();
        $response->survey_slug = $slug;
        $response->respondent_id = 0;
        $response->respondent_type = 'App\User';
        $response->response_data = $responseData;
        $response->last_page = $lastPage;
        $response->started_at = now();
        $response->finalized_at = now();
        $response->save();

        $this->handleFinalization($slug, $response);

        return response()->json([
            'id' => $response->id,
            'response_data' => $response->response_data,
            'last_page' => $response->last_page,
            'started_at' => $response->started_at,
            'finalized_at' => $response->finalized_at,
        ]);
    }

    private function handleFinalization($slug, SurveyJsonResponse $response)
    {
        if ($slug === 'application1') {
            $this->finalizeApplication($response);
        } elseif ($slug === 'priorities1') {
            $this->finalizePriorities($response);
        }
    }

    private function finalizeApplication(SurveyJsonResponse $response)
    {
        if (is_null($response->respondent) || $response->respondent_id === 0) {
            CreateVolunteerFromApplication::dispatch($response);
            $response->refresh();
        }

        $this->storePrioritiesFromResponse($response);

        Mail::to($response->email)->send(new ApplicationCompletedMail($response));

        (new \App\Actions\Reports\ApplicationReportRowCreate)->handleJson($response);
    }

    private function finalizePriorities(SurveyJsonResponse $response)
    {
        $this->storePrioritiesFromResponse($response);
    }

    private function storePrioritiesFromResponse(SurveyJsonResponse $response)
    {
        if (!$response->curation_activity_1) {
            return;
        }

        $prioritizationRound = Priority::selectRaw('max(prioritization_round) as prioritization_round')
            ->where('user_id', $response->respondent_id)
            ->first()->prioritization_round ?? 0;
        ++$prioritizationRound;

        foreach ([1, 2, 3] as $num) {
            if ($response->{'curation_activity_'.$num}) {
                Priority::create([
                    'user_id' => $response->respondent_id,
                    'priority_order' => $num,
                    'curation_activity_id' => $response->{'curation_activity_'.$num},
                    'curation_group_id' => $response->{'panel_'.$num},
                    'activity_experience' => $response->{'activity_experience_'.$num} ?? 0,
                    'activity_experience_details' => $response->{'activity_experience_'.$num.'_detail'},
                    'effort_experience' => $response->{'effort_experience_'.$num} ?? 0,
                    'effort_experience_details' => $response->{'effort_experience_'.$num.'_detail'},
                    'outside_panel' => $response->outside_panel,
                    'prioritization_round' => $prioritizationRound,
                    'survey_id' => null,
                    'response_id' => $response->id,
                ]);
            }
        }
    }

    private function loadDefinition($slug)
    {
        $file = self::$slugToFile[$slug] ?? null;
        if (!$file) {
            return null;
        }

        $path = resource_path("surveys/json/{$file}.json");
        if (!file_exists($path)) {
            return null;
        }

        return json_decode(file_get_contents($path), true);
    }
}
