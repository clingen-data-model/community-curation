<?php

use Illuminate\Database\Eloquent\Model;

if (! function_exists('renderQuery')) {
    function renderQuery($query)
    {
        $treated = preg_replace('/\?/', '"%s"', $query->toSql());

        return call_user_func_array('sprintf', array_merge([$treated], $query->getBindings()));
    }
}

if (! function_exists('seedFromConfig')) {
    function seedFromConfig($config, $modelClass)
    {
        Model::unguard();
        $items = config($config);
        foreach ($items as $slug => $id) {
            $modelClass::updateOrCreate([
                'id' => $id,
                'name' => ucfirst(preg_replace('/-/', ' ', $slug)),
            ]);
        }
    }
}

// if (! function_exists('resolveBundleUrl')) {
//     function resolveBundleUrl($bundle) {
//         $manifest = json_decode(file_get_contents(base_path('/public/js/mainfest.json')));
//         return '/'
//     }
// }
