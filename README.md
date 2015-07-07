# Bandiera PHP Client

This is a client for talking to the [Bandiera][bandiera] feature flagging service from a PHP application.

This client is compatible with the v2 Bandiera API.

[![Build status][shield-build]][info-build]
[![MIT licensed][shield-license]][info-license]

# Installation

Installation is done via composer. In your `composer.json` file add:

```json
{
    "require": {
        "npg/bandiera-client-php": "~1.0"
    }
}
```

# Usage

You can interact with the Bandiera server like so:

```php
<?php

include 'vendor/autoload.php';

$bandiera = new Nature\Bandiera\Client('http://bandiera-demo.herokuapp.com');

if ($bandiera->isEnabled('my_app', 'super_new_feature')) {
    // show the super new feature!
}
```

The `Nature\Bandiera\Client::isEnabled` command takes two main arguments - the 'feature group',
and the 'feature name'.  This is because in Bandiera, features are organised
into groups as it is intented as a service for multiple applications to use at
the same time - this organisation allows separation of feature flags that are
intended for different audiences.

`Nature\Bandiera\Client::isEnabled` also takes an optional `params` array, this is
for use with some of the more advanced features in Bandiera - user group and percentage based flags. It is in this params array you pass in your
`user_group` and `user_id`, i.e.:

```php
$bandiera->isEnabled('my_app', 'super_new_feature', [
    'user_id' => 1234567,
    'user_group' => 'Administrators'
]);
```

For more information on these advanced features, please see the Bandiera wiki:

https://github.com/nature/bandiera/wiki/How-Feature-Flags-Work#feature-flags-in-bandiera

# Direct API Access

If you'd prefer not to use the `isEnabled` method for featching feature flag values, the following methods are available...

Get features for all groups:

```php
Nature\Bandiera\Client::getAll($params = []);
```

Get features for a group:

```php
Nature\Bandiera\Client::getFeaturesForGroup($group, $params = []);
```

Get an individual feature:

```php
Nature\Bandiera\Client::getFeature($group, $feature, $params = []);
```

# Development

1. Fork this repo.
2. Run `composer install`

# License

[&copy; 2014 Nature Publishing Group](LICENSE.txt).
Bandiera PHP Client is licensed under the [MIT License][mit].

[mit]: http://opensource.org/licenses/mit-license.php
[bandiera]: https://github.com/nature/bandiera
[bandiera-api]: https://github.com/nature/bandiera/wiki/API-Documentation
[info-license]: LICENSE
[shield-dependencies]: https://img.shields.io/gemnasium/nature/bandiera-client-ruby.svg
[info-build]: https://travis-ci.org/nature/bandiera-client-php
[shield-license]: https://img.shields.io/badge/license-MIT-blue.svg
[shield-build]: https://img.shields.io/travis/nature/bandiera-client-php/master.svg
