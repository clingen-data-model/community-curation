<?php

namespace App\Import\Maps;

use App\CurationGroup;
use App\Import\Exceptions\ImportException;
use InvalidArgumentException;

class CurationGroupMap
{
    private $map = [
        'ACADVL' => 'ACADVL VCEP',
        'Actionability' => 'Actionability-Adult/Pediatric',
        'Aminoacidopathy' => 'Aminoacidopathy GCEP',
        'Brain Malformations' => ['Brain Malformations GCEP', 'Brain Malformations VCEP'],
        'Cardiomyopathy' => 'Cardiomyopathy VCEP',
        'CDH1' => 'CDH1 VCEP',
        'Coagulation Factor Deficiency' => 'Coagulation Factor Deficiencies VCEP',
        'Dilated Cardiomyopathy' => 'Dilated Cardiomyopathy GCEP',
        'Epilepsy' => 'Epilepsy GCEP',
        'Hearing Loss' => 'Hearing Loss VCEP',
        'Hemo/Thrombo' => 'Hemostasis/ Thrombosis GCEP',
        'Hematological Cancer taskforce' => null,
        'hematological cancer taskforce' => null,
        'Hereditary Cancer' => 'Hereditary Cancer GCEP',
        'Genitourinary cancer taskforce' => null,
        'ID Autism' => 'Intellectual Disability and Autism GCEP',
        'ID/Autism' => 'Intellectual Disability and Autism GCEP',
        'ID- Autism' => 'Intellectual Disability and Autism GCEP',
        'Intellectual Disability and Autism' => 'Intellectual Disability and Autism GCEP',
        'Mito' => 'Mitochondrial Diseases VCEP',
        'LGMD VCEP' => 'Limb Girdle Muscular Dystrophy VCEP',
        'LGMD GCEP' => 'Limb Girdle Muscular Dystrophy GCEP',
        'Mitochondrial GCEP' => 'Mitochondrial Diseases GCEP',
        'Monogenic Diabetes' => ['Monogenic Diabetes GCEP', 'Monogenic Diabetes VCEP'],
        'Neurodevelopmental Dosage' => 'Dosage-Neurodevelopmental',
        'PAH' => 'PAH VCEP',
        'pancreatic cancer taskforce' => null,
        'Pediatric cancer taskforce' => null,
        'Pancreatic cancer taskforce' => null,
        'pediatric cancer taskforce' => null,
        'pediatric and pancreatic cancer taskforces' => null,
        'Pediatric cancer' => null,
        'Pediatric cancer and Hematological cancer taskforce' => null,
        'Phenylketonuria' => 'Phenylketonuria VCEP',
        'Platelet Disorders' => 'Platelet Disorders VCEP',
        'Platelet Disorder' => 'Platelet Disorders VCEP',
        'PTEN' => 'PTEN VCEP',
        'RASopathy' => 'RASopathy VCEP',
        'Recurrent CNVs' => null,
        'RYR1' => 'RYR1 VCEP',
        'Somatic WG' => null,
        'Storage Disease' => null,
        'TP53' => 'TP53 VCEP',
    ];
    protected $curationGroups;

    public function __construct()
    {
        $this->curationGroups = CurationGroup::all()->keyBy('name');
        // dd($this->curationGroups->keys());
    }



    public function map($input)
    {
        $ep = $this->curationGroups->get($input);
        if ($ep) {
            return $ep;
        }

        if (!isset($this->map[$input])) {
            throw new ImportException('EP Uknown: "'.$input.'"', 404);
        }

        if (is_null($this->map[$input])) {
            throw new ImportException('EP Unmappable: '.$input.' does not map to a known EP', 404);
        }

        if (is_array($this->map[$input])) {
            throw new ImportException('EP Ambiguous: "'.$input.'" could map to '.implode(', ', $this->map[$input]), 409);
        }
        
        return $this->curationGroups->get($this->map[$input]);
    }

    public function mapAbiguous($ep, $ca)
    {
        $suffix = null;
        switch ($ca) {
            case 'Variant Pathogenicity':
                $suffix = ' VCEP';
                break;
            case 'Gene Disease Validity':
                $suffix = ' GCEP';
                break;
            default:
                throw new ImportException('Can not map ambiguous curation group name for curatio activity "'.$ca.'"');
        }

        return $this->map($ep.$suffix);
    }
}
