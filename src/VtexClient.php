<?php

namespace Vtex;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
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
        $this->parseApi();
        $this->parseCredentials($config);
    }

    /**
     * Parse API routes.
     */
    protected function parseApi()
    {
        $this->api = api($this->parseClass());
    }

    /**
     * Parse credentials from config.
     *
     * @param array $config
     */
    protected function parseCredentials(array $config = [])
    {
        if (isset($config['credentials'])) {
            $this->credentials = $config['credentials'];
        } else {
            $this->credentials = [
                'accountName' => getenv('VTEX_ACCOUNT_NAME'),
                'environment' => getenv('VTEX_ENVIRONMENT'),
                'appKey' => getenv('VTEX_APP_KEY'),
                'appToken' => getenv('VTEX_APP_TOKEN')
            ];
        }
    }

    /**
     * @param string $name
     * @param array $args = []
     * @return array
     * @throws VtexException
     */
    public function __call(string $name, array $args): ?array
    {
        try {
            $apiPaths = $this->api['paths'];
            $securitySchemas = $this->api['components']['securitySchemes'] ?? [];
            $uri = $this->api['servers'][0]['url'];
            $headers = [];
            $pathArgs = ($args[0]['pathParams'] ?? []) + $this->credentials;
            $queryArgs = $args[0]['queryParams'] ?? [];
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
                                    $headers[$parameter['name']] = $parameter['schema']['default'] ??
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

            foreach ($securitySchemas as $key => $securitySchema) {
                $headers[$securitySchema['name']] = $this->credentials[$key];
            }

            $client = new Client();
            $options = ['headers' => $headers, 'query' => $queryParams, 'json' => $args[0]['body'] ?? []];

            Log::info('Send ['.$methodOperation.'] request to `'.$uri.'`', $options);
            $this->response = $client->request($methodOperation, $uri, $options);
            
            $body = $this->response->getBody()->getContents();
            $statusCode = $this->response->getStatusCode();
            Log::info('VTEX API response from `'.$uri.'`', ['body' => $body, 'code' => $statusCode]);

            $responseArr = json_decode($body, true);
            if (null === $responseArr) {
                Log::warning('Error to parse json response body: '.json_last_error_msg());
                $responseArr = [];
            }
            
            return $responseArr;
        } catch (ClientException $clientException) {
            throw new VtexException(
                $clientException->getResponse()->getBody()->getContents(),
                $clientException->getResponse()->getStatusCode()
            );
        } catch (GuzzleException $guzzleException) {
            throw new VtexException(
                $guzzleException->getMessage()
            );
        } catch (\Exception $exception) {
            throw new VtexException(
                $exception->getMessage()
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

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @return array
     */
    public function getApi(): array
    {
        return $this->api ?? ['empty'];
    }

    /**
     * @param array $api
     *
     * @return VtexClient
     */
    public function setApi(array $api): VtexClient
    {
        $this->api = $api;

        return $this;
    }

    /**
     * @return array
     */
    public function getCredentials()
    {
        return $this->credentials;
    }
}
