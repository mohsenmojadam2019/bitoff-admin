<?php

use Bitoff\Mantis\Application\Models\Trade;

return [
    'translate' => [

    ],
    'color' => [
        Trade::STATUS_ACTIVE => 'primary',
        Trade::STATUS_DISPUTE => 'warning',
        Trade::STATUS_PAID => 'info',
        Trade::STATUS_COMPLETED => 'success',
        Trade::STATUS_CANCELED => 'danger',
        Trade::STATUS_RELEASED => 'info',
        Trade::STATUS_EXPIRED => 'danger',
    ],
    'dashboard' => [

    ],
];
