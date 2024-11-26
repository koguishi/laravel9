<?php

namespace core\domain\enum;

enum MediaStatus: string
{
    case PROCESSING = 'PROCESSING';
    case COMPLETE = 'COMPLETE';
    case PENDING = 'PENDING';
}