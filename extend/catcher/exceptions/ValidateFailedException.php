<?php

declare(strict_types=1);

namespace catcher\exceptions;

use catcher\enums\Code;

class ValidateFailedException extends CatchException
{
    protected $code = Code::VALIDATE_FAILED;
}
