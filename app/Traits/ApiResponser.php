<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

trait ApiResponser
{
    /**
     * @param  mixed  $data
     * @param  mixed  $code
     * @return \Illuminate\Http\JsonResponse
     */
    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    /**
     * @param  mixed  $message
     * @param  int  $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    /**
     * @param string $message
     * @param int $code
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showError($message = 'An error ocurred', $code = 400)
    {
        return $this->errorResponse($message, $code);
    }

    /**
     * @param  $collection
     * @param  int  $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showAll($collection, $code = 200, $paginate = true)
    {
        if ($collection->isEmpty()) {
            return $this->successResponse(['data' => $collection], $code);
        }
        if ($paginate) {
            $collection = $this->paginate($collection);
        }
        // $collection = $this->cacheResponse($collection);

        return $this->successResponse($collection, $code);
    }

    protected function showOne($instance, $code = 200)
    {
        return $this->successResponse($instance, $code);
    }

    /**
     * @param  mixed  $message
     * @param  int  $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse(['data' => $message], $code);
    }

    protected function paginate($collection)
    {
        $rules = [
            'per_page' => 'integer|min:2|max:50',
        ];

        // get current page
        $page = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 10;
        Validator::validate(request()->all(), $rules);

        if (request()->has('per_page')) {
            $perPage = (int) request()->per_page;
        }

        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        $paginated->appends(request()->all());

        return $paginated;
    }

    /**
     * @param  mixed  $data
     * @return \Illuminate\Support\Facades\Cache
     */
    protected function cacheResponse($data)
    {
        $url = request()->url();
        $queryParams = request()->query();

        ksort($queryParams);

        $queryString = http_build_query($queryParams);

        $fullUrl = "{$url}?{$queryString}";

        return Cache::remember($fullUrl, 60, function () use ($data) {
            return $data;
        });
    }

    public function authUser(): User
    {
        return auth()->user();
    }
}
