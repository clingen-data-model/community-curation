<?php

namespace App\Actions\Reports;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use App\Services\Reports\ApplicationReportWriter;
use App\Services\Reports\ApplicationReportGenerator;

final class ApplicationReportBuild
{
    public function __construct(
        private ApplicationReportGenerator $generator,
        private ApplicationReportWriter $writer
    ) {
    }

    public function handle(array $params): string {
      $filePath = storage_path('app/reports/application-report-'.Carbon::now()->format('Y-m-d_H:i:s').'.xlsx');

      if (isset($params['start_date'])) {
          $this->generator
              ->addConstraint(function ($q) use ($params) {
                  $q->where('finalized_at', '>=', $params['start_date']);
              });
      }

      if (isset($params['end_date'])) {
          $this->generator
              ->addConstraint(function ($q) use ($params) {
                  $q->where('finalized_at', '<=', $params['end_date']);
              });
      }

      $data = $this->generator->generate($params);

      if ($data->count() == 0) {
          session()->flash('warning', 'Nothing matched your filters.');

          return redirect()->back();
      }

      $this->writer
          ->setPath($filePath)
          ->writeData($data)
          ->addMetadata($this->getMetadata($params))
          ->closeWriter();

      return $filePath;
    }

    private function getMetadata($filterParams): Collection
    {
        $metadata = collect();
        if (count($filterParams) == 0) {
            $metadata->push(collect(['filter' => '', 'value' => '']));

            return $metadata;
        }
        foreach ($filterParams as $key => $value) {
            if ($key == 'page') {
                continue;
            }

            $label = $key;
            $valueLabel = $value;

            if (substr($key, -3) === '_id') {
                $label = ucfirst(str_replace('_', ' ', (preg_replace('/_id$/', '', $key))));
                $model = 'App\\'.ucfirst(Str::camel(preg_replace('/_id$/', '', $key)));
                $valueLabel = 'unassigned';

                if ($value != -1) {
                    $instance = $model::find($value);
                    $valueLabel = ($instance) ? $instance->name : '?';
                }
            }

            $metadata->push(collect(['filter' => $label, 'value' => $valueLabel]));
        }

        return $metadata;
    }
}
