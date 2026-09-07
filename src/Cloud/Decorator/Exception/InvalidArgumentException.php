<?php

declare(strict_types=1);

namespace Laminas\Tag\Cloud\Decorator\Exception;

use Laminas\Tag\Exception;

/** @psalm-suppress ClassMustBeFinal */
class InvalidArgumentException extends Exception\InvalidArgumentException implements ExceptionInterface
{
}
