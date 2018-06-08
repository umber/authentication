<?php

declare(strict_types=1);

namespace Umber\Authentication\Resolver\UserResolver;

use Umber\Authentication\AuthenticationMethodInterface;
use Umber\Authentication\Exception\Resolver\CannotResolveAuthenticationMethodException;
use Umber\Authentication\Exception\Resolver\UnsupportedAuthenticationMethodException;
use Umber\Authentication\Method\AuthorisationHeaderInterface;
use Umber\Authentication\Prototype\UserRepositoryInterface;
use Umber\Authentication\Resolver\Credential\CredentialInterface;
use Umber\Authentication\Resolver\Credential\User\UserCredential;
use Umber\Authentication\Resolver\CredentialResolverInterface;

/**
 * A super basic user resolver.
 *
 * Accepts user through the authorisation header using the made up email scheme type.
 *
 * This user resolver is great for use in functional testing cases.
 */
final class UserEmailAuthorisationHeaderCredentialResolver implements CredentialResolverInterface
{
    public const TYPE_EMAIL = 'email';

    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(AuthenticationMethodInterface $method): CredentialInterface
    {
        if ($method instanceof AuthorisationHeaderInterface) {
            return $this->resolveAuthorisationHeader($method);
        }

        throw UnsupportedAuthenticationMethodException::create();
    }

    /**
     * Attempt to resolve the authorisation header.
     *
     * @throws CannotResolveAuthenticationMethodException
     */
    protected function resolveAuthorisationHeader(AuthorisationHeaderInterface $header): CredentialInterface
    {
        switch ($header->getType()) {
            case static::TYPE_EMAIL:
                $user = $this->repository->findOneByEmail($header->getCredentials());

                return new UserCredential($user);

            default:
                throw CannotResolveAuthenticationMethodException::create();
        }
    }
}
