<?php

namespace Database\Seeders;

use App\Faq;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faqs = [
            'How do I get my login credentials?' => 'Your login credentials will be sent to you by email.',
            'What do I do if I forgot my password?' => 'To the right of the login button there is a button that says “Forgot Your Password?”. Click on this to reset.',
            'How do I edit my first or last name?' => 'Go to the “Basic Information” section under the “Summary”  tab. On the upper right section, there is a button labeled “Edit”. A window will pop up and you can input your updated first or last name.',
            'How do I change my email that is on file?' => 'Go to the “Basic Information” section under the “Summary”  tab. On the upper right section, there is a button labeled “Edit”. A window will pop up and you can input your updated email address.',
            'How do I add my ORCID ID?' => 'Go to the “Basic Information” section under the “Summary”  tab. On the upper right section, there is a button labeled “Edit”. A window will pop up and you can input your ORCID ID.',
            'How do I add my hypothes.is username?' => 'Go to the “Basic Information” section under the “Summary”  tab. On the upper right section, there is a button labeled “Edit”. A window will pop up and you can input your hypothes.is username.',
            'How do I add or change my institution?' => 'Go to the “Basic Information” section under the “Summary”  tab. On the upper right section, there is a button labeled “Edit”. A window will pop up and you can input your current institution.',
            'How do I add or change my address?' => 'Go to the “Basic Information” section under the “Summary”  tab. On the upper right section, there is a button labeled “Edit”. A window will pop up and you can input your current address.',
            'How do I add a CV, resume, photo, certification of biocurator training, certification of biocurator trainer status, variant curation training logbook or additional document to my dashboard?' => 'Go to the “Documents” tab and click on the “Add Documents” button on the right hand side. Add your file by clicking on “Choose File”. Add the name, category and any notes in the spaces below.',
            'How do I know what curation effort I prioritized first?' => 'On the left hand side of the Summary tab, under “Curation Effort”.',
            'How do I change the curation effort that I prioritized?' => 'Go to the priorities tab, in the bottom left corner click on “Set New Priorities”. In the window that pops up change the curation effort under “First Choice” to your new prioritized curation effort. Click “Finish and Finalize”.',
            'How do I change or add an Curation Group that I am interested in to my  priorities?' => 'Go to the “Priorities” tab, in the bottom left corner click on “Set New Priorities”. In the window that pops up change the Curation Group under “First Choice” to your new prioritized Curation Group. Click “Finish and Finalize”.',
            'How do I indicate that I am willing to volunteer for ClinGen outside of the curation efforts and Curation Groups that I prioritized?' => 'Go to the “Priorities” tab, in the bottom left corner click on “Set New Priorities”. In the window that pops up change the last question to “Yes - I am willing to volunteer with any available ClinGen group- I am willing to volunteer with any available ClinGen group. Click “Finish and Finalize”.',
            'How do I know if I have completed the training?' => 'On the “Trainings” tab, under “Date Completed” the date you attended the live training will be listed. If you have not yet attended a live training it will say “incomplete”.',
            'Who do I contact about completing the training?' => 'The training is not assigned or completed through the Community Curation Database, you will be contacted via email to schedule and complete the training.',
            'How do I sign the attestation after completing training?' => 'On the left hand side of the “Summary” tab click on the button under “Panel/Gene” that says “Sign Attestation”. If this button is not there your training may not be complete, contact volunteer@clinicalgenome.org if you believe that you have already completed all the training.',
            'How do I change my survey data?' => 'The data in the “Survey Data” tab cannot be changed. Any other tabs that display data from the survey can be changed but this will not alter the data in the “Survey Data” tab.',
        ];

        DB::table('faqs')->truncate();

        foreach ($faqs as $question => $answer) {
            Faq::create([
                'question' => $question,
                'answer' => $answer,
            ]);
        }
    }
}
