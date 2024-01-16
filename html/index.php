<?php

use Michelf\Markdown;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

const CONTENT_PATH = __DIR__ . '/../content';

$app = AppFactory::create();

function return404(Response $response)
{
    $text = Markdown::defaultTransform(file_get_contents(CONTENT_PATH . "/pages/error_404.md"));
    $response->getBody()->write($text);
    return $response->withStatus(404);
}

$app->get('/', function (Request $request, Response $response, $args) {
    $text = Markdown::defaultTransform(file_get_contents(CONTENT_PATH . "/pages/home.md"));
    $response->getBody()->write($text);
    return $response;
});

$app->get('/pieczatki[/{woj}]', function (Request $request, Response $response, $args) {
    $woj = $args['woj'] ?? '';
    ob_start();
    require __DIR__ . '/../internal/lista.php';
    $text = ob_get_clean();
    if ($text) {
        $response->getBody()->write($text);
        return $response;
    } else {
        return return404($response);
    }
});

$app->get('/pieczatki/{woj}/{pow}', function (Request $request, Response $response, $args) {
    $woj = $args['woj'];
    $pow = $args['pow'];
    ob_start();
    require __DIR__ . '/../internal/pieczatki.php';
    $text = ob_get_clean();
    if ($text) {
        $response->getBody()->write($text);
        return $response;
    } else {
        return return404($response);
    }
});

$app->get('/pieczatki/{woj}/{pow}/{img}', function (Request $request, Response $response, $args) {
    $woj = $args['woj'];
    $pow = $args['pow'];
    $img = $args['img'];
    $filepath = CONTENT_PATH . "/pieczatki/$woj/$pow/$img";
    if (file_exists($filepath)) {
        $response->getBody()->write(file_get_contents($filepath));
        return $response->withHeader('Content-Type', 'image/jpg');
    } else {
        return return404($response);
    }
});

$app->get('/img/{image}', function (Request $request, Response $response, $args) {
    $filepath = CONTENT_PATH . "/pages/img/" . basename($args['image']);
    if (file_exists($filepath)) {
        $response->getBody()->write(file_get_contents($filepath));
        return $response->withHeader('Content-Type', 'image/jpg');
    } else {
        return return404($response);
    }
});

$app->get('/{page}', function (Request $request, Response $response, $args) {
    $filepath = CONTENT_PATH . "/pages/$args[page].md";
    if (file_exists($filepath)) {
        $text = Markdown::defaultTransform(file_get_contents($filepath));
        $response->getBody()->write($text);
        return $response;
    } else {
        return return404($response);
    }
});

$app->run();
