<?php

namespace App\Surveys;

use App\SurveyJsonResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ResponseReader
{
    private static $slugToFile = [
        'volunteer-three-month1' => 'volunteer-three-month',
        'volunteer-six-month1' => 'volunteer-six-month',
    ];

    public function __construct()
    {
    }

    public function resolveReadable(Model $model)
    {
        if ($model instanceof SurveyJsonResponse) {
            return $this->resolveJsonReadable($model);
        }

        return $this->resolveLegacyReadable($model);
    }

    public function resolveResponseArray(Model $model): array
    {
        $structured = $this->resolveReadable($model);
        $returnValue = [
            'name' => $model->volunteer->name,
            'email' => $model->volunteer->email,
            'date' => $model->finalized_at
        ];
        foreach ($structured as $key => $value) {
            $resolvedValue = null;
            if (!is_null($value['value'])) {
                $resolvedValue = $value['value'];
                if (is_object($value['value']) && ($value['value'] instanceof Collection)) {
                    $resolvedValue = $value['value']->join(', ');
                }
                if (is_array($value['value'])) {
                    $resolvedValue = implode(', ', $value['value']);
                }
            }
            $returnValue[$key] = $resolvedValue;
        }
        return $returnValue;
    }

    private function resolveLegacyReadable(Model $model)
    {
        $doc = $model->survey->document;
        $questions = $doc->getQuestions();
        $data = [];
        foreach ($questions as $question) {
            $data[$question->name] = [
                'variable' => $question->name,
                'questionText' => trim($question->questionText),
                'rawValue' => $model->{$question->name},
                'value' => $model->{$question->name},
            ];

            if ($question->hasOptions()) {
                $options = collect($question->getOptionsForResponseValue($model->{$question->name}))
                            ->transform(function ($option) {
                                return $option->label;
                            });
                $data[$question->name]['value'] = $options;
            }
        }

        return $data;
    }

    private function resolveJsonReadable(SurveyJsonResponse $model)
    {
        $definition = $this->loadDefinition($model->survey_slug);
        $questions = $this->extractQuestions($definition);
        $responseData = $model->response_data ?? [];
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
