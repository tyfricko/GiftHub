<?php

namespace App\Console\Commands;

use App\Mail\GiftAssignmentNotificationMail;
use App\Models\GiftAssignment;
use App\Models\GiftExchangeEvent;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AssignGiftsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gift-exchange:assign
                            {event_id : The ID of the event}
                            {--force : Overwrite existing assignments}
                            {--dry-run : Show what would be done without changing data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually assign gifts for a specific event (CLI).';

    public function handle()
    {
        $eventId = $this->argument('event_id');
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');

        $event = GiftExchangeEvent::with(['participants.user', 'assignments'])->find($eventId);
        if (! $event) {
            $this->error("Event with ID {$eventId} not found.");

            return 1;
        }

        $this->info("Event: {$event->name} (ID: {$event->id})");

        $participants = $event->participants()->where('status', 'accepted')->with('user')->get();
        $this->info("Participants: {$participants->count()}");

        if ($participants->count() < 2) {
            $this->error('At least 2 participants are required for assignment.');

            return 1;
        }

        if ($event->assignments()->exists() && ! $force) {
            $this->error('Assignments already exist for this event. Use --force to overwrite.');

            return 1;
        }

        $this->table(['ID', 'Username', 'Email'], $participants->map(function ($p) {
            return [$p->id, $p->user->username ?? $p->user->name ?? 'User#'.$p->user_id, $p->user->email ?? 'N/A'];
        })->toArray());

        if ($dryRun) {
            $this->info('Dry run: no changes have been made.');

            return 0;
        }

        $assignmentsCreated = [];

        try {
            DB::transaction(function () use ($event, $participants, $force, &$assignmentsCreated) {
                // If force, delete existing assignments first
                if ($force) {
                    GiftAssignment::where('event_id', $event->id)->delete();
                }

                $givers = $participants->shuffle()->values();
                $recipients = $givers->slice(1)->concat($givers->slice(0, 1))->values();

                foreach ($givers as $i => $giver) {
                    $recipient = $recipients[$i];

                    if ($giver->id === $recipient->id) {
                        throw new \Exception('Self-assignment detected.');
                    }

                    $assignment = GiftAssignment::create([
                        'event_id' => $event->id,
                        'giver_id' => $giver->id,
                        'recipient_id' => $recipient->id,
                        'assigned_at' => Carbon::now(),
                    ]);

                    // Send notification (best-effort)
                    try {
                        Mail::to($giver->user->email)->send(
                            new GiftAssignmentNotificationMail($event, $giver, $recipient)
                        );
                    } catch (\Exception $e) {
                        Log::error('Failed to send assignment email', ['event' => $event->id, 'giver' => $giver->id, 'error' => $e->getMessage()]);
                    }

                    $assignmentsCreated[] = $assignment;
                }
            });
        } catch (\Exception $e) {
            Log::error('Assignment creation failed', ['event' => $event->id, 'error' => $e->getMessage()]);
            $this->error('Assignment failed: '.$e->getMessage());

            return 1;
        }

        $this->info('Assignments created: '.count($assignmentsCreated));

        $this->table(['Giver', 'Recipient'], collect($assignmentsCreated)->map(function ($a) {
            return [
                $a->giver->user->username ?? ('User#'.$a->giver_id),
                $a->recipient->user->username ?? ('User#'.$a->recipient_id),
            ];
        })->toArray());

        return 0;
    }
}
