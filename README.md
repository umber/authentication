# Umber Authentication

A series of light weight abstractions around HTTP authentication.

|Master|Develop|
|---|---|
|[![Build Status](https://travis-ci.com/umber/authentication.svg?branch=master)](https://travis-ci.com/umber/authentication)|[![Build Status](https://travis-ci.com/umber/authentication.svg?branch=develop)](https://travis-ci.com/umber/authentication)

The authentication component provides a series of classes that will allow
implementation of authentication routines easily. Classes are built in
isolation and do not require a specific framework. Authenticate anything
from `Authorization` headers to custom implementations through building
resolvers.

Implementations are provided for the following frameworks:
* `symfony/symfony` @ `~3.4|~4.0`
