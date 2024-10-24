<?php

namespace core\domain\factory;

use core\domain\validation\ValidationInterface;
use core\domain\validation\VideoValidationManual;

class VideoValidationFactory
{
    public static function create(): ValidationInterface
    {
        return new VideoValidationManual();
        // return new VideoLaravelValidator();
        // return new VideoRakitValidator();
    }
}
