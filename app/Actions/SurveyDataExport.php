<?php

namespace App\Actions;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Surveys\ResponseReader;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\AsController;

class SurveyDataExport
{
    use AsCommand;
    use AsController;

    public $commandSignature = 'surveys:export-data {modelClass} {--limit= : number of rows.}';

    public function __construct(private ResponseReader $reader)
    {
    }
    

    public function handle($class, $limit = null)
    {
        $responses = $class::with('volunteer')->get();
        $rows = $this->getRows($responses);

        
        $filepath = storage_path('app/reports/'.Str::snake(preg_replace('/\\\/', '', $class)).'_'.Carbon::now()->format('Y-m-d').'.csv');
        $fh = fopen($filepath, 'w');
        
        if (count($rows) == 0) {
            fputcsv($fh, ['No followup survey data to export.']);
            return $filepath;
        }
        
        fputcsv($fh, array_keys($rows[0]));
        foreach ($rows as $idx => $row) {
            if ($limit && $idx+1 > $limit) {
                break;
            }
            fputcsv($fh, $row);
        }
        fclose($fh);

        return $filepath;
    }

    public function asController(ActionRequest $request)
    {
        $filepath = $this->handle($request->class);

        $pathparts = explode('/', $filepath);
        $filename = array_pop($pathparts);

        return response()->download($filepath, $filename);
    }
    

    public function asCommand(Command $command)
    {
        $filepath = $this->handle($command->argument('modelClass'), $command->option('limit'));

        $command->info('Exported to '.$filepath);
    }

    private function getRows(Collection $responses)
    {
        return $responses->map(function ($rsp) {
            return $this->reader->resolveResponseArray($rsp);
        });
    }
    
        
}
