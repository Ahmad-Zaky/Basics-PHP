<?php

namespace App\Middlewares;

use Exception;

class VerifyCsrfToken
{
    public function handle() 
    {
        if (request()->isReading(requestMethod())) return;

        $token = request("_token");

        if (
            ! is_string(csrfToken()) ||
            ! is_string($token) ||
            ! hash_equals(csrfToken(), $token)
        ) { throw new Exception(__("CSRF token mismatch.")); }

        distroyCsrfToken();
    }
}