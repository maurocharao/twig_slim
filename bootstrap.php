<?php

use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use app\src\Image;
use app\helpers\TwigFilters;
use app\helpers\TwigFunctions;

date_default_timezone_set('America/Bahia');
error_reporting(E_ALL);

session_start();

require "vendor/autoload.php";

$app = AppFactory::create();

// The routing middleware should be added earlier than the ErrorMiddleware
// Otherwise exceptions thrown from it will not be handled by the middleware
$app->addRoutingMiddleware();

// add Twig
$twig = Twig::create(DIR_ROOT.'/app/views/', [ 'cache' => false ]);
// add Twig-View Middleware
$app->add(TwigMiddleware::create($app, $twig));
// add Twig Extensions
$twig->addExtension(new TwigFilters);
$twig->addExtension(new TwigFunctions);
// add Twig globals
$admin = (new \app\models\admin\Admin)->user();
if($admin && $admin->photo) {
  $admin->photo = '/'.Image::IMG_PATH.$admin->photo;
}
$twig->getEnvironment()->addGlobal('admin', $admin);

// This middleware should be added last. It will not handle any exceptions/errors for middleware added after it
$displayErrorDetails = true; // should be set to false in production
$logErrors = true; // parameter is passed to the default ErrorHandler
$logErrorDetails = true; // display error details in error log
$PSR3Logger = null; // optional
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, $logErrors, $logErrorDetails, $PSR3Logger);
