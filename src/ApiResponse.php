<?php

namespace Gtk\Gapi;

use League\Fractal\Manager;
use Illuminate\Http\JsonResponse;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class ApiResponse
{
    const CODE_BAD_REQUEST = 'GEN-BAD-REQUEST';

    const CODE_UNAUTHORIZED = 'GEN-UNAUTHORIZED';

    const CODE_FORBIDDEN = 'GEN-FORBIDDEN';

    const CODE_NOT_FOUND = 'GEN-NOT-FOUND';

    const CODE_METHOD_NOT_ALLOWED = 'GEN-METHOD-NOT-ALLOWED';

    const CODE_GONE = 'GEN-GONE';

    const CODE_INTERNAL_ERROR = 'GEN-INTERNAL-ERROR';

    protected $statusCode = 200;

    protected $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager ? : new Manager;
    }

    /**
     * Getter for statusCode
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Setter for statusCode
     *
     * @param int $statusCode Value to set
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function withArray(array $data, array $headers = [])
    {
        return new JsonResponse($data, $this->statusCode, $headers);
    }

    public function withItem($data, $transformer, $resourceKey = null, $meta = [], array $headers = [])
    {
        return $this->withArray(
            $this->makeItem($data, $transformer, $resourceKey, $meta)->toArray(),
            $headers
        );
    }

    public function withCollection($data, $transformer, $resourceKey = null, Cursor $cursor = null, $meta = [], array $headers = [])
    {
        return $this->withArray(
            $this->makeCollection($data, $transformer, $resourceKey, $cursor, $meta)->toArray(),
            $headers
        );
    }

    public function makeItem($data, $transformer, $resourceKey = null, $meta = [])
    {
        $resource = new Item($data, $transformer, $resourceKey);

        foreach ($meta as $metaKey => $metaValue) {
            $resource->setMetaValue($metaKey, $metaValue);
        }

        return $this->manager->createData($resource);
    }

    public function makeCollection($data, $transformer, $resourceKey = null, Cursor $cursor = null, $meta = [])
    {
        $resource = new Collection($data, $transformer, $resourceKey);

        foreach ($meta as $metaKey => $metaValue) {
            $resource->setMetaValue($metaKey, $metaValue);
        }

        if (! is_null($cursor)) {
            $resource->setCursor($cursor);
        }

        return $this->manager->createData($resource);
    }

    public function withPaginator(LengthAwarePaginator $paginator, $transformer, $resourceKey = null, $meta = [], array $headers = [])
    {
        $resource = new Collection($paginator->items(), $transformer, $resourceKey);

        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        foreach ($meta as $metaKey => $metaValue) {
            $resource->setMetaValue($metaKey, $metaValue);
        }

        $rootScope = $this->manager->createData($resource);

        return $this->withArray($rootScope->toArray(), $headers);
    }

    public function withError($message, $errorCode = false, array $headers = [])
    {
        if ($this->statusCode === 200) {
            trigger_error(
                "You better have a really good reason for erroring on a 200...",
                E_USER_WARNING
            );
        }

        if (false === $errorCode) {
            $errorCode = $this->getErrorCode();
        }

        return $this->withArray([
            'error' => [
                'code' => $errorCode,
                'http_code' => $this->statusCode,
                'message' => $message,
            ]
        ], $headers);
    }

    protected function getErrorCode()
    {
        $errorCodes = [
            400 => self::CODE_BAD_REQUEST,
            401 => self::CODE_UNAUTHORIZED,
            403 => self::CODE_FORBIDDEN,
            404 => self::CODE_NOT_FOUND,
            405 => self::CODE_METHOD_NOT_ALLOWED,
            410 => self::CODE_GONE,
            500 => self::CODE_INTERNAL_ERROR
        ];

        return isset($errorCodes[$this->statusCode]) ? $errorCodes[$this->statusCode] : 'GEN-UNDEFINED-ERROR-CODE';
    }

    /**
     * Generates a Response with a 400 HTTP header and a given message.
     *
     * @param string $message
     * @param array $headers
     * @return ResponseFactory
     */
    public function errorBadRequest($message = 'Bad Request', array $headers = [])
    {
        return $this->setStatusCode(400)->withError($message, self::CODE_BAD_REQUEST, $headers);
    }

    /**
     * Generates a Response with a 401 HTTP header and a given message.
     *
     * @param string $message
     * @param array $headers
     * @return ResponseFactory
     */
    public function errorUnauthorized($message = 'Unauthorized', array $headers = [])
    {
        return $this->setStatusCode(401)->withError($message, self::CODE_UNAUTHORIZED, $headers);
    }

    /**
     * Generates a Response with a 403 HTTP header and a given message.
     *
     * @param string $message
     * @param array $headers
     * @return ResponseFactory
     */
    public function errorForbidden($message = 'Forbidden', array $headers = [])
    {
        return $this->setStatusCode(403)->withError($message, self::CODE_FORBIDDEN, $headers);
    }

    /**
     * Generates a Response with a 404 HTTP header and a given message.
     *
     * @param string $message
     * @param array $headers
     * @return ResponseFactory
     */
    public function errorNotFound($message = 'Resource Not Found', array $headers = [])
    {
        return $this->setStatusCode(404)->withError($message, self::CODE_NOT_FOUND, $headers);
    }

    /**
     * Generates a Response with a 405 HTTP header and a given message.
     *
     * @param string $message
     * @param array $headers
     * @return ResponseFactory
     */
    public function errorMethodNotAllowed($message = 'Method Not Allowed', array $headers = [])
    {
        return $this->setStatusCode(405)->withError($message, self::CODE_METHOD_NOT_ALLOWED, $headers);
    }

    /**
     * Generates a Response with a 410 HTTP header and a given message.
     *
     * @param string $message
     * @param array $headers
     * @return ResponseFactory
     */
    public function errorGone($message = 'Resource No Longer Available', array $headers = [])
    {
        return $this->setStatusCode(410)->withError($message, self::CODE_GONE, $headers);
    }

    /**
     * Generates a Response with a 500 HTTP header and a given message.
     *
     * @param string $message
     * @param array $headers
     * @return ResponseFactory
     */
    public function errorInternalError($message = 'Internal Error', array $headers = [])
    {
        return $this->setStatusCode(500)->withError($message, self::CODE_INTERNAL_ERROR, $headers);
    }

    /**
     * Generates a Response with a 400 HTTP header and a given message from validator
     *
     * @param Validator $validator
     * @param array $headers
     * @return ResponseFactory
     */
    public function errorBadRequestValidator(Validator $validator, array $headers = [])
    {
        return $this->errorBadRequest($validator->getMessageBag()->toArray());
    }
}