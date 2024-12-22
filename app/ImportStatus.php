<?php

namespace App;

enum ImportStatus: string
{
    case IN_PROGRESS = "in_progress";
    case SUCCESSFUL = "successful";
    case UNSUCCESSFUL = "unsuccessful";

    public function getLabel(): string
    {
        return match ($this) {
            self::IN_PROGRESS => 'In progress',
            self::SUCCESSFUL => 'Successful',
            self::UNSUCCESSFUL => 'Unsuccessful',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::IN_PROGRESS => 'warning',
            self::SUCCESSFUL => 'success',
            self::UNSUCCESSFUL => 'danger',
        };
    }
}
