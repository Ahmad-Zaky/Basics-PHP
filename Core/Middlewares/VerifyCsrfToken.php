<?php

namespace Core\Middlewares;

use Exception;

class VerifyCsrfToken
{
    public function handle() 
    {
        if ($this->isReading(requestMethod())) return;
        
        $token = request("_token");

        if (
            ! is_string(csrfToken()) ||
            ! is_string($token) ||
            ! hash_equals(csrfToken(), $token)
        ) { throw new Exception("CSRF token mismatch."); }

        distroyCsrfToken();
    }

    protected function isReading($method)
    {
        return in_array($method, ['HEAD', 'GET', 'OPTIONS']);
    }
}