<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Exception;

final class TransformationException extends \Exception
{
    public static function fromLibxmlErrorList(array $errors): self
    {
        if ($errors === []) {
            throw new \InvalidArgumentException('Cannot create exception while there are no libxml errors');
        }

        $exceptions = [];
        while ($errors !== []) {
            /** @var \LibXMLError $error */
            $error = \array_shift($errors);
            $previous = isset($exceptions[0]) ? $exceptions[0] : null;
            $exception = new self($error->message, 0, $previous);
            $exception->file = $error->file;
            $exception->code = $error->code;
            $exception->line = $error->line;
            $exceptions[] = $exception;
        }

        return new self('Transformation failed: ' . $exceptions[0]->message, 0, $exceptions[0]);
    }
}
