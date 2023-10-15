<?php

use app\src\controller\AuthController;
use app\src\controller\MainController;
use app\src\controller\OpenController;
use app\src\core\lib\Psr4AutoloaderClass;
use app\src\model\Application;

require_once __DIR__ . '/../src/core/lib/Psr4AutoloaderClass.php';
require_once __DIR__ . '/../src/config.php';

if (session_status() == PHP_SESSION_NONE)
    session_start();

$loader = new Psr4AutoloaderClass();
$loader->register();
$loader->addNamespace('app', __DIR__ . '/../');

$app = new Application(dirname(__DIR__));

//$app->on(Application::EVENT_BEFORE_REQUEST, function () {
//    echo "Before request";
//});
//
//$app->on(Application::EVENT_AFTER_REQUEST, function () {
//    echo "After request";
//});

$app->router->get('/', 'home');

$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);

$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);

$app->router->get('/pro_login', [AuthController::class, 'pro_login']);
$app->router->post('/pro_login', [AuthController::class, 'pro_login']);

$app->router->get('/logout', [AuthController::class, 'logout']);

$app->router->get('/contact', [MainController::class, 'contact']);

$app->router->get('/about', [OpenController::class, 'index']);

$app->router->get('/profile', [MainController::class, 'profile']);
$app->router->get('/profile/{id}', [MainController::class, 'profile']);

$app->router->get('/edit_profile', [MainController::class, 'edit_profile']);
$app->router->get('/edit_profile/{id}', [MainController::class, 'edit_profile']);
$app->router->post('/edit_profile', [MainController::class, 'edit_profile']);
$app->router->post('/edit_profile/{id}', [MainController::class, 'edit_profile']);

$app->router->get('/search', [MainController::class, 'search']);

$app->router->get('/readAll', [MainController::class, 'readAll']);

$app->router->get('/mailtest', [MainController::class, 'mailtest']);

$app->router->get('/offres', [MainController::class, 'offres']);

$app->router->get('/entreprises', [MainController::class, 'entreprises']);
$app->router->get('/entreprises/{id:\d+}', [MainController::class, 'entreprises']);

$app->router->get('/offres/create', [MainController::class, 'creeroffre']);
$app->router->post('/offres/create', [MainController::class, 'creeroffre']);


$app->router->get('/offres/{id:\d+}', [MainController::class, 'offres']);

$app->router->get('/offres/{id:\d+}/postuler', [MainController::class, 'postuler']);
$app->router->post('/offres/{id:\d+}/postuler', [MainController::class, 'postuler']);

$app->router->post('/offres/{id:\d+}/edit', [MainController::class, 'editOffre']);
$app->router->post('/offres/{id:\d+}/archive', [MainController::class, 'archiveOffre']);

$app->router->post('/offres/{id:\d+}/edit', [MainController::class, 'editOffre']);

$app->router->get('/offres/{id:\d+}/edit', [MainController::class, 'editOffre']);

$app->router->get('/offres/{id:\d+}/validate', [MainController::class, 'validateOffre']);
$app->router->get('/offres/{id:\d+}/archive', [MainController::class, 'archiveOffre']);


$app->router->get('/dashboard', [MainController::class, 'dashboard']);
$app->router->get('/user_test/{id}', [MainController::class, 'user_test']);

$app->router->get('/candidatures', [MainController::class, 'candidatures']);
$app->router->get('/candidatures/{id:\d+}', [MainController::class, 'candidatures']);
$app->router->post('/candidatures', [MainController::class, 'candidatures']);
$app->router->get('/utilisateurs/{id}/archiver', [MainController::class, 'archiver']);

$app->router->get('/utilisateurs', [MainController::class, 'utilisateurs']);
$app->router->get('/utilisateurs/{id}', [MainController::class, 'utilisateurs']);
$app->router->post('/utilisateurs/{id}', [MainController::class, 'utilisateurs']);

$app->router->get('/ListeTuteurPro', [MainController::class, 'ListeTuteurPro']);

$app->run();