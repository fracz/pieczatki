<?php

use Michelf\Markdown;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../get-stamps.php';

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
    return $phpView->render($response, "home.php", ['stamps' => getStamps()]);
});

$app->get('/pieczatki[/{woj:.+}]', function (Request $request, Response $response, $args) use ($phpView) {
    $woj = $args['woj'] ?? '';
    $filepath = CONTENT_PATH . "/pieczatki/$woj";
    $stamps = getStamps();
    if (is_dir($filepath)) {
        if ($woj) {
            foreach (explode('/', $woj) as $part) {
                $stamps = $stamps[$part];
            }
        }
        if (isset($stamps['images'])) {
            return $phpView->render($response, "gallery.php", ['subdir' => $woj, 'stamps' => $stamps]);
        } else {
            return $phpView->render($response, "home.php", ['subdir' => $woj, 'stamps' => $stamps]);
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

$app->get('/{page}', function (Request $request, Response $response, $args) use ($phpView) {
    $filepath = CONTENT_PATH . "/pages/$args[page].md";
    if (file_exists($filepath)) {
        return $phpView->render($response, "markdown.php", ['filepath' => $filepath]);
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
