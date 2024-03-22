<?php
namespace App\Support\Http;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\ParameterBag;

abstract class GuzzleHttpRequest implements HttpServiceInterface
{
    /**
     * @var string
     */
    protected $method;
    /**
     * @var string
     */
    protected $url;
    /**
     * @var ParameterBag
     */
    protected $parameters;

    /**
     * @var ParameterBag
     */
    protected $headers;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var bool
     */
    protected $json = true;

    /**
     * @var HttpResponseInterface
     */
    protected $response;

    /**
     * GuzzleHttpRequest constructor.
     *
     * @param Client $client
     * @param HttpResponseInterface $response
     */
    public function __construct(Client $client, HttpResponseInterface $response)
    {
        $this->client = $client;
        $this->response = $response;
        $this->parameters = $this->headers = new ParameterBag();
    }

    /**
     * @return mixed
     * @throws GuzzleException
     */
    public function send()
    {
        try {
            $content = $this->client->request(
                $this->getMethod(),
                $this->getUrl(),
                $this->getOptions()
            )->getBody()->getContents();

            return $this->getResponse($content);
        } catch (GuzzleException $exception) {
            return $this->handler($exception);
        }
    }

    /**
     * @param GuzzleException $exception
     * @throws GuzzleException
     */
    protected function handler(GuzzleException $exception)
    {
        throw $exception;
    }

    /**
     * @return string
     */
    protected function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return $this->method;
    }

    protected function getOptions()
    {
        $options = [];

        if ($this->isMethod('get')) {
            $key = \GuzzleHttp\RequestOptions::QUERY;
        } else {
            if ($this->json) {
                $key = \GuzzleHttp\RequestOptions::JSON;
            } else {
                $key = \GuzzleHttp\RequestOptions::FORM_PARAMS;
            }
        }

        $options[$key] = $this->getParameters();

        $headers = [];

        if ($this->getHeaders()) {
            foreach ($this->getHeaders() as $key => $value) {
                if (is_array($value)) {
                    continue;
                }
                $headers[$key] = $value;
            }
        }

        $options['headers'] = $headers;

        return $options;
    }

    /**
     * @param $content
     *
     * @return mixed
     */
    protected function getResponse($content)
    {
        return $this->response->getContent($content);
    }

    /**
     * @return array
     */
    protected function getParameters()
    {
        return array_merge($this->parameters->all(), $this->extraParams());
    }

    /**
     * @return array
     */
    protected function extraParams()
    {
        return [];
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    protected function setParameter($name, $value)
    {
        $this->parameters->set($name, $value);

        return $this;
    }

    /**
     * @param $name
     * @param $value
     *
     * @return GuzzleHttpRequest
     */
    protected function setHeader($name, $value)
    {
        $this->headers->set($name, $value);

        return $this;
    }

    /**
     * @param $method
     *
     * @return bool
     */
    protected function isMethod($method)
    {
        return strtolower($method) == strtolower($this->getMethod());
    }

    /**
     * @return array
     */
    protected function getHeaders()
    {
        return $this->headers->all();
    }
}
