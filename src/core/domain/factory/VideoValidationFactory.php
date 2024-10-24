<?php

namespace core\domain\factory;

use core\domain\validation\ValidationInterface;
use core\domain\validation\VideoManualValidation;

class VideoValidationFactory
{
    public static function create(): ValidationInterface
    {
        return new VideoManualValidation();
        // return new VideoLaravelValidator();
        // return new VideoRakitValidator();
    }
}
