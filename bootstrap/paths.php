<?php

$basePath = dirname(__DIR__);

if (file_exists($basePath . '/storage/framework/cache/data')) {
    $cachePath = $basePath . '/storage/framework/cache/data';
} else {
    $cachePath = $basePath . '/bootstrap/cache';
}

return [
    'app' => $basePath,
    'storage' => $basePath . '/storage',
    'cache' => $cachePath,
    'config' => $basePath . '/config',
    'database' => $basePath . '/database',
    'lang' => $basePath . '/lang',
    'public' => $basePath . '/public',
    'resources' => $basePath . '/resources',
    'routes' => $basePath . '/routes',
    'bootstrap' => $basePath . '/bootstrap',
];
