<?php

namespace App\Http\Controllers\Api;

use App\CurationGroup;
use App\Exceptions\NotImplementedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomTrainingEmailRequest;
use App\Http\Requests\TrainingSessionAttendeeInviteRequest;
use App\Http\Resources\AvailableTraineesResource;
use App\Http\Resources\TrainingSessionAttendeeResource;
use App\Jobs\InviteVolunteersToTrainingSession;
use App\Notifications\CustomTrainingEmail;
use App\Notifications\ValueObjects\MailAttachment;
use App\TrainingSession;
use App\User;
use League\HTMLToMarkdown\HtmlConverter;
use Parsedown;

class TrainingSessionAttendeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($trainingSessionId)
    {
        $trainingSession = TrainingSession::findOrFail($trainingSessionId);
        $attendees = $trainingSession->attendees()->with([
            'assignments' => function ($q) use ($trainingSession) {
                $q->assignableIs($trainingSession->topic_type, $trainingSession->topic_id)
                    ->select('created_at as date_assigned', 'user_id', 'id');
            },
            'assignments.userAptitudes',
        ])
            ->select('first_name', 'last_name', 'users.id', 'email', 'already_clingen_member', 'already_member_cgs')
            ->get();
        $alreadyMemberCgIds = $attendees->pluck('already_member_cgs')->flatten()->unique()->sort();
        $curationGroups = CurationGroup::find($alreadyMemberCgIds);

        $attendees = $attendees->map(function ($a) use ($curationGroups) {
            if (! $a->already_member_cgs) {
                $a->already_member_groups = null;

                return $a;
            }

            $a->already_member_groups = $curationGroups->whereIn('id', $a->already_member_cgs);

            return $a;
        });

        return TrainingSessionAttendeeResource::collection($attendees);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TrainingSessionAttendeeInviteRequest $request, $trainingSessionId)
    {
        $trainingSession = TrainingSession::findOrFail($trainingSessionId);
        $volunteers = User::find($request->attendee_ids);
        InviteVolunteersToTrainingSession::dispatch($trainingSession, $volunteers);

        $attendees = $trainingSession->attendees()->with([
            'assignments' => function ($q) use ($trainingSession) {
                $q->assignableIs($trainingSession->topic_type, $trainingSession->topic_id)
                    ->select('created_at as date_assigned', 'user_id', 'id');
            },
            'assignments.userAptitudes',
        ])
            ->select('first_name', 'last_name', 'users.id', 'email', 'already_clingen_member', 'already_member_cgs')
            ->get();

        return TrainingSessionAttendeeResource::collection($attendees);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return new NotImplementedException();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($trainingSessionId, $userId)
    {
        $trainingSession = TrainingSession::findOrFail($trainingSessionId);

        $trainingSession->attendees()->detach($userId);
    }

    public function trainableVolunteers($trainingSessionId)
    {
        $trainingSession = TrainingSession::findOrFail($trainingSessionId);
        $volunteerQuery = User::isVolunteer()
            ->select('first_name', 'last_name', 'id', 'volunteer_status_id', 'email', 'already_clingen_member', 'already_member_cgs')
            ->whereNotIn('id', $trainingSession->attendees->pluck('id'))
            ->whereHas('userAptitudes', function ($q) use ($trainingSession) {
                $q->needsTraining()
                    ->whereHas('aptitude', function ($qu) use ($trainingSession) {
                        $qu->forSubject($trainingSession->topic_type, $trainingSession->topic_id);
                    });
            })
            ->with([
                'assignments' => function ($q) use ($trainingSession) {
                    $q->assignableIs($trainingSession->topic_type, $trainingSession->topic_id)
                        ->select('created_at as date_assigned', 'user_id', 'id', 'assignable_type', 'assignable_id', 'assignment_status_id');
                },
                'assignments.status',
                'volunteerStatus',
            ])
            ->withCount([
                'trainingSessions' => function ($q) use ($trainingSession) {
                    $q->topicIs($trainingSession->topic)
                        ->past();
                },
            ]);

        $volunteers = $volunteerQuery->get();

        $alreadyMemberCgIds = $volunteers->pluck('already_member_cgs')->flatten()->unique()->sort();
        $curationGroups = CurationGroup::find($alreadyMemberCgIds);

        $volunteers = $volunteers->map(function ($v) use ($curationGroups) {
            if (! $v->already_member_cgs) {
                $v->already_member_groups = null;

                return $v;
            }

            $v->already_member_groups = $curationGroups->whereIn('id', $v->already_member_cgs);

            return $v;
        });

        return AvailableTraineesResource::collection($volunteers);
    }

    public function emailAttendees(CustomTrainingEmailRequest $request, $trainingSessionId)
    {
        $htmlToMarkdown = new HtmlConverter(['strip tags' => true, 'remove_nodes' => 'script']);
        $parsedown = new Parsedown();
        $parsedown->setSafeMode('true');
        $trainingSession = TrainingSession::findOrFail($trainingSessionId);
        $safeBody = $parsedown->parse($htmlToMarkdown->convert($request->body));
        $attachments = collect($request->attachments)
            ->map(function ($file) {
                return MailAttachment::createFromUploadedFile($file);
            })
            ->toArray();

        $recipients = User::find(explode(',', $request->recipients));

        $recipients
            ->each(function ($attendee) use ($request, $trainingSession, $safeBody, $attachments) {
                $attendee->notify(new CustomTrainingEmail($trainingSession, $safeBody, $request->from, $request->subject, $attachments));
            });
    }
}
