<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function index(Request $request, $classString)
    {
        $modelClass = $this->resolveModelClass($classString);

        return $this->resolveEloquentResource($classString)::collection($modelClass::all());
    }

    public function show(Request $request, $classString, $id)
    {
        $modelClass = $this->resolveModelClass($classString);
        $model = $modelClass::find($id);

        $resourceClass = $this->resolveEloquentResource($classString);

        return new $resourceClass($model);
    }
    
    private function resolveModelClass ($classString)
    {
        $className = $this->resolveClassName($classString);
        if (!class_exists($className)) {
            abort(404, 'We couldn\'t find what you were looking for');
        }

        return $className;
    }

    private function resolveEloquentResource ($classString)
    {
        $className = '\\App\\Http\\Resources\\'.substr($this->resolveClassName($classString), 5).'Resource';

        if (!class_exists($className)) {
            return \App\Http\Resources\DefaultResource::class;
        }

        return $className;
    }
    
    private function resolveClassName ($classString) {
        $className = '\\App\\'.ucfirst(camel_case(\Str::singular($classString)));
        
        if (!class_exists($className)) {
            abort(404, 'We couldn\'t find what you were looking for.');
        }

        if ($this->modelHiddenFromApi($className)) {
            abort(404, 'We couldn\'t find what you were looking for.');
        }

        return $className;
    }

    private function modelHiddenFromApi($className)
    {
        return in_array($className, config('api.hidden-models'));
    }
}