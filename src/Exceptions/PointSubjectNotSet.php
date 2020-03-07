<?php

namespace Ansezz\Gamify\Exceptions;

use Exception;
use Throwable;

class PointSubjectNotSet extends Exception
{
    protected $message = 'Initialize $subject field in constructor.';
}
