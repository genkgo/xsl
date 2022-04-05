# Genkgo/Xsl
XSL 2.0 Transpiler in PHP.

### Installation

Requires PHP 7.4 or later. It is installable and autoloadable via Composer as [genkgo/xsl](https://packagist.org/packages/genkgo/xsl).

### Quality

![workflow code check](https://github.com/genkgo/xsl/workflows/code%20check/badge.svg)

To run the unit tests at the command line, issue `./vendor/bin/phpunit -c phpunit.xml`. This library attempts to comply with
[PSR-1][], [PSR-2][], and [PSR-4][]. If you notice compliance oversights, please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

## Getting Started

Replace `XSLTProcessor` with `Genkgo\Xsl\XsltProcessor`, change `version="1.0"` in `version="2.0"` and you are done!

```php
<?php
use Genkgo\Xsl\XsltProcessor;
use Genkgo\Xsl\Cache\NullCache;

$xslDoc = new DOMDocument();
$xslDoc->load('Stubs/collection.xsl');

$xmlDoc = new DOMDocument();
$xmlDoc->load('Stubs/collection.xml');

$transpiler = new XsltProcessor(new NullCache());
$transpiler->importStylesheet($xslDoc);
echo $transpiler->transformToXML($xmlDoc);
```

## Create your own extenions

You can also register your own extensions. Just implement the `XmlNamespaceInterface` and you
are ready to use your own element transformations and xpath functions. See the example below and the [integration
test](https://github.com/genkgo/xsl/blob/master/tests/Integration/ExtensionTest.php) to understand how it works.


```php
<?php
// use omitted for readability

class MyExtension implements XmlNamespaceInterface {

    const URI = 'https://github.com/genkgo/xsl/tree/master/tests/Stubs/Extension/MyExtension';

    public function register(TransformerCollection $transformers, FunctionCollection $functions) {
        $functions->set(
            self::URI, 
            new class extends AbstractLazyFunctionMap {
                public function newFunctionList(): array
                {
                    return [
                        'hello-world' => ['newStringFunction', MyExtension::class],
                    ];
                }
            }
        );
    }

    public static function helloWorld(Arguments $arguments) {
        return 'Hello World was called and received ' . count($arguments->unpack()) . ' arguments!';
    }

}

$factory = new ProcessorFactory(new NullCache(), [new MyExtension()]);
$processor = $factory->newProcessor();
```

and then call the function in your style sheet.

```xsl
<xsl:stylesheet version="2.0"
        xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
        xmlns:my="https://github.com/genkgo/xsl/tree/master/tests/Stubs/Extension/MyExtension">

    <xsl:output omit-xml-declaration="yes" />

    <xsl:template match="/">
        <xsl:value-of select="my:hello-world(1, 2, 3, 4, 5, 6, 7, 8, 9, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10)" />
    </xsl:template>

</xsl:stylesheet>
```

will yield: `Hello World was called and received 20 arguments!`.

## Caching: transpile once

Depending on the complexity of your stylesheet, the transpiling process could slow down the processing of your
document. Therefore, you probably want to cache the result stylesheet. By adding
[`psr/simple-cache`](https://packagist.org/packages/psr/simple-cache) to your composer.json, you will add the possibility to enable caching.
See the example below, or the [integration test](https://github.com/genkgo/xsl/blob/master/tests/Integration/CacheTest.php)
to see how it works.


```php
<?php
use Genkgo\Xsl\Cache\ArrayCache;
use Genkgo\Xsl\ProcessorFactory;

$factory = new ProcessorFactory(new ArrayCache());
$processor = $factory->newProcessor();
```

## Contributing

- Found a bug? Please try to solve it yourself first and issue a pull request. If you are not able to fix it, at least
  give a clear description what goes wrong. We will have a look when there is time.
- Want to see a feature added, issue a pull request and see what happens. You could also file a bug of the missing
  feature and we can discuss how to implement it.
