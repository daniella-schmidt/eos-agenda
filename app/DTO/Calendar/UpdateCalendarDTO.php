<?php

namespace App\DTO\Calendar;

//Usar um array aqui permite diferenciar um campo ausente de um campo enviado explicitamente como null.
class UpdateCalendarDTO
{
    public function __construct(
        public readonly array $attributes,
    ) {
    }
}