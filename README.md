Umber Authentication
====================

The authentication component provides a series of classes that will allow
implementation of authentication routines easily. Classes are built in
isolation and do not require a specific framework. Authenticate anything
from `Authorization` headers to custom implementations through building
resolvers.

Implementations are provided for the following frameworks:
* `symfony/symfony` @ `~3.4|~4.0`

Authentication methods available:
* `Authorization` header
** `Email` scheme which is handy for tests.

Coming Soon
===========

Planned implementations for:
* `Authorization: Bearer jwt`
