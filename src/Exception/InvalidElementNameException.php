<?php

declare(strict_types=1);

namespace Laminas\Tag\Exception;

use DomainException;

class InvalidElementNameException extends DomainException implements ExceptionInterface
{
}
