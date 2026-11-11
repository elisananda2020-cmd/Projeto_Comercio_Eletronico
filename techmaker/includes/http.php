<?php

declare(strict_types=1);

function store_response_headers(): void
{
    ini_set('display_errors', '0');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: no-referrer');
    header("Content-Security-Policy: frame-ancestors 'none'; object-src 'none'; base-uri 'self'; form-action 'self'");
}
