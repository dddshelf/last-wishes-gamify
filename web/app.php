<?php

use josegonzalez\Dotenv\Loader as DotenvLoader;
use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;

/**
 * @var Composer\Autoload\ClassLoader
 */
$loader = require __DIR__.'/../app/autoload.php';
// include_once __DIR__.'/../app/bootstrap.php.cache';

DotenvLoader::load([
    'filepath'  => __DIR__ . '/../.env',
    'putenv'    => true,
    'toEnv'     => true,
    'toServer'  => true,
]);

if ('prod' === $_SERVER['SYMFONY_ENV'] && extension_loaded('apc')) {
    $apcLoader = new ApcClassLoader(sha1(__FILE__), $loader);
    $loader->unregister();
    $apcLoader->register(true);
}

if ('dev' === $_SERVER['SYMFONY_ENV']) {
    Debug::enable();
}

$kernel = new AppKernel($_SERVER['SYMFONY_ENV'], (bool) $_SERVER['SYMFONY_DEBUG']);
// $kernel->loadClassCache();

if ('prod' === $_SERVER['SYMFONY_ENV'] && true === (bool) $_SERVER['SYMFONY___USE_REVERSE_PROXY']) {
    $kernel = new AppCache($kernel);
    Request::enableHttpMethodParameterOverride();
}

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
