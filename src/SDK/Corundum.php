<?php

namespace Bavix\SDK;

use Bavix\Exceptions\Invalid;
use Bavix\Helpers\Arr;
use Bavix\Helpers\JSON;
use Bavix\Helpers\Str;
use Bavix\Slice\Slice;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class Corundum
{

    /**
     * @var array
     */
    protected $tokens = [];

    /**
     * @var string
     */
    protected $urlToken;

    /**
     * @var string
     */
    protected $basic;

    /**
     * @var Slice
     */
    protected $slice;

    /**
     * @var Slice
     */
    protected $fake;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var Slice
     */
    protected $results;

    /**
     * Corundum constructor.
     *
     * @param Slice $slice
     */
    public function __construct(Slice $slice)
    {
        $clientId = $slice->getRequired('app.client_id');
        $secret   = $slice->getRequired('app.client_secret');

        $this->slice    = $slice;
        $this->basic    = \base64_encode($clientId . ':' . $secret);
        $this->urlToken = $slice->getRequired('app.url.token');
    }

    /**
     * @return array
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }

    /**
     * @param array $tokens
     */
    public function setTokens(array $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * @return Slice
     */
    protected function fake(): Slice
    {
        if (!$this->fake)
        {
            $this->fake = new Slice([]);
        }

        return $this->fake;
    }

    /**
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getBody(): string
    {
        return $this->response->getBody()
            ->getContents();
    }

    /**
     * @return Slice|null
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @return int|null
     */
    public function getCode()
    {
        if ($this->response)
        {
            return $this->response->getStatusCode();
        }

        return null;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    protected function value($value)
    {
        if (Str::first($value) === '@')
        {
            return fopen(Str::withoutFirst($value), 'rb');
        }

        return $value;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    protected function multipart(array $params): array
    {
        $results = [];

        foreach ($params as $key => $value)
        {
            $content = [
                'name'     => $key,
                'contents' => $this->value($value)
            ];

            if (\is_resource($content['contents']))
            {
                $content['filename'] = basename($value);
            }

            $results[] = $content;
        }

        return $results;
    }

    /**
     * @param Slice $options
     *
     * @return Slice
     *
     * @throws Invalid
     */
    protected function apiRequest(Slice $options): Slice
    {
        $type    = $options->getData('token_type', 'Basic');
        $method  = $options->getData('method', 'POST');
        $token   = $options->getData('access_token', $this->basic);
        $url     = $options->getData('url', $this->urlToken);
        $params  = $options->getData('params', []);
        $headers = $options->getData('headers', []);

        // headers
        $headers['Authorization'] = $type . ' ' . $token;

        $client = new Client();

        $data = [
            'headers'   => $headers,
            'multipart' => $this->multipart($params),
        ];

        $this->response = $client->request(
            Str::upp($method),
            $url,
            $data
        );

        $this->results = null;

        $response = JSON::decode(
            $this->getBody()
        );

        if (JSON::errorNone())
        {
            $this->results = new Slice($response);
        }

        $code = $this->getCode();

        if ($code < 200 || $code > 299)
        {
            throw new Invalid(
                'Error: ' . $this->response->getReasonPhrase(),
                $code
            );
        }

        return $this->getResults();
    }

    /**
     * @param string $user
     * @param array  $params
     *
     * @return Slice
     *
     * @throws Invalid
     */
    protected function authorize(string $user, array $params = []): Slice
    {
        $grantType = $this->slice->getData('grant_type', 'password');
        $userData  = $this->slice->getSlice('users.' . $user);
        $defaults  = [
            'username'   => $userData->getRequired('username'),
            'password'   => $userData->getRequired('password'),
            'grant_type' => $grantType,
        ];

        $response = $this->apiRequest(new Slice([
            'params' => Arr::merge($defaults, $params)
        ]));

        $response->expires = Carbon::now()
            ->addSeconds($response->expires_in);

        return $response;
    }

    /**
     * @param string $user
     * @param string $scope
     *
     * @return Slice
     *
     * @throws Invalid
     */
    protected function getToken(string $user, string $scope = ''): Slice
    {
        if (!Arr::keyExists($this->tokens, $user))
        {
            $this->tokens[$user] = $this->authorize($user, [
                'scope' => $scope
            ]);
        }

        return $this->tokens[$user];
    }

    /**
     * @param string $user
     *
     * @return Slice
     *
     * @throws Invalid
     */
    protected function refreshToken(string $user): Slice
    {
        /**
         * @var $token Slice
         */
        $token = $this->tokens[$user];

        return $this->tokens[$user] = $this->authorize($user, [
            'grant_type'    => 'refresh_token',
            'refresh_token' => $token->getRequired('refresh_token'),
        ]);
    }

    /**
     * @param string $user
     * @param string $file
     * @param Slice  $options
     *
     * @return Slice
     *
     * @throws Invalid
     */
    public function upload(string $user, string $file, Slice $options = null): Slice
    {
        if (!$options)
        {
            $options = $this->fake();
        }

        $token = $this->getToken(
            $user,
            $options->getData('scope', '')
        );

        try
        {
            return $this->apiRequest(
                $this->uploadSlice($token, $options, $file)
            );
        }
        catch (\Throwable $throwable)
        {
            return $this->apiRequest(
                $this->uploadSlice(
                    $this->tokenUpdate($user, $options),
                    $options,
                    $file
                )
            );
        }

    }

    /**
     * @param Slice $token
     * @param Slice $options
     *
     * @return Slice
     */
    protected function uploadSlice(Slice $token, Slice $options, string $file): Slice
    {
        return new Slice([
            'token_type'   => $token->getRequired('token_type'),
            'access_token' => $token->getRequired('access_token'),
            'method'       => $options->getData('method', 'post'),
            'url'          => $this->slice->getRequired('app.url.upload'),

            'headers' => Arr::merge($options->getData('headers', []), [
                'Accept' => 'application/json'
            ]),

            'params' => Arr::merge($options->getData('params', []), [
                'file' => '@' . \ltrim($file, '@')
            ])
        ]);
    }

    /**
     * @param Slice $token
     *
     * @return Slice
     *
     * @throws Invalid
     */
    protected function verify(Slice $token): Slice
    {
        return $this->apiRequest(new Slice([
            'token_type'   => $token->getRequired('token_type'),
            'access_token' => $token->getRequired('access_token'),
            'url'          => $this->slice->getRequired('app.url.verify'),
            'headers'      => [
                'Accept' => 'application/json'
            ]
        ]));
    }

    /**
     * @param string $user
     * @param Slice  $slice
     *
     * @return Slice
     *
     * @throws Invalid
     */
    protected function tokenUpdate(string $user, Slice $slice): Slice
    {
        try
        {
            $token = $this->refreshToken($user);

            if (!$this->verify($token))
            {
                throw new Invalid('The token isn\'t verified');
            }
        }
        catch (Invalid $invalid)
        {
            $this->tokens[$user] = null;

            $token = $this->getToken(
                $user,
                $slice->getData('scope', '')
            );
        }

        return $token;
    }

}
