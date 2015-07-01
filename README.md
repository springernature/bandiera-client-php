# Bandiera PHP Client

A PHP client for talking to the [Bandiera][bandiera] feature flagging
service.

# Installation

Installation is done via composer. In your `composer.json` file add:

```json
{
    "require": {
        "nature/bandiera-client-php": "~1.0"
    }
}
```

# Usage

```php
<?php

include 'vendor/autoload.php';

$bandiera = new Nature\Bandiera\Client('http://bandiera.example.com');

if ($bandiera->isEnabled('my_app', 'super_new_feature')) {
    // show the super new feature!
}
```

# License

[&copy; 2014, Nature Publishing Group](LICENSE.txt).

Bandiera is licensed under the [GNU General Public License 3.0][gpl].

[gpl]: http://www.gnu.org/licenses/gpl-3.0.html
[bandiera]: https://github.com/nature/bandiera
