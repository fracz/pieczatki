<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../get-stamps.php';

const CONTENT_PATH = __DIR__ . '/../content';
const DESCRIPTIONS_PATH = __DIR__ . '/../var/descriptions.php';

$descriptions = require DESCRIPTIONS_PATH;

if (preg_match('#^/(css|img|js|lib)/#', $_SERVER["REQUEST_URI"])) {
    return false;
}

session_cache_limiter(false);
session_start();

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$phpView = new PhpRenderer(__DIR__ . "/../templates", ['title' => 'Polskie Pieczątki Turystyczne']);
$phpView->setLayout('layout.php');

$return404 = function (Response $response) use ($phpView) {
    return $phpView->render($response, "markdown.php", ['filepath' => CONTENT_PATH . "/pages/error_404.md"])
        ->withStatus(404);
};

$app->get('/', function (Request $request, Response $response, $args) use ($phpView) {
    return $phpView->render($response, "home.php", ['stamps' => getStamps()]);
});

$app->get('/login', function (Request $request, Response $response, $args) use ($phpView) {
    return $phpView->render($response, "login.php");
});

$app->post('/login', function (Request $request, Response $response, $args) use ($phpView) {
    $body = $request->getParsedBody();
    if ((sha1('yuve' . ($body['password'] ?? ''))) === 'c93f1b18258b016e8a11f585556be57b86ef1e10') {
        $_SESSION['loggedIn'] = true;
        return $response->withStatus(301)->withHeader('Location', '/');
    } else {
        return $phpView->render($response, "login.php");
    }
});

$app->get('/logout', function (Request $request, Response $response, $args) use ($phpView) {
    session_destroy();
    return $response->withStatus(301)->withHeader('Location', '/');
});

$app->get('/pieczatki[/{woj:.+}]', function (Request $request, Response $response, $args) use ($descriptions, $return404, $phpView) {
    $woj = $args['woj'] ?? '';
    $stamps = getStamps();
    if ($woj) {
        foreach (explode('/', $woj) as $part) {
            $stamps = $stamps[$part] ?? null;
            if (!$stamps) {
                return $return404($response);
            }
        }
    }
    if ($stamps['images'] ?? null) {
        return $phpView->render($response, "gallery.php", ['subdir' => $woj, 'stamps' => $stamps, 'descriptions' => $descriptions]);
    } else {
        return $phpView->render($response, "home.php", ['subdir' => $woj, 'stamps' => $stamps]);
    }
});

$app->get('/szukaj', function (Request $request, Response $response, $args) use ($descriptions, $return404, $phpView) {
    $params = $request->getQueryParams();
    $hits = [];
    $slugify = new Cocur\Slugify\Slugify();
    $phrase = $slugify->slugify($params['q'] ?? '');
    if ($phrase) {
        $isHit = fn($str) => str_contains($slugify->slugify($str), $phrase);
        foreach ($descriptions as $filepath => $info) {
            if ($isHit(basename($filepath)) || array_filter($info, $isHit)) {
                $hits[$filepath] = $info;
            }
        }
    }
    return $phpView->render($response, "search.php", ['phrase' => $params['q'] ?? '', 'hits' => $hits]);
});

$app->put('/update', function (Request $request, Response $response, $args) use ($return404, $phpView, &$descriptions) {
    if ($_SESSION['loggedIn'] ?? false) {
        $body = $request->getParsedBody();
        $filename = $body['filename'];
        unset($body['filename']);
        $descriptions[$filename] = $body;
        file_put_contents(DESCRIPTIONS_PATH, "<?php\nreturn " . var_export($descriptions, true) . ';', LOCK_EX);
        return $response;
    } else {
        return $return404($response);
    }
});

$app->get('/{page}', function (Request $request, Response $response, $args) use ($return404, $phpView) {
    $filepath = CONTENT_PATH . "/pages/$args[page].md";
    if (file_exists($filepath)) {
        return $phpView->render($response, "markdown.php", ['filepath' => $filepath]);
    } else {
        return $return404($response);
    }
});

$app->get('/media/{path:.+\.[jpsJPS][pnvPNV][gG]$}', function (Request $request, Response $response, $args) use ($return404) {
    $path = str_replace('..', '', $args['path']);
    $filepath = CONTENT_PATH . "/pieczatki/" . $path;
    if (file_exists($filepath)) {
        $response->getBody()->write(file_get_contents($filepath));
        return $response->withHeader('Content-Type', mime_content_type($filepath));
    } else {
        return $return404($response);
    }
});

$app->get('/static/{image}', function (Request $request, Response $response, $args) use ($return404) {
    $filepath = CONTENT_PATH . "/pages/static/" . basename($args['image']);
    if (file_exists($filepath)) {
        $response->getBody()->write(file_get_contents($filepath));
        return $response->withHeader('Content-Type', mime_content_type($filepath));
    } else {
        return $return404($response);
    }
});

$app->run();
