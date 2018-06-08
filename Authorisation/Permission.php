<?php

declare(strict_types=1);

namespace Umber\Authentication\Authorisation;

use Umber\Authentication\Exception\Authorisation\Permission\PermissionAbilityNameInvalidException;
use Umber\Authentication\Exception\Authorisation\Permission\PermissionScopeNameInvalidException;

/**
 * {@inheritdoc}
 */
final class Permission implements PermissionInterface
{
    private const NAME_REGEX = '/^([a-z]+[a-z\-\_]?[a-z]+)+$/';

    private $scope;
    private $abilities;

    /**
     * @param string[] $abilities
     */
    public function __construct(string $scope, array $abilities)
    {
        if (preg_match(self::NAME_REGEX, strtolower($scope)) !== 1) {
            throw PermissionScopeNameInvalidException::create($scope);
        }

        foreach ($abilities as $ability) {
            if ($ability === self::WILDCARD) {
                $abilities = [self::WILDCARD];

                break;
            }

            if (preg_match(self::NAME_REGEX, strtolower($ability)) !== 1) {
                throw PermissionAbilityNameInvalidException::create($scope, $ability);
            }
        }

        $this->scope = strtolower($scope);
        $this->abilities = array_map('strtolower', $abilities);
    }

    /**
     * {@inheritdoc}
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * {@inheritdoc}
     */
    public function getAbilities(): array
    {
        return $this->abilities;
    }

    /**
     * {@inheritdoc}
     */
    public function hasAbility(string $ability): bool
    {
        if (in_array(self::WILDCARD, $this->abilities)) {
            return true;
        }

        foreach ($this->abilities as $string) {
            if ($string === $ability) {
                return true;
            }
        }

        return false;
    }
}
