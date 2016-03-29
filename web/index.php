<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../app/AppKernel.php";

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

$request = Request::createFromGlobals();
$kernel = new AppKernel('dev', true);

Debug::enable();

$kernel->loadClassCache();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);