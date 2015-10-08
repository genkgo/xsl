<?php
namespace Genkgo\Xsl;

final class Transpiler {

    /**
     * @var Context
     */
    private $context;

    public function __construct (Context $context) {
        $this->context = $context;
    }

    public function transpile ($path) {
        if (substr($path, -1, 1) === '~') {
            return $this->context->getDocument()->saveXML();
        }

        return file_get_contents($path);
    }

}