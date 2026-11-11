<?php

declare(strict_types=1);

function ga4_measurement_id(): string
{
    $id = getenv('GA4_MEASUREMENT_ID');
    return is_string($id) && preg_match('/\AG-[A-Z0-9]{4,20}\z/', $id) ? $id : '';
}

function gtm_container_id(): string
{
    return 'GTM-PML7LNKP';
}
