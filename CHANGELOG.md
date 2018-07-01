# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

- Nothing.

## [0.1.1] - 2018-07-01

### Added
- Added `UmberAuthenticationBundle` for those using `symfony` and want services registered for you.

### Moved
- The class `RequestAuthorisationHeader` has been renamed to `SymfonyRequestAuthorisationHeader`.
- The classes relating to `symfony` have been moved to `Framework/Symfony`.

## [0.1.0] - 2018-06-08

### Removed
- Removed classes implementing `CredentialResolverInterface`.

### Added
- Added a series of token classes for `lcobucci/jwt` and basic implementation.
- Added implementation for `symfony/security` @ `~3.4|~4.0`.
- Added `phpunit.xml.dist`.
- Added `.gitattributes` and `.gitignore`.
- Added `CHANGELOG` and `README`.
- Added the authentication code base from `umber/common`.
