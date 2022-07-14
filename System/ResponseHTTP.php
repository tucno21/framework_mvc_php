<?php

namespace System;

/** 
 * verificar la web si es 404
 */

class ResponseHTTP
{
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }
}
