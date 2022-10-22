<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

trait ApiResponser{

    /**
     * @param mixed $data
     * @param mixed $code
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    private function successResponse($data, $code){
        return response()->json($data, $code);
    }

    /**
     * @param mixed $message
     * @param mixed $code
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($message, $code){
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showError($message = "An error ocurred", $code = 400)
    {
        return $this->errorResponse($message,$code);
    }


    /**
     * @param Collection<int, Model> $collection 
     * @param int $code
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showAll(Collection $collection, $code = 200){

        if($collection->isEmpty()){
            return $this->successResponse(['data' => $collection],$code);
        }

        $collection = $this->paginate($collection);
        // $collection = $this->cacheResponse($collection);	
        
        return $this->successResponse($collection,$code);
    }

    /**
     * @param Model $instance
     * @param int $code
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showOne(Model $instance, $code = 200){
        return $this->successResponse($instance,$code);
    }

    
    /**
     * @param Object $instance
     * @param int $code
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showOneObject(Object $instance, $code = 200){
        return $this->successResponse($instance,$code);
    }

    /**
     * @param mixed $message
     * @param int $code
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showMessage($message, $code = 200){
        return $this->successResponse(['data'=> $message],$code);
    }



    /**
     * @param Collection<int, Model> $collection
     * 
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected function paginate(Collection $collection){

        $rules = [
            'per_page' => 'integer|min:2|max:50',
        ];

       // get current page
        $page = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 5;
        Validator::validate(request()->all(), $rules);

        if(request()->has('per_page')){
            $perPage = (int) request()->per_page;
        }
        
        $results = $collection->slice(($page - 1) * $perPage, $perPage )->values();

        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' =>LengthAwarePaginator::resolveCurrentPath()
        ]);

        $paginated->appends(request()->all());

        return $paginated;
        
    }


    /**
     * @param mixed $data
     * 
     * @return \Illuminate\Support\Facades\Cache
     */
    protected function cacheResponse($data)
	{
		$url = request()->url();
		$queryParams = request()->query();

		ksort($queryParams);

		$queryString = http_build_query($queryParams);

		$fullUrl = "{$url}?{$queryString}";

		return Cache::remember($fullUrl, 60, function() use($data) {
			return $data;
		});
	}


}