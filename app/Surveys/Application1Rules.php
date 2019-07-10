<?php 
namespace App\Surveys;

use Sirs\Surveys\SurveyRules;

class Application1Rules extends SurveyRules
{
    /** 
    Rules stub.  Will add more as we flesh this out

    format:
        public function PAGETITLEBeforeShow() {}
        public function PAGETITLEBeforeSave() {}
        public function PAGETITLEAfterSave() {}
        public function PAGETITLESkip() {}
        public function PAGAETITLEGetValidator() {}
        public function getRedirectUrl() {}
        public function navigate() {} // should return page number;

    Known Page Titles:
        Introduction
    */

    /**
     * lifecycle hook called before any page in the survey is shown.
     * @return array Context associative array to be passed to view.
     */
    public function beforeShow()
    {
        $context = [];
        return $context;
    }

    /**
     * lifecycle hood called before save when any page is submitted
     * @return void
     */
    public function beforeSave()
    {
        # logic to run before save
    }

    /**
     * lifecycle hood called after save when any page is submitted
     * @return void
     */
    public function afterSave()
    {
        # logic to run after save
    }

    /**
     * do custom navigation
     * @param  array $params ['page'=>'pageName', 'nav'=>'navType']
     * @return mixed int page number to navigate to or null for derfault navigation.
     */
    public function navigate($params)
    {
        return null;
    }

}