<?php
namespace Slims\Opac;

use Volnix\CSRF\CSRF;

class Security
{
    public static function getCsrfToken()
    {
        return CSRF::getToken();
    }

    public static function checkCsrfToken($sessionToken, $inputToken)
    {
        return CSRF::validate($sessionToken, $inputToken);
    }
}