<?php

use Michelf\Markdown;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;

require __DIR__ . '/../vendor/autoload.php';

const CONTENT_PATH = __DIR__ . '/../content';

if (preg_match('/(css|img|js|lib)/', $_SERVER["REQUEST_URI"])) {
    return false;
}

$app = AppFactory::create();
$phpView = new PhpRenderer(__DIR__ . "/../templates", ['title' => 'Polskie Pieczątki Turystyczne']);
$phpView->setLayout('layout.php');

function return404(Response $response)
{
    $text = Markdown::defaultTransform(file_get_contents(CONTENT_PATH . "/pages/error_404.md"));
    $response->getBody()->write($text);
    return $response->withStatus(404);
}

$app->get('/', function (Request $request, Response $response, $args) use ($phpView) {
    return $phpView->render($response, "home.php");
});

$app->get('/pieczatki[/{woj:.+}]', function (Request $request, Response $response, $args) use ($phpView) {
    $woj = $args['woj'] ?? '';
    $filepath = CONTENT_PATH . "/pieczatki/$woj";
    if (is_dir($filepath)) {
        if (file_exists($filepath . '/_list.yml')) {
            return $phpView->render($response, "gallery.php", ['subdir' => $woj]);
        } else {
            return $phpView->render($response, "home.php", ['subdir' => $woj]);
        }
    } else {
        return return404($response);
    }
});

//$app->get('/img/{image}', function (Request $request, Response $response, $args) {
//    $filepath = CONTENT_PATH . "/pages/img/" . basename($args['image']);
//    if (file_exists($filepath)) {
//        $response->getBody()->write(file_get_contents($filepath));
//        return $response->withHeader('Content-Type', 'image/jpg');
//    } else {
//        return return404($response);
//    }
//});

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

$app->get('/{path:.+\.[pngjsv]{3}$}', function (Request $request, Response $response, $args) {
    $path = str_replace('..', '', $args['path']);
    $filepath = CONTENT_PATH . "/pieczatki/" . $path;
    if (file_exists($filepath)) {
        $response->getBody()->write(file_get_contents($filepath));
        $parts = explode('.', $path);
        $contentType = ['png' => 'image/png', 'jpg' => 'image/jpg'][strtolower(end($parts))] ?? 'image/svg+xml';
        return $response->withHeader('Content-Type', $contentType);
    } else {
        return return404($response);
    }
});

$app->run();
