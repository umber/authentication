<?php

declare(strict_types=1);

namespace Umber\Authentication\Enum;

final class AuthenticationErrorEnum
{
    public const CANONICAL_UNAUTHORISED = 'http.authorisation.unauthorised';
    public const CANONICAL_UNAUTHORISED_EXPIRED = 'http.authorisation.expired';
    public const CANONICAL_FORBIDDEN_DENIED = 'http.authorisation.permission.denied';
    public const CANONICAL_MISSING_CREDENTIALS = 'http.authorisation.missing_credentials';
}
