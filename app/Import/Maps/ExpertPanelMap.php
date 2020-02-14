<?php

namespace App\Import\Maps;

use App\ExpertPanel;
use App\Import\Exceptions\ImportException;
use InvalidArgumentException;

class ExpertPanelMap
{
    private $map = [
        'ACADVL' => 'ACADVL VCEP',
        'Actionability' => 'Actionability-Pediatric',
        'Aminoacidopathy' => null,
        'Brain Malformations' => ['Brain Malformations GCEP', 'Brain Malformations VCEP'],
        'Cardiomyopathy' => 'Cardiomyopathy VCEP',
        'Coagulation Factor Deficiency' => 'Coagulation Factor Deficiencies VCEP',
        'Dilated Cardiomyopathy' => null,
        'Epilepsy' => null,
        'Epilepsy ' => null,
        'Epilepsy GCEP' => null,
        'Hearing Loss' => 'Hearing Loss VCEP',
        'Hemo/Thrombo' => 'Hemostasis/ Thrombosis GCEP',
        'Hematological Cancer taskforce' => null,    
        'hematological cancer taskforce' => null,
        'Hereditary Cancer' => 'Hereditary Cancer GCEP',
        'Genitourinary cancer taskforce' => '',
        'ID Autism' => 'Intellectual Disability and Autism GCEP',
        'ID/Autism' => 'Intellectual Disability and Autism GCEP',
        'Mito' => 'Mitochondrial Diseases GCEP',
        'Mitochondrial GCEP' => 'Mitochondrial Diseases GCEP',
        'Monogenic Diabetes' => ['Monogenic Diabetes GCEP', 'Monogenic Diabetes VCEP'],
        'PAH' => null,
        'pancreatic cancer taskforce' => null,
        'Pediatric cancer taskforce' => null,
        'Pancreatic cancer taskforce' => null,
        'pediatric cancer taskforce' => null,
        'pediatric and pancreatic cancer taskforces' => null,
        'Pediatric cancer' => null,
        'Pediatric cancer and Hematological cancer taskforce' => null,
        'Platelet Disorders' => 'Platelet Disorders VCEP',
        'RASopathy' => 'RASopathy VCEP',
        'Recurrent CNVs' => null,
        'RYR1' => null,
        'Somatic WG' => null,
        'Storage Disease' => null,
        'TP53' => 'TP53 VCEP',
    ];
    protected $expertPanels;

    public function __construct()
    {
        $this->expertPanels = ExpertPanel::all()->keyBy('name');
    }



    public function map($input)
    {

        $ep = $this->expertPanels->get($input);
        if ($ep) {
            return $ep;
        }

        if (!isset($this->map[$input])) {
            throw new ImportException('EP Uknown: "'.$input.'"');
        }

        if (is_null($this->map[$input])) {
            throw new ImportException('EP Unmappable: '.$input.' does not map to a known EP');
        }

        if (is_array($this->map[$input])) {
            throw new ImportException('EP Ambiguous: "'.$input.'" could map to '.implode(', ', $this->map[$input]));
        }
        
        return $this->expertPanels->get($this->map[$input]);

    }
    
}
