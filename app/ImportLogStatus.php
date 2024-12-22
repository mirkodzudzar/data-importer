<?php

namespace App;

enum ImportLogStatus: string
{
    case VALIDATION_FAILED = "validation_failed";
    case ERROR = "error";
}
