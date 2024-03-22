<?php

namespace Bitoff\Feedback\Application\Enum;

use Bitoff\Feedback\Utilities\EnumGetByValue;
use Bitoff\Feedback\Utilities\EnumValues;

enum FeedbackRole: string
{
    use EnumValues;
    use EnumGetByValue;

    case ROLE_EARNER = 'earner';
    case ROLE_SHOPPER = 'shopper';
    case ROLE_OFFERER = 'offerer';
    case ROLE_TRADER = 'trader';

}
