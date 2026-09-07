<?php

declare(strict_types=1);

namespace Laminas\Tag\Exception;

use DomainException;

final class InvalidElementNameException extends DomainException implements ExceptionInterface
{
}
