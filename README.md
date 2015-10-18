# xsl
XSL 2.0 Transpiler in PHP

### Installation

Requires PHP 5.6 or later. There are no plans to support PHP 5.5 or earlier. PRs in this matter are rejected. It is installable and autoloadable via Composer as [genkgo/xsl](https://packagist.org/packages/genkgo/xsl).

### Quality

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/genkgo/xsl/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/genkgo/xsl/)
[![Code Coverage](https://scrutinizer-ci.com/g/genkgo/xsl/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/genkgo/xsl/)
[![Build Status](https://travis-ci.org/genkgo/xsl.png?branch=master)](https://travis-ci.org/genkgo/xsl)

To run the unit tests at the command line, issue `phpunit -c tests/`. [PHPUnit](http://phpunit.de/manual/) is required.

This library attempts to comply with [PSR-1][], [PSR-2][], and [PSR-4][]. If
you notice compliance oversights, please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

## Getting Started

Just replace `XSLTProcessor` with `Genkgo\Xsl\XsltProcessor` and you are done!

```php
<?php
use Genkgo\Xsl\XsltProcessor;

$xslDoc = new DOMDocument();
$xslDoc->load('Stubs/collection.xsl');

$xmlDoc = new DOMDocument();
$xmlDoc->load('Stubs/collection.xml');

$transpiler = new XsltProcessor();
$transpiler->importStylesheet($xslDoc);
echo $transpiler->transformToXML($xmlDoc);
```

## Create your own extenions

You can also register your own extensions. Just implement the `XmlNamespaceInterface` and you
are ready to use your own element transformations and xpath functions. See the example below and the [integration
test](https://github.com/genkgo/xsl/blob/master/tests/Integration/ExtensionTest.php) to understand how it works.


```php
<?php
use Genkgo\Xsl\Config;
use Genkgo\Xsl\XsltProcessor;

class MyExtensions implements XmlNamespaceInterface {
    // implementation here
}

$config = new Config();
$config->setExtensions(new MyExtensions());

$transpiler = new XsltProcessor($config);
```

## Caching: transpile once

Depending on the complexity of your stylesheet, the transpiling process could slow down the processing of your
document. Therefore, you probably want to cache the result stylesheet. By adding
[`genkgo/cache`](https://github.com/genkgo/cache) to your composer.json, you will add the possibility to enable caching.
See the example below, or the [integration test](https://github.com/genkgo/xsl/blob/master/tests/Integration/CacheTest.php)
to see how it works.


```php
<?php
use Genkgo\Cache\Adapters\ArrayAdapter;
use Genkgo\Cache\Adapters\SimpleCallbackAdapter;
use Genkgo\Xsl\Config;
use Genkgo\Xsl\XsltProcessor;

$arrayCache = new ArrayAdapter();

$config = new Config();
$config->setCacheAdapter(new SimpleCallbackAdapter($arrayCache));

$transpiler = new XsltProcessor($config);
```

## Contributing

- Found a bug? Please try to solve it yourself first and issue a pull request. If you are not able to fix it, at least
  give a clear description what goes wrong. We will have a look when there is time.
- Want to see a feature added, issue a pull request and see what happens. You could also file a bug of the missing
  feature and we can discuss how to implement it.
