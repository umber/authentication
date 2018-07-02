<?php

declare(strict_types=1);

namespace Umber\Authentication\Framework\Symfony;

use Umber\Authentication\Authenticator;
use Umber\Authentication\Exception\Authorisation\MissingCredentialsException;
use Umber\Authentication\Exception\Method\Header\MalformedAuthorisationHeaderException;
use Umber\Authentication\Exception\UnauthorisedException;
use Umber\Authentication\Framework\Modifier\AuthenticatorRoleModifierInterface;
use Umber\Authentication\Framework\Symfony\Method\Header\SymfonyRequestAuthorisationHeader;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

/**
 * An authenticator implementation for Symfony.
 */
final class SymfonyAuthenticator implements SimplePreAuthenticatorInterface
{
    private $authenticator;
    private $modifier;

    public function __construct(Authenticator $authenticator, AuthenticatorRoleModifierInterface $modifier)
    {
        $this->authenticator = $authenticator;
        $this->modifier = $modifier;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsToken(TokenInterface $token, $provider)
    {
        return ($token instanceof PreAuthenticatedToken)
            && ($token->getProviderKey() === $provider);
    }

    /**
     * {@inheritdoc}
     *
     * @throws UnauthorisedException When the authorisation header is missing.
     * @throws UnauthorisedException When the authorisation header is malformed.
     * @throws UnauthorisedException When the credentials cannot be resolved.
     */
    public function createToken(Request $request, $provider)
    {
        try {
            $header = new SymfonyRequestAuthorisationHeader($request);
        } catch (MalformedAuthorisationHeaderException $exception) {
            throw UnauthorisedException::create($exception);
        } catch (MissingCredentialsException $exception) {
            throw UnauthorisedException::create($exception);
        }

        $this->authenticator->authenticate($header);

        return new PreAuthenticatedToken(
            $header->getType(),
            $header->getCredentials(),
            $provider
        );
    }

    /**
     * {@inheritdoc}
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $provider)
    {
        $credentials = $token->getCredentials();

        $user = $this->authenticator->getUser();

        $roles = $user->getAuthorisationRoles();
        $roles = $this->modifier->modify($roles);

        return new PreAuthenticatedToken($user, $credentials, $provider, $roles);
    }
}
