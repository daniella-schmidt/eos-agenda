<?php

namespace App\Services\SmartRequest;

use App\DTO\SmartRequest\ExtractedEventDataDTO;
use Carbon\Carbon;

// Essa versão é boa para o primeiro fluxo. Ela não é uma IA de verdade ainda, 
// mas permite desenvolver o fluxo completo
class ExtractEventDataService
{
    public function handle(string $rawText, string $timezone = 'America/Sao_Paulo'): ExtractedEventDataDTO
    {
        $text = mb_strtolower(trim($rawText));

        $title = $this->extractTitle($rawText);
        $startAt = $this->extractStartAt($text, $timezone);
        $endAt = $startAt
            ? Carbon::parse($startAt, $timezone)->addMinutes(30)->format('Y-m-d H:i:s')
            : null;

        $participants = $this->extractParticipants($rawText);

        $missingFields = $this->detectMissingFields(
            title: $title,
            startAt: $startAt,
            endAt: $endAt
        );

        return new ExtractedEventDataDTO(
            title: $title,
            description: $this->extractDescription($rawText),
            startAt: $startAt,
            endAt: $endAt,
            participants: $participants,
            missingFields: $missingFields,
            raw: [
                'rawText' => $rawText,
                'normalized_text' => $text,
                'timezone' => $timezone,
            ]
        );
    }

    private function extractTitle(string $rawText): ?string
    {
        $text = trim($rawText);

        if ($text === '') {
            return null;
        }

        // Para o MVP: gera um título simples a partir do texto.
        // Depois isso pode ser substituído por IA.
        return mb_strimwidth($text, 0, 200);
    }

    private function extractDescription(string $rawText): ?string
    {
        if (str_contains(mb_strtolower($rawText), 'sobre')) {
            $parts = explode('sobre', $rawText, 2);

            return trim($parts[1] ?? '') ?: null;
        }

        return null;
    }

    private function extractStartAt(string $text, string $timezone): ?string
    {
        $date = $this->extractDate($text, $timezone);
        $time = $this->extractTime($text);

        if (! $date || ! $time) {
            return null;
        }

        return Carbon::parse("{$date} {$time}", $timezone)->format('Y-m-d H:i:s');
    }

    private function extractDate(string $text, string $timezone): ?string
    {
        $now = Carbon::now($timezone);

        if (str_contains($text, 'hoje')) {
            return $now->format('Y-m-d');
        }

        if (str_contains($text, 'amanhã') || str_contains($text, 'amanha')) {
            return $now->copy()->addDay()->format('Y-m-d');
        }

        if (str_contains($text, 'segunda')) {
            return $now->copy()->next(Carbon::MONDAY)->format('Y-m-d');
        }

        if (str_contains($text, 'terça') || str_contains($text, 'terca')) {
            return $now->copy()->next(Carbon::TUESDAY)->format('Y-m-d');
        }

        if (str_contains($text, 'quarta')) {
            return $now->copy()->next(Carbon::WEDNESDAY)->format('Y-m-d');
        }

        if (str_contains($text, 'quinta')) {
            return $now->copy()->next(Carbon::THURSDAY)->format('Y-m-d');
        }

        if (str_contains($text, 'sexta')) {
            return $now->copy()->next(Carbon::FRIDAY)->format('Y-m-d');
        }

        // Formato simples: 25/06 ou 25/06/2026
        if (preg_match('/\b(\d{1,2})\/(\d{1,2})(?:\/(\d{4}))?\b/', $text, $matches)) {
            $day = (int) $matches[1];
            $month = (int) $matches[2];
            $year = isset($matches[3]) ? (int) $matches[3] : (int) $now->format('Y');

            return Carbon::create($year, $month, $day, 0, 0, 0, $timezone)
                ->format('Y-m-d');
        }

        return null;
    }

    private function extractTime(string $text): ?string
    {
        // Captura: 15h, 15:30, 15h30
        if (preg_match('/\b(\d{1,2})(?:h|:)?(\d{2})?\b/', $text, $matches)) {
            $hour = (int) $matches[1];
            $minute = isset($matches[2]) ? (int) $matches[2] : 0;

            if ($hour >= 0 && $hour <= 23 && $minute >= 0 && $minute <= 59) {
                return sprintf('%02d:%02d:00', $hour, $minute);
            }
        }

        return null;
    }

    private function extractParticipants(string $rawText): array
    {
        $participants = [];
        $emails = [];

        if (preg_match_all('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i', $rawText, $emailMatches)) {
            $emails = $emailMatches[0];
        }

        // Exemplo simples:
        // "com Ana amanhã às 15h"
        if (preg_match('/com\s+([A-ZÁÉÍÓÚÂÊÔÃÕÇ][a-záéíóúâêôãõç]+)/u', $rawText, $matches)) {
            $participants[] = [
                'name' => $matches[1],
                'email' => $emails[0] ?? null,
            ];
        }

        foreach (array_slice($emails, count($participants)) as $email) {
            $participants[] = [
                'name' => strtok($email, '@') ?: $email,
                'email' => $email,
            ];
        }

        return $participants;
    }

    private function detectMissingFields(?string $title, ?string $startAt, ?string $endAt): array
    {
        $missing = [];

        if (! $title) {
            $missing[] = 'title';
        }

        if (! $startAt) {
            $missing[] = 'start_at';
        }

        if (! $endAt) {
            $missing[] = 'end_at';
        }

        return $missing;
    }
}
