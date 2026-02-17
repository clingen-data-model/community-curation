<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\SurveyResponse;
use App\Surveys\SurveyOptions;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Tag\TaggedValue;

class SurveyController extends Controller
{
    /**
     * Get a survey definition from YAML and return as JSON. Handles !include directives to allow modular YAML files.
     *
     * @param string $survey The survey name (without .yaml extension)
     * @return JsonResponse
     */
    public function definition(Request $request, $slug)
    {
        $user = $this->authUnlessInitialApplication($request, $slug);
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
            default:
                abort(404, 'Unknown choice type');
        }
    }

    public function getResponse(Request $request, $slug)
    {
        $user = $this->authUnlessInitialApplication($request, $slug);
        $response = SurveyResponse::where('survey_slug', $slug)
            ->where('respondent_id', $user->id)
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
        $user = $this->authUnlessInitialApplication($request, $slug);

        $request->validate([
            'response_data' => 'required|array',
            'last_page' => 'nullable|string',
            'finalize' => 'nullable|boolean',
        ]);

        $response = SurveyResponse::where('survey_slug', $slug)
            ->where('respondent_id', $user->id)
            ->whereNull('finalized_at')
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

    private function authUnlessInitialApplication(Request $request, string $slug)
    {
        if ($slug === 'application.1')
            return null;
        $user = auth()->user() ?? abort(403, 'Unauthorized');
        return $user;
    }
}
