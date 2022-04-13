<?php

namespace App\Actions;

use App\User;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsCommand;
use App\Actions\TrainingCertificateGenerate;

ini_set('memory_limit', '512M');
class TrainingCertificateGenerateForExisting
{
    use AsCommand;

    public $commandSignature = 'volunteers:training:generate-certificates-for-existing {--limit= : # of volunteers} {--offset= : Offset at which to begin } {--dry-run : Print certificate params; don\'t actually generate certificates.} {--dry-run-quiet : print count of effected volunteers; don\'t generate certs.}';
    
    private $limit = null;
    private $offset = null;
    private $dryRun = false;
    private $dryRunQuiet = false;

    public function __construct(private TrainingCertificateGenerate $certGen)
    {
    }
    

    public function handle(): void
    {
        $query = User::with([
                'userAptitudes',
                'userAptitudes.aptitude',
                'userAptitudes.aptitude.subject'
            ])
            ->isTrained();

        if ($this->limit) {
            $query->limit($this->limit);
        }

        if ($this->offset) {
            $query->offset($this->offset);
        }

        $query->get()
            ->each(function ($volunteer) {
                $volunteer->userAptitudes->each(function ($userApt) use ($volunteer) {
                    if (!$userApt->trained_at) {
                        return;
                    }
                    if ($this->dryRun) {
                        $this->dumpCertParams($volunteer, $userApt);
                        return;
                    }
                    $this->generateCert($volunteer, $userApt);
                });
            });
    }
    
    public function asCommand(Command $command)
    {
        $this->setLimit($command->option('limit'))
            ->setOffset($command->option('offset'))
            ->setDryRun($command->option('dry-run'))
            ->setDryRunQuiet($command->option('dry-run-quiet'))
            ->handle();
    }

    private function setLimit($limit)
    {
        $this->limit = ($limit > 0) ? $limit : null;
        return $this;
    }

    private function setOffset($offset)
    {
        $this->offset = ($offset > 0) ? $offset : null;

        return $this;
    }
    

    private function setDryRun($dryRun)
    {
        $this->dryRun = $dryRun;
        return $this;
    }
    
    private function setDryRunQuiet($dryRunQuiet)
    {
        $this->dryRunQuiet = $dryRunQuiet;
        return $this;
    }
    
    

    private function generateCert($volunteer, $userApt)
    {
        app()->make(TrainingCertificateGenerate::class)->handle(
            user: $volunteer,
            date: $userApt->trained_at,
            type: Str::kebab($userApt->aptitude->subject->name)
        );
    }
    
    private function dumpCertParams($volunteer, $userApt): void
    {
        dump([
            'user' => $volunteer->name,
            'date' => $userApt->trained_at->format('Y-m-d'),
            'type' => Str::kebab($userApt->aptitude->subject->name)
        ]);
    }
    
    
}
