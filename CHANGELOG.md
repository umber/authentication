# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## Unreleased

The package had a concept of `UserInterface` which was too restrictive and narrows the scope of authentication instead of sticking with the original concept of `CredentialInterface`.
Therefore a fairly big change has been made to remove this.
Instead documentation should be made available on how to implement your own user focused authentication.

* Removed `Umber\Authentication\Prototype\UserInterface` as it requires an implementation of this which may not meet your domain model.
* Removed `Umber\Authentication\Resolver\Credential\UserCrednetialInterface`
* Removed `Umber\Authentication\Resolver\Credential\UserCrednetial`
* Removed `Umber\Authentication\Storage\CredentialStorageInterface::getUser()`, you can implement this yourself by making the `CredentialInterface` user aware.
* Removed `Umber\Authentication\Framework\Modifier\AuthenticatorRoleModifierInterface` as it's a bespoke use case and roles should be modified before being given to the credential.

* Moved `Umber\Authentication\Prototype\Authorisation\AuthorisationAwareInterface` to `Umber\Authentication\Authorisation\AuthorisationAwareInterface`


## [2.0.0] - 2019-04-21

A small overhaul of the coding standards and how the exceptions work.
Nothing in the public API has changed.

## [1.2.0] - 2019-01-30

This is the first changelog entry.

### Exceptions

All exceptions now extend the base `Exception` instead of relying on `umber/common`.
This package is now no longer reliant on `umber/common` as this package is soon to be deprecated.

Annotated `@throws` tags have been added to all interfaces.
This will display in most modern IDE's that the exception needs to be caught (and this is most likely true) but can be ignored.
To clarify no new exceptions are being thrown just already thrown exceptions have been documented.

Also the exception `Umber\Authentication\Exception\Token\TokenExpiredException` has had its namespace changed from `\Umber\Authentication\Exception\TokenExpiredException`.
Originally it was sitting in the exception root namespace and now has been moved to `Token` within that.
