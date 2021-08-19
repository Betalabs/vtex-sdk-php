<?php

namespace Vtex;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Vtex\Exception\VtexException;

class VtexClient
{
    /**
     * @var array
     */
    private $credentials;

    /**
     * @var array
     */
    private $api;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->api = api($this->parseClass());
        $this->credentials = $config['credentials'];
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @param string $name
     * @param array $args
     * @return array
     */
    public function __call(string $name, array $args): array
    {
        $apiPaths = $this->api['paths'];
        $uri = $this->api['servers'][0]['url'];
        $headers = [];
        $pathArgs = ($args[0] ?? []) + $this->credentials;
        $queryArgs = $args[1] ?? [];
        $queryParams = [];
        $existOperation = false;
        $methodOperation = 'get';

        foreach ($apiPaths as $apiPath => $methods) {
            foreach ($methods as $method => $operation) {
                if (
                    isset($operation['operationId']) &&
                    ucfirst($name) === $operation['operationId']
                ) {
                    $uri = $uri . $apiPath;
                    $methodOperation = $method;

                    foreach ($operation['parameters'] as $parameter) {
                        switch ($parameter['in']) {
                            case 'path':
                                if ($parameter['required']) {
                                    if (isset($pathArgs[$parameter['name']])) {
                                        $uri = str_replace(
                                            '{' . $parameter['name'] . '}',
                                            $pathArgs[$parameter['name']],
                                            $uri
                                        );
                                    } else {
                                        throw new VtexException(
                                            "missing parameter {$parameter['name']} path"
                                        );
                                    }
                                } elseif (isset($pathArgs[$parameter['name']])) {
                                    $uri = str_replace(
                                        '{' . $parameter['name'] . '}',
                                        $pathArgs[$parameter['name']],
                                        $uri
                                    );
                                }

                                break;
                            case 'query':
                                if ($parameter['required']) {
                                    if (isset($queryArgs[$parameter['name']])) {
                                        $queryParams[$parameter['name']] = $queryArgs[$parameter['name']];
                                    } else {
                                        throw new VtexException(
                                            "missing parameter {$parameter['name']} query"
                                        );
                                    }
                                } elseif (isset($queryArgs[$parameter['name']])) {
                                    $queryParams[$parameter['name']] = $queryArgs[
                                    $parameter['name']
                                    ];
                                }

                                break;
                            case 'header':
                                $headers[$parameter['name']] = $headerParams[
                                    $parameter['name']
                                    ] ??
                                    $parameter['schema']['default'] ??
                                    $parameter['schema']['example'];

                                break;
                        }
                    }

                    $existOperation = true;

                    break 2;
                }
            }
        }

        if (!$existOperation) {
            throw new VtexException(
                "operation {$name} not found"
            );
        }

        $client = new Client();

        try {
            $this->response = $client->request(
                $methodOperation,
                $uri,
                [
                    'headers' => $headers,
                    'query' => $queryParams
                ]
            );

            return json_decode($this->response->getBody()->getContents(), true);
        } catch (ClientException $clientException) {
            throw new VtexException(
                $clientException->getResponse()->getBody()->getContents()
            );
        } catch (GuzzleException $guzzleException) {
            throw new VtexException(
                $guzzleException->getMessage()
            );
        }
    }

    /**
     * @return string
     */
    private function parseClass(): string
    {
        $getClass = get_class($this);
        $service = substr($getClass, strrpos($getClass, '\\') + 1, -6);

        return strtolower($service);
    }
}