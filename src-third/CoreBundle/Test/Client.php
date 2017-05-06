<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Test;

use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Client as SymfonyClient;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Client
{
    /**
     * @var SymfonyClient
     */
    protected $client;

    /**.
     * @var Response $response
     */
    protected $response;

    /**
     * @var array
     */
    protected $parsedResponse;

    public function __construct(SymfonyClient $client)
    {
        $this->client = $client;
    }

    public function check(int $status) {
        if ($this->response) {
            TestCase::assertEquals($status, $this->response->getStatusCode());
        } else {
            TestCase::fail('No requests have been done.');
        }
    }

    public function post(string $url, array $params = [], array $files = [], array $headers = [])
    {
        $this->request('POST', $url, $params, $files, $headers);
    }

    public function get(string $url, array $params = [], array $files = [], array $headers = [])
    {
        $this->request('GET', $url, $params, $files, $headers);
    }

    public function delete(string $url, array $params = [], array $files = [], array $headers = [])
    {
        $this->request('DELETE', $url, $params, $files, $headers);
    }

    public function put(string $url, array $params = [], array $files = [], array $headers = [])
    {
        $this->request('PUT', $url, $params, $files, $headers);
    }

    public function request(string $method, string $url, array $params = [], array $files = [], array $headers = [])
    {
        $url = $this->loadUrl($url, $params);
        $files = $this->loadFiles($files);

        if (isset($headers["CONTENT_TYPE"]) && $headers["CONTENT_TYPE"] === 'application/json') {
            $this->client->request($method, $url, [], $files, $headers, json_encode($params));
        } else {
            $this->client->request($method, $url, $params, $files, $headers, null);
        }

        $this->response = $this->client->getResponse();
        $this->parseResponse($this->client->getResponse());
    }

    protected function loadUrl(string $url, array $params = []): string
    {
        if ($url[0] !== "/") {
            $url = $this->getRoute($url, $params);
        }

        return $url;
    }

    protected function loadFiles(array $files): array
    {
        $uploads = [];
        foreach ($files as $key => $value) {
            $uploads[$key] = $this->createFile($value);
        }

        return $uploads;
    }

    protected function getRoute(string $name, array $params = []): string
    {
        return $this->client->getContainer()->get('router')->generate(
            $name,
            $params,
            UrlGeneratorInterface::NETWORK_PATH
        );
    }

    protected function createFile(string $path): UploadedFile
    {
        $dest = tempnam(sys_get_temp_dir(), 'TEST');
        copy($path, $dest);

        return new UploadedFile($dest, "test_file");
    }

    /**
     * @param Response $response
     * @return \stdClass|string
     * @throws Exception
     */
    protected function parseResponse(Response $response)
    {
        if ($response->headers->contains("Content-Type", "application/json")) {
            $this->parsedResponse = json_decode($response->getContent(), true);
        } else {
            throw new Exception("Unknown Content-type: " . $response->headers->get("Content-Type"));
        }
    }

    public function getResponse(): array
    {
        return $this->parsedResponse;
    }
}
