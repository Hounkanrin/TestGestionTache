<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once dirname(__DIR__).'/vendor/autoload.php';
require_once dirname(__DIR__).'/classes/Util.class.php';

$app = new \Slim\App();

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
    ->withHeader('Access-Control-Allow-Origin', '*')
    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

// ajouter tache
$app->post('/task/add', function (Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $util = new ApiUtils();
    $util->setData($data);
    return $util->saveData();
});

// supprimer tache
$app->delete('/task/delete/{id}', function (Request $request, Response $response, array $args) {
    $util = new ApiUtils();
    $data[ApiUtils::ID] = $args['id'];
    $util->setData($data);
    $util->saveData(false,true);
    return $util->getData();
});

// modifier tache
$app->put('/task/update/{id}', function (Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $util = new ApiUtils();
    $data[ApiUtils::ID] = $args['id'];
    $util->setData($data);
    return $util->saveData(false);
});

// lister tache
$app->get('/task/list[/{type}]', function (Request $request, Response $response, array $args) {
    $util = new ApiUtils();
    $type = isset($args['type'])? $args['type'] : null;
    return $util->getData(null,$type);
});

// lister tache par ID
$app->get('/task/listid[/{id}]', function (Request $request, Response $response, array $args) {
    $util = new ApiUtils();
    $id = isset($args['id'])? $args['id'] : null;
    return $util->getData($id);
});
     
$app->run();