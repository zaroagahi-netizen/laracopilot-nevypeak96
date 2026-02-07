<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cart Session Configuration
    |--------------------------------------------------------------------------
    |
    | Configure cart behavior and reservation settings
    |
    */

    'reservation_time' => 15, // Minutes to hold stock reservation

    'lock_timeout' => 10, // Seconds for cache lock timeout

    'cleanup_interval' => 60, // Minutes between automatic cleanup of expired reservations
];