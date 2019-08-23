<?php declare(strict_types=1);

namespace Bff;

use Psr\Http\Message\ResponseInterface;

final class Emitter
{
    public function emit(ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();

        header($this->formatHttpStatusLine($response), true, $statusCode);

        foreach ($response->getHeaders() as $header => $values) {
            $isFirstValue = true;

            foreach ($values as $value) {
                header(
                    sprintf('%s: %s', $header, $value),
                    $isFirstValue
                );

                $isFirstValue = false;
            }
        }

        http_response_code($statusCode);

        echo $response->getBody();
    }

    private function formatHttpStatusLine(ResponseInterface $response): string
    {
        return rtrim(
            sprintf(
                'HTTP/%s %d %s',
                $response->getProtocolVersion(),
                $response->getStatusCode(),
                $response->getReasonPhrase()
            )
        );
    }
}
