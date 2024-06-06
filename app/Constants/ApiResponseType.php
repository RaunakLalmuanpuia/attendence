<?php

namespace App\Constants;
class ApiResponseType{
    const MODEL_NOT_FOUND = 404;
    const INVALID_CREDENTIAL = 417;
    const APPROVAL_NEEDED = 407;
    const SUCCESS = 200;
    const VALIDATION_ERROR = 429;
    const INVALID_OFFICE = 412;
    const INVALID_DEVICE = 456;
    const LATE_ATTENDANCE = 466;
    const INVALID_GEO = 444;
    const ALREADY_SIGNIN = 441;
    const DUPLICATE_ATTENDANCE = 451;
}
