<?php

declare(strict_types=1);

// config for Freefind/Freefind
return [
    /*
     * Your Freefind site ID. Without this, the package won't work properly. You can find this in your account settings
     * in the top left hand corner of the Freefind control center. It is a numerical string.
     */
    'site_id' => (int) env('FREEFIND_SITE_ID', 0),
];
