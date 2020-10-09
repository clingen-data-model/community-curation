<?php

namespace App\Http\Resources;

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
        $doc = $this->survey->document;
        $questions = $doc->getQuestions();
        // dd($questions);
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
}
