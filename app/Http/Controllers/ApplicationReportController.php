<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\Reports\ApplicationReportWriter;
use App\Services\Reports\ApplicationReportGenerator;

class ApplicationReportController extends Controller
{
    protected $generator;

    protected $writer;

    public function __construct(ApplicationReportGenerator $generator, ApplicationReportWriter $writer)
    {
        $this->generator = $generator;
        $this->writer = $writer;
    }

    public function index(Request $request)
    {
        $filePath = storage_path('app/reports/application-report-'.Carbon::now()->format('Y-m-d_H:i:s').'.xlsx');
       
        if ($request->start_date) {
            $this->generator
                ->addConstraint(function ($q) use ($request) {
                    $q->where('finalized_at', '>=', $request->start_date);
                });
        }

        if ($request->end_date) {
            $this->generator
                ->addConstraint(function ($q) use ($request) {
                    $q->where('finalized_at', '<=', $request->end_date);
                });
        }

        $filterParams = $request->all();
        $data = $this->generator->generate($filterParams);

        $data->put('metadata', $this->getMetadata($filterParams));

        if (!$data->has('personal')) {
            session()->flash('warning', 'Nothing matched your filters.');
            return redirect()->back();
        }

        $this->writer
            ->setPath($filePath)
            ->writeData($data);

        
        return response()
            ->download($filePath)
            ->deleteFileAfterSend();
    }

    private function getMetadata($filterParams)
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
