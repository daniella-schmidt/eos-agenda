<?php

namespace App\Services\SmartRequest;

use App\Enums\EventParticipantResponseStatus;
use App\Enums\EventParticipantRole;
use App\Enums\EventReminderType;
use App\Enums\EventSource;
use App\Enums\EventStatus;
use App\Enums\SmartRequestStatus;
use App\Models\Calendar;
use App\Models\Event;
use App\Services\Event\CheckEventConflictService;
use App\Models\SmartRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ConfirmSmartRequestService
{
    public function __construct(
        private readonly CheckEventConflictService $checkEventConflictService,
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function handle(SmartRequest $smartRequest): Event
    {
        return DB::transaction(function () use ($smartRequest): Event {
            /*
             * Busca e bloqueia a SmartRequest durante a transação,
             * evitando duas confirmações simultâneas.
             */
            $smartRequest = SmartRequest::query()
                ->with('user')
                ->lockForUpdate()
                ->findOrFail($smartRequest->id);

            /*
             * Torna a operação idempotente:
             * caso o evento já tenha sido criado, retorna o existente.
             */
            $existingEvent = Event::query()
                ->where('smartRequestId', $smartRequest->id)
                ->first();

            if ($existingEvent) {
                return $existingEvent;
            }

            $this->validateSmartRequest($smartRequest);

            $calendar = $this->findDefaultCalendar($smartRequest);

            $hasConflict = $this->checkEventConflictService->handle(
                userId: $smartRequest->userId,
                startAt: $smartRequest->extractedStartAt,
                endAt: $smartRequest->extractedEndAt,
            );

            if ($hasConflict) {
                throw ValidationException::withMessages([
                    'event' => [
                        'O horário selecionado não está mais disponível.',
                    ],
                ]);
            }

            $event = Event::create([
                'userId' => $smartRequest->userId,
                'calendarId' => $calendar->id,
                'smartRequestId' => $smartRequest->id,

                'title' => $smartRequest->extractedTitle,
                'description' => $smartRequest->extractedDescription,

                'startAt' => $smartRequest->extractedStartAt,
                'endAt' => $smartRequest->extractedEndAt,

                'timezone' => $smartRequest->user->timezone
                    ?? 'America/Sao_Paulo',

                'status' => EventStatus::Confirmed,
                'source' => EventSource::SmartRequest,
                'createByAI' => true,
            ]);

            foreach ($smartRequest->extractedParticipants ?? [] as $participant) {
                if (blank($participant['name'] ?? null) || blank($participant['email'] ?? null)) {
                    continue;
                }

                $event->participants()->create([
                    'name' => $participant['name'],
                    'email' => $participant['email'],
                    'role' => EventParticipantRole::Attendee,
                    'responseStatus' => EventParticipantResponseStatus::Pending,
                ]);
            }

            $event->reminders()->create([
                'type' => EventReminderType::notification,
                'minutesBefore' => 10,
            ]);

            $smartRequest->update([
                'status' => SmartRequestStatus::Completed,
            ]);

            return $event->load([
                'participants',
                'reminders',
            ]);
        });
    }

    /**
     * @throws ValidationException
     */
    private function validateSmartRequest(SmartRequest $smartRequest): void
    {
        if ($smartRequest->status !== SmartRequestStatus::NeedsConfirmation) {
            throw ValidationException::withMessages([
                'smartRequest' => [
                    'Esta solicitação não está aguardando confirmação.',
                ],
            ]);
        }

        if (
            blank($smartRequest->extractedTitle)
            || blank($smartRequest->extractedStartAt)
            || blank($smartRequest->extractedEndAt)
        ) {
            throw ValidationException::withMessages([
                'smartRequest' => [
                    'A solicitação não possui todos os dados necessários para criar o evento.',
                ],
            ]);
        }

        if ($smartRequest->extractedEndAt <= $smartRequest->extractedStartAt) {
            throw ValidationException::withMessages([
                'smartRequest' => [
                    'A data final do evento deve ser posterior à data inicial.',
                ],
            ]);
        }
    }

    /**
     * @throws ValidationException
     */
    private function findDefaultCalendar(SmartRequest $smartRequest): Calendar
    {
        $calendar = Calendar::query()
            ->where('userId', $smartRequest->userId)
            ->where('isDefault', true)
            ->where('isActive', true)
            ->first();

        if (! $calendar) {
            throw ValidationException::withMessages([
                'calendar' => [
                    'O usuário não possui um calendário padrão ativo.',
                ],
            ]);
        }

        return $calendar;
    }
}
