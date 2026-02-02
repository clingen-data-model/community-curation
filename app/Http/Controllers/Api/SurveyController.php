<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\SurveyJsonResponse;
use App\Surveys\SurveyOptions;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    private static $slugToFile = [
        'volunteer-three-month1' => 'volunteer-three-month',
        'volunteer-six-month1' => 'volunteer-six-month',
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
            default:
                abort(404, 'Unknown choice type');
        }
    }

    public function getResponse(Request $request, $slug)
    {
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

        $user = Auth::user();

        $request->validate([
            'response_data' => 'required|array',
            'last_page' => 'nullable|string',
            'finalize' => 'nullable|boolean',
        ]);

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

        return response()->json([
            'id' => $response->id,
            'response_data' => $response->response_data,
            'last_page' => $response->last_page,
            'started_at' => $response->started_at,
            'finalized_at' => $response->finalized_at,
        ]);
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
