<?php

namespace App\Http\Controllers\Api;

use App\Country;
use App\Http\Controllers\Controller;
use App\SurveyResponse;
use App\Jobs\CreateVolunteerFromApplication;
use App\Mail\ApplicationCompletedMail;
use App\Priority;
use App\Surveys\SurveyOptions;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Tag\TaggedValue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SurveyController extends Controller
{
    /**
     * Get a survey definition from YAML and return as JSON. Handles !include directives to allow modular YAML files.
     *
     * @param string $survey The survey name (without .yaml extension)
     * @return JsonResponse
     */

    private static $authRequiredSlugs = [
        'volunteer-three-month.1',
        'volunteer-six-month.1',
    ];

    private static $guestAllowedSlugs = [
        'application.1',
        'priorities.1',
    ];

    public function definition(Request $request, $slug)
    {
        $user = $this->enforceAuth($request, $slug);
        // Sanitize the survey name to prevent directory traversal
        $survey = basename($slug);

        // Build the path to the YAML file
        $yamlPath = resource_path("surveys-yaml/{$survey}.yaml");

        // Check if file exists
        if (!File::exists($yamlPath)) {
            return response()->json([
                'error' => 'Survey not found',
                'survey' => $survey
            ], 404);
        }

        try {
            // Parse the YAML file with custom tag handler for !include
            $data = $this->parseYamlWithIncludes($yamlPath);

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error parsing survey',
                'message' => $e->getMessage()
            ], 500);
        }
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
        $user = $this->enforceAuth($request, $slug);

        // Guest users manage progress client-side (localStorage)
        if (in_array($slug, self::$guestAllowedSlugs) && !Auth::check()) {
            return response()->json(null);
        }

        $response = SurveyResponse::where('survey_slug', $slug)
            ->where('respondent_id', $user?->id)
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
        $user = $this->enforceAuth($request, $slug);
        /*
        if (!isset(self::$slugToFile[$slug])) {
            abort(404, 'Survey not found');
        }
        */

        $request->validate([
            'response_data' => 'required|array',
            'last_page' => 'nullable|string',
            'finalize' => 'nullable|boolean',
        ]);

        if (in_array($slug, self::$guestAllowedSlugs) && !Auth::check()) {
            return $this->saveGuestResponse($request, $slug);
        }

        $response = SurveyResponse::where('survey_slug', $slug)
            ->where('respondent_id', $user->id)
            ->latest()
            ->first();

        if ($response && $response->finalized_at) {
            throw new AuthorizationException('Cannot update a finalized survey response');
        }

        if ($response && $response->respondent_id !== $user->id) {
            throw new AuthorizationException('Unauthorized to modify this survey response');
        }

        if (!$response) {
            $response = new SurveyResponse();
            $response->survey_slug = $slug;
            $response->respondent_id = $user->id;
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

    /**
     * Parse YAML file and handle !include tags
     *
     * @param string $filePath
     * @return array
     */
    private function parseYamlWithIncludes(string $filePath): array
    {
        $content = File::get($filePath);
        $baseDir = dirname($filePath);

        // Parse with custom tag support
        $data = Yaml::parse($content, Yaml::PARSE_CUSTOM_TAGS);

        // Recursively process the data to handle !include tags
        return $this->processIncludes($data, $baseDir);
    }

    /**
     * Recursively process data structure to resolve !include tags
     *
     * @param mixed $data
     * @param string $baseDir
     * @return mixed
     */
    private function processIncludes($data, string $baseDir)
    {
        if ($data instanceof TaggedValue) {
            // Handle !include tag
            if ($data->getTag() === 'include') {
                $includePath = $data->getValue();
                $fullPath = $baseDir . '/' . $includePath;

                if (!File::exists($fullPath)) {
                    throw new \Exception("Include file not found: {$includePath}");
                }

                // Parse the included file
                $includedContent = File::get($fullPath);
                $includedData = Yaml::parse($includedContent, Yaml::PARSE_CUSTOM_TAGS);

                // Recursively process includes in the included file
                return $this->processIncludes($includedData, dirname($fullPath));
            }
        }

        if (is_array($data)) {
            $result = [];
            foreach ($data as $key => $value) {
                $result[$key] = $this->processIncludes($value, $baseDir);
            }
            return $result;
        }

        return $data;
    }

    private function enforceAuth(Request $request, string $slug)
    {
        $user = auth()->user();
        if (in_array($slug, self::$guestAllowedSlugs))
            return $user;
        if (!$user)
            abort(403, 'Unauthorized');
        return $user;
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
        $response = new SurveyResponse();
        $response->survey_slug = $slug;
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

    private function handleFinalization($slug, SurveyResponse $response)
    {
        if ($slug === 'application.1') {
            $this->finalizeApplication($response);
        } elseif ($slug === 'priorities.1') {
            $this->finalizePriorities($response);
        }
    }

    private function finalizeApplication(SurveyResponse $response)
    {
        if (is_null($response->respondent) || $response->respondent_id === 0) {
            CreateVolunteerFromApplication::dispatchSync($response);
            $response->refresh();
        }

        $this->storePrioritiesFromResponse($response);

        Mail::to($response->email)->send(new ApplicationCompletedMail($response));

        (new \App\Actions\Reports\ApplicationReportRowCreate)->handleJson($response);
    }

    private function finalizePriorities(SurveyResponse $response)
    {
        $this->storePrioritiesFromResponse($response);
    }

    private function storePrioritiesFromResponse(SurveyResponse $response)
    {
        if (!$response->curation_activity_1) {
            return;
        }

        // TODO: consider wrapping in transaction
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

}
