<?php

namespace App\Import\SheetHandlers;

use App\Goal;
use App\Campaign;
use App\CurationActivity;
use App\CurationGroup;
use App\Interest;
use App\Motivation;
use App\VolunteerType;
use App\SelfDescription;
use Illuminate\Support\Collection;
use Box\Spout\Reader\SheetInterface;
use App\Import\Contracts\SheetHandler;
use App\Surveys\SurveyOptions;

class ApplicationSurveyHandler extends AbstractSheetHandler implements SheetHandler
{
    /**
     * @var array $rowKeys
     */
    private $rowKeys = [
        'created_at',                   // A
        'name',                         // B
        'institution',                  // C
        'address',                      // D
        'email',                        // E
        'self_desc',                    // F
        'ad_campaign',                  // G
        'volunteer_type',               // H
        'curation_activity_1',          // I
        'curation_activity_2',          // J
        'curation_activity_3',          // K
        'curation_activity_4',          // L
        'curation_activity_5',          // M
        'activity_experience_1_detail', // N
        'additional_priority',          // O
        'panel_1',                      // P
        'empty',                        // Q
        'outside_panel',                // R
        'goals_other_detail',           // S
        'interests',                    // T
        'motivation',                   // U
        'highest_ed',                   // V
        'adv_cert',                     // W
        'timezone',                     // X
        'notes'                         // Y
    ];
    protected $selfDescriptions;

    protected $interests;

    protected $motivation;

    protected $goals;
    protected $campaigns;
    protected $motivations;

    public function __construct()
    {
        $this->selfDescriptions = SelfDescription::all();
        $this->interests = Interest::all();
        $this->motivations = Motivation::all();
        $this->goals = Goal::all();
        $this->campaigns = Campaign::all();
        $this->curationGroups = CurationGroup::all();
        $this->curationActivities = CurationActivity::all();
        $this->highestEd = Collect([
            (object)[
                'id' => 1,
                'name' => 'Some high school education'
            ],
            (object)[
                'id' => 2,
                'name' => 'High school diploma'
            ],
            (object)[
                'id' => 3,
                'name' => 'Bachelor\'s degree'
            ],
            (object)[
                'id' => 4,
                'name' => 'Master\'s degree'
            ],
            (object)[
                'id' => 5,
                'name' => 'M.D.'
            ],
            (object)[
                'id' => 6,
                'name' => 'Ph.D.'
            ],
            (object)[
                'id' => 100,
                'name' => 'Other'
            ]]
        );

        $this->outsidePanelOptions = collect([
            (object)[
                'id' => 1,
                'name' => 'Yes - I am willing to volunteer with any available ClinGen group'
            ],
            (object)[
                'id' => 0,
                'name' => 'No - I am only interested in the group(s) I previously indicate'
            ],
            (object)[
                'id' => 2,
                'name' => 'Maybe - please contact me with other options, and I will decide based on what is available'
            ],
        ]);
        $this->additionalPriority = Collect([
            (object)[
                'id' => 1,
                'name' => 'Yes'
            ],
            (object)[
                'id' => 0,
                'name' => 'No'
            ],
            (object)[
                'id' => 2,
                'name' => 'Possibly'
            ],
        ]);
        $this->surveyOptions = new SurveyOptions();
    }

    

    public function handle(SheetInterface $sheet):array
    {
        if ($sheet->getName() != 'Volunteer Survey') {
            return parent::handle($sheet);
        }

        $rows = [];
        foreach ($sheet->getRowIterator() as $rowNum => $rowObj) {
            if ($rowNum == 1) {
                continue;
            }
            $rowValues = arrayTrimStrings(rowToArray($rowObj));
            $row = array_combine($this->rowKeys, array_pad(array_slice($rowValues, 0, 25), 25, null));

            $row = $this->transcribeRowData($row);

            $rows[] = $row;
        }
        $rows = collect($rows)->groupBy('email')->toArray();
        return $rows;
    }

    private function transcribeRowData($row)
    {
        $data = collect($row)->except(['empty', 'address','volunteer_type', 'curation_activity_4', 'curation_activity_5']);
        
        $nameParts = parseName($row['name']);
        $data['email'] = trim(preg_replace('/\"/', '', $data['email']));
        $data['first_name'] = array_shift($nameParts);
        $data['last_name'] = implode(" ", $nameParts);

        $data['timezone'] = $this->getSingleId($row['timezone'], collect($this->surveyOptions->timezones()));
        $data['volunteer_type'] = $this->getSingleId(strtolower($row['volunteer_type']), VolunteerType::all());
        $data['self_desc'] = $this->getSingleId($row['self_desc'], $this->selfDescriptions);
        $data['self_desc_other'] = $this->getOtherStringForSingle($row['self_desc'], $this->selfDescriptions);
        $data['ad_campaign'] = $this->getMultipleIds($row['ad_campaign'], $this->campaigns)->toJson();
        $data['ad_campaign_other_detail'] = $this->getOtherStringForMultiple($row['ad_campaign'], $this->campaigns);
        $data['interests'] = $this->getMultipleIds($row['interests'], $this->interests)->toJson();
        $data['motivation'] = $this->getMultipleIds($row['motivation'], $this->motivations)->toJson();
        $data['motivation_other_detail'] = $this->getOtherStringForMultiple($row['motivation'], $this->motivations);
        $data['outside_panel'] = $this->getSingleId($row['outside_panel'], $this->outsidePanelOptions);
        $data['highest_ed'] = $this->getSingleId($row['highest_ed'], $this->highestEd);
        $data['highest_ed_other'] = $this->getOtherStringForSingle($row['highest_ed'], $this->highestEd);

        $data['curation_activity_1'] = $this->getSingleId($row['curation_activity_1'], $this->curationActivities, 'legacy_name');
        $data['curation_activity_2'] = $this->getSingleId($row['curation_activity_2'], $this->curationActivities, 'legacy_name');
        $data['curation_activity_3'] = $this->getSingleId($row['curation_activity_3'], $this->curationActivities, 'legacy_name');
        
        $data['additional_priority'] = $this->getSingleId($row['additional_priority'], $this->additionalPriority);
        $data['panel_1'] = $this->getSingleId($row['panel_1'], $this->curationGroups);
        $data['goals'] = '[]';
        $data['street1'] = $row['address'];
        $data['imported_survey_data'] = json_encode($row);
        
        return $data;
    }

    private function getSingleId($value, Collection $options, $attribute = 'name')
    {
        return ($options->firstWhere($attribute, $value)) ? $options->firstWhere($attribute, $value)->id : null;
    }
    
    private function getMultipleIds($value, Collection $options)
    {
        $selected = explode(', ', $value);
        $ids = $options->filter(function ($option) use ($selected) {
                    return in_array($option->name, $selected);
                })->pluck('id');
        
        return $ids;
    }
    
    private function getOtherStringForSingle($value, $options)
    {
        if (!$options->pluck('name')->contains($value)) {
            return $value;
        }

        return null;
    }
    
    private function getOtherStringForMultiple($value, $options)
    {
        $responses = explode(', ', $value);

        $otherResponses = [];
        foreach ($responses as $response) {
            if ($options->pluck('name')->contains($response)) {
                continue;
            }
            array_push($otherResponses, $response);
        }

        return implode('; ', $otherResponses);
    }
    
    
    
}
