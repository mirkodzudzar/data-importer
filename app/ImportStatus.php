<?php

namespace App;

enum ImportStatus: string
{
    case IN_PROGRESS = "in_progress";
    case SUCCESSFUL = "successful";
    case UNSUCCESSFUL = "unsuccessful";
}
