<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Stubs\Extension;

use Genkgo\Xsl\Callback\ClosureFunction;
use Genkgo\Xsl\Callback\FunctionCollection;
use Genkgo\Xsl\Callback\FunctionInterface;
use Genkgo\Xsl\Callback\FunctionMapInterface;
use Genkgo\Xsl\Callback\StaticClassFunction;
use Genkgo\Xsl\Util\TransformerCollection;
use Genkgo\Xsl\XmlNamespaceInterface;

final class MyExtension implements XmlNamespaceInterface
{
    const URI = 'https://github.com/genkgo/xsl/tree/master/tests/Stubs/Extension/MyExtension';

    /**
     * @param mixed ...$args
     * @return string
     */
    public static function helloWorld(...$args)
    {
        return 'Hello World was called and received ' . \count($args) . ' arguments!';
    }

    /**
     * @param TransformerCollection $transformers
     * @param FunctionCollection $functions
     * @return void
     */
    public function register(TransformerCollection $transformers, FunctionCollection $functions): void
    {
        $functions->attach(
            self::URI,
            new class implements FunctionMapInterface {
                private $functions = [];

                public function __construct()
                {
                    $this->functions = [
                        'hello-world' => new StaticClassFunction(MyExtension::class, 'helloWorld'),
                        'collection-sqrt' => new ClosureFunction(
                            MyExtension::URI . ':collection-sqrt',
                            \Closure::fromCallable(
                                [
                                    new CollectionSqrtFunction([1, 2, 3, 4]),
                                    'sqrt'
                                ]
                            )
                        ),
                        'hello-earth' => new ClosureFunction(
                            MyExtension::URI . ':hello-earth',
                            function ($arguments) {
                                return \count($arguments);
                            }
                        ),
                    ];
                }

                /**
                 * @param string $name
                 * @return FunctionInterface
                 */
                public function get(string $name): FunctionInterface
                {
                    if (isset($this->functions[$name])) {
                        return $this->functions[$name];
                    }

                    throw new \InvalidArgumentException('Unknown function ' . $name);
                }
            }
        );
    }
}
