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

    const CODE_UNPROCESSABLE_ENTITY = 'GEN-UNPROCESSABLE-ENTITY';

    const CODE_INTERNAL_ERROR = 'GEN-INTERNAL-ERROR';

    const CODE_UNDEFINED_ERROR_CODE = 'GEN-UNDEFINED-ERROR-CODE';

    /**
     * @var \League\Fractal\Manager
     */
    protected $manager;

    /**
     * Create a new ApiResponse instance.
     *
     * @param  \League\Fractal\Manager  $manager
     * @return void
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager ?: new Manager;
    }

    /**
     * Return a new JSON response from the application.
     *
     * @param  mixed  $data
     * @param  int  $status
     * @param  array  $headers
     * @param  int  $options
     * @return \Illuminate\Http\JsonResponse
     */
    public function json($data = [], $status = 200, array $headers = [], $options = 0)
    {
        return new JsonResponse($data, $status, $headers, $options);
    }

    /**
     * Return a new JSON response with a given model data.
     *
     * @param mixed $data
     * @param callable|\League\Fractal\TransformerAbstract $transformer
     * @param string $resourceKey
     * @param array $meta
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function withItem($data, $transformer, $resourceKey = null, $meta = [], array $headers = [])
    {
        return $this->json(
            $this->makeItem($data, $transformer, $resourceKey, $meta)->toArray(),
            200,
            $headers
        );
    }

    /**
     * Return a new JSON response with a given collection data.
     *
     * @param mixed $data
     * @param callable|\League\Fractal\TransformerAbstract $transformer
     * @param string $resourceKey
     * @param \League\Fractal\Pagination\Cursor $cursor
     * @param array $meta
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function withCollection($data, $transformer, $resourceKey = null, Cursor $cursor = null, $meta = [], array $headers = [])
    {
        return $this->json(
            $this->makeCollection($data, $transformer, $resourceKey, $cursor, $meta)->toArray(),
            200,
            $headers
        );
    }

    /**
     * Return a new JSON response with a given paginator data.
     *
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator $paginator
     * @param callable|\League\Fractal\TransformerAbstract $transformer
     * @param string $resourceKey
     * @param array $meta
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function withPaginator(LengthAwarePaginator $paginator, $transformer, $resourceKey = null, $meta = [], array $headers = [])
    {
        return $this->json(
            $this->makePaginator($paginator, $transformer, $resourceKey, $meta)->toArray(),
            200,
            $headers
        );
    }

    /**
     * Create a fractal resource with a given model data.
     *
     * @param mixed $data
     * @param callable|\League\Fractal\TransformerAbstract $transformer
     * @param string $resourceKey
     * @param array $meta
     * @return mixed
     */
    public function makeItem($data, $transformer, $resourceKey = null, $meta = [])
    {
        $resource = new Item($data, $transformer, $resourceKey);

        foreach ($meta as $metaKey => $metaValue) {
            $resource->setMetaValue($metaKey, $metaValue);
        }

        return $this->manager->createData($resource);
    }

    /**
     * Create a fractal resource with a given collection data.
     *
     * @param mixed $data
     * @param callable|\League\Fractal\TransformerAbstract $transformer
     * @param string $resourceKey
     * @param \League\Fractal\Pagination\Cursor $cursor
     * @param array $meta
     * @return mixed
     */
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

    /**
     * Create a fractal resource with a given paginator data.
     *
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator $paginator
     * @param callable|\League\Fractal\TransformerAbstract $transformer
     * @param string $resourceKey
     * @param array $meta
     * @return mixed
     */
    public function makePaginator(LengthAwarePaginator $paginator, $transformer, $resourceKey = null, $meta = [])
    {
        $resource = new Collection($paginator->items(), $transformer, $resourceKey);

        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        foreach ($meta as $metaKey => $metaValue) {
            $resource->setMetaValue($metaKey, $metaValue);
        }

        return $this->manager->createData($resource);
    }

    /**
     * Create a new JSON response with an error.
     *
     * @param  mixed $message
     * @param  mixed $code
     * @param  int  $status
     * @param  array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function withError($message = '', $code = null, $status = 422, array $headers = [])
    {
        return $this->json([
            'error' =>  array_merge([
                'code' => $code ?: $this->getDefaultErrorCode($status),
                'http_code' => $status,
            ], is_array($message) ? $message : compact('message')),
        ], $status, $headers);
    }

    /**
     * Get the default error code associated with the HTTP status code.
     *
     * @param  int  $status
     * @return mixed
     */
    protected function getDefaultErrorCode($status)
    {
        $errorCodes = [
            400 => self::CODE_BAD_REQUEST,
            401 => self::CODE_UNAUTHORIZED,
            403 => self::CODE_FORBIDDEN,
            404 => self::CODE_NOT_FOUND,
            405 => self::CODE_METHOD_NOT_ALLOWED,
            410 => self::CODE_GONE,
            422 => self::CODE_UNPROCESSABLE_ENTITY,
            500 => self::CODE_INTERNAL_ERROR,
        ];

        return isset($errorCodes[$status]) ? $errorCodes[$status] : self::CODE_UNDEFINED_ERROR_CODE;
    }

    /**
     * Create a JSON response with a 400 HTTP status code and a given message.
     *
     * @param mixed $message
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorBadRequest($message = 'Bad Request', array $headers = [])
    {
        return $this->withError($message, self::CODE_BAD_REQUEST, 400, $headers);
    }

    /**
     * Create a JSON response with a 401 HTTP status code and a given message.
     *
     * @param string $message
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorUnauthorized($message = 'Unauthorized', array $headers = [])
    {
        return $this->withError($message, self::CODE_UNAUTHORIZED, 401, $headers);
    }

    /**
     * Create a JSON response with a 403 HTTP status code and a given message.
     *
     * @param string $message
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorForbidden($message = 'Forbidden', array $headers = [])
    {
        return $this->withError($message, self::CODE_FORBIDDEN, 403, $headers);
    }

    /**
     * Create a JSON response with a 404 HTTP status code and a given message.
     *
     * @param string $message
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorNotFound($message = 'Resource Not Found', array $headers = [])
    {
        return $this->withError($message, self::CODE_NOT_FOUND, 404, $headers);
    }

    /**
     * Create a JSON response with a 405 HTTP status code and a given message.
     *
     * @param string $message
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorMethodNotAllowed($message = 'Method Not Allowed', array $headers = [])
    {
        return $this->withError($message, self::CODE_METHOD_NOT_ALLOWED, 405, $headers);
    }

    /**
     * Create a JSON response with a 410 HTTP status code and a given message.
     *
     * @param string $message
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorGone($message = 'Resource No Longer Available', array $headers = [])
    {
        return $this->withError($message, self::CODE_GONE, 410, $headers);
    }

    /**
     * Create a JSON response with a 422 HTTP status code and a given message.
     *
     * @param string $message
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorUnprocessableEntity($message = 'Unprocessable Entity', array $headers = [])
    {
        return $this->withError($message, self::CODE_UNPROCESSABLE_ENTITY, 422, $headers);
    }

    /**
     * Create a JSON response with a 500 HTTP status code and a given message.
     *
     * @param string $message
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorInternalError($message = 'Internal Error', array $headers = [])
    {
        return $this->withError($message, self::CODE_INTERNAL_ERROR, 500, $headers);
    }

    /**
     * Create a JSON response with a 400 HTTP status code and a given message from validator
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorBadRequestValidator(Validator $validator, array $headers = [])
    {
        return $this->errorBadRequest($validator->getMessageBag()->toArray());
    }
}
