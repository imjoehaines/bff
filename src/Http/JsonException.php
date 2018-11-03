<?php

declare(strict_types=1);

namespace Bff\Http;

// on PHP >= 7.3 we can extend the built-in JsonException instead
use Exception;

final class JsonException extends Exception
{
}
