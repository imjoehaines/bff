<?php

declare(strict_types=1);

namespace Bff\Http;

class Respond
{
    public function __invoke(array $body, int $statusCode = 200) : Response
    {
        return new Response($body, $statusCode);
    }
}