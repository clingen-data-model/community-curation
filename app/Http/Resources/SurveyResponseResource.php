<?php

namespace App\Http\Resources;

use App\SurveyJsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        if ($this->resource instanceof SurveyJsonResponse) {
            return $this->transformJsonResponse();
        }

        return $this->transformLegacyResponse();
    }

    private function transformLegacyResponse()
    {
        $doc = $this->survey->document;
        $questions = $doc->getQuestions();
        $data = [];
        foreach ($questions as $question) {
            $data[$question->name] = [
                'variable' => $question->name,
                'questionText' => trim($question->questionText),
                'rawValue' => $this->{$question->name},
                'value' => $this->{$question->name},
            ];

            if ($question->hasOptions()) {
                $options = collect($question->getOptionsForResponseValue($this->{$question->name}))
                            ->transform(function ($option) {
                                return $option->label;
                            });
                $data[$question->name]['value'] = $options;
            }
        }

        return $data;
    }

    private function transformJsonResponse()
    {
        $definition = $this->loadDefinition($this->resource->survey_slug);
        $questions = $this->extractQuestions($definition);
        $responseData = $this->resource->response_data ?? [];
        $data = [];

        foreach ($questions as $question) {
            $name = $question['name'];
            if ($question['type'] === 'html') {
                continue;
            }

            $rawValue = $responseData[$name] ?? null;
            $value = $rawValue;

            if (isset($question['choices']) && !is_null($rawValue)) {
                $choiceMap = collect($question['choices'])->keyBy('value');

                if (is_array($rawValue)) {
                    $value = collect($rawValue)->map(function ($v) use ($choiceMap) {
                        $choice = $choiceMap->get($v);
                        return $choice ? $choice['text'] : $v;
                    })->values()->all();
                } else {
                    $choice = $choiceMap->get($rawValue);
                    $value = $choice ? $choice['text'] : $rawValue;
                }
            }

            $data[$name] = [
                'variable' => $name,
                'questionText' => $question['title'] ?? $name,
                'rawValue' => $rawValue,
                'value' => $value,
            ];
        }

        return $data;
    }

    private function loadDefinition($slug)
    {
        $slugToFile = [
            'volunteer-three-month1' => 'volunteer-three-month',
            'volunteer-six-month1' => 'volunteer-six-month',
        ];

        $file = $slugToFile[$slug] ?? null;
        if (!$file) {
            return null;
        }

        $path = resource_path("surveys/json/{$file}.json");
        if (!file_exists($path)) {
            return null;
        }

        return json_decode(file_get_contents($path), true);
    }

    private function extractQuestions($definition)
    {
        $questions = [];
        if (!$definition || !isset($definition['pages'])) {
            return $questions;
        }

        foreach ($definition['pages'] as $page) {
            if (isset($page['elements'])) {
                foreach ($page['elements'] as $element) {
                    $questions[] = $element;
                }
            }
        }

        return $questions;
    }
}
