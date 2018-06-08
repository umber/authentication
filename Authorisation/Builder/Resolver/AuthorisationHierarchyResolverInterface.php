<?php

declare(strict_types=1);

namespace Umber\Authentication\Authorisation\Builder\Resolver;

use Umber\Authentication\Authorisation\Builder\AuthorisationHierarchy;

interface AuthorisationHierarchyResolverInterface
{
    public function resolve(): AuthorisationHierarchy;
}
