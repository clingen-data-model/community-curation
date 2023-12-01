<?php

namespace App\Surveys;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ResponseReader
{
    public function __construct()
    {
    }

    public function resolveReadable(Model $model)
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

    public function resolveResponseArray(Model $model): array
    {
        $structured = $this->resolveReadable($model);
        $returnValue = [
            'name' => $model->volunteer->name,
            'email' => $model->volunteer->email,
            'date' => $model->finalized_at,
        ];
        foreach ($structured as $key => $value) {
            $resolvedValue = null;
            if (! is_null($value['value'])) {
                $resolvedValue = $value['value'];
                if (is_object($value['value']) && ($value['value'] instanceof Collection)) {
                    $resolvedValue = $value['value']->join(', ');
                }
            }
            $returnValue[$key] = $resolvedValue;
        }

        return $returnValue;
    }
}
