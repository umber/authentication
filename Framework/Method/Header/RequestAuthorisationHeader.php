<?php

declare(strict_types=1);

namespace Umber\Authentication\Framework\Method\Header;

use Umber\Authentication\Exception\Authorisation\MissingCredentialsException;
use Umber\Authentication\Exception\Method\Header\MalformedAuthorisationHeaderException;
use Umber\Authentication\Method\AuthorisationHeaderInterface;
use Umber\Authentication\Method\Header\StringAuthorisationHeader;

use Symfony\Component\HttpFoundation\Request;

/**
 * An implementation of authorisation header that accepts a Symfony request.
 *
 * This class will attempt to locate and parse the header string from a Symfony request.
 */
final class RequestAuthorisationHeader implements AuthorisationHeaderInterface
{
    public const AUTHORISATION_HEADER = 'Authorization';

    /** @var StringAuthorisationHeader */
    private $header;

    /**
     * @throws MissingCredentialsException When the request is missing authorisation header.
     * @throws MalformedAuthorisationHeaderException When the authorisation header is malformed.
     */
    public function __construct(Request $request)
    {
        $string = $request->headers->get(self::AUTHORISATION_HEADER, null);

        if ($string === null) {
            throw MissingCredentialsException::create();
        }

        $this->header = new StringAuthorisationHeader($string);
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return $this->header->getType();
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(): string
    {
        return $this->header->getCredentials();
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return $this->header->toString();
    }

    /**
     * Magic conversion to string.
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
