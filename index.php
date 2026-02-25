<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;

require_once __DIR__ . '/vendor/autoload.php';
$pdo = require_once __DIR__ . '/pdo.php';

const CONTENT_PATH = __DIR__ . '/content';

if (preg_match('#^/(css|img|js|lib)/#', $_SERVER["REQUEST_URI"])) {
    return false;
}

session_cache_limiter(false);
session_start();

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$phpView = new PhpRenderer(__DIR__ . "/templates", ['title' => 'Polskie Pieczątki Turystyczne']);
$phpView->setLayout('layout.php');

$return404 = function (Response $response) use ($phpView) {
    return $phpView->render($response, "markdown.php", ['filepath' => CONTENT_PATH . "/pages/error_404.md"])
        ->withStatus(404);
};

$app->get('/', function (Request $request, Response $response, $args) use ($phpView, $pdo) {
    return $phpView->render($response, "home.php", [
        'regions' => getRegions($pdo),
        'totalCount' => getTotalStampsCount($pdo)
    ]);
});

$app->get('/login', function (Request $request, Response $response, $args) use ($phpView) {
    return $phpView->render($response, "login.php");
});

$app->post('/login', function (Request $request, Response $response, $args) use ($phpView) {
    $body = $request->getParsedBody();
    if ((sha1('yuve' . ($body['password'] ?? ''))) === '173511dfcbb4564c191d3c6f52ed1bb8ed1660db') {
        $_SESSION['loggedIn'] = true;
        return $response->withStatus(301)->withHeader('Location', '/admin');
    } else {
        return $phpView->render($response, "login.php");
    }
});

$app->get('/logout', function (Request $request, Response $response, $args) use ($phpView) {
    session_destroy();
    return $response->withStatus(301)->withHeader('Location', '/');
});

$app->get('/pieczatki[/{path:.+}]', function (Request $request, Response $response, $args) use ($pdo, $return404, $phpView) {
    $path = $args['path'] ?? '';
    $parts = explode('/', $path);

    if (!$path) {
        return $response->withStatus(301)->withHeader('Location', '/');
    }

    $regionSlug = $parts[0];
    $region = getRegionBySlug($pdo, $regionSlug);
    if (!$region) {
        return $return404($response);
    }

    if (count($parts) === 1) {
        // List counties in region
        return $phpView->render($response, "home.php", [
            'region' => $region,
            'counties' => getCounties($pdo, $region['id']),
            'subdir' => $region['name']
        ]);
    }

    $countySlug = $parts[1];
    $county = getCountyBySlug($pdo, $region['id'], $countySlug);
    if (!$county) {
        return $return404($response);
    }

    // List images in county
    return $phpView->render($response, "gallery.php", [
        'region' => $region,
        'county' => $county,
        'images' => getImages($pdo, $county['id']),
        'subdir' => $region['name'] . '/' . $county['name']
    ]);
});

$app->get('/szukaj', function (Request $request, Response $response, $args) use ($pdo, $phpView) {
    $params = $request->getQueryParams();
    $q = $params['q'] ?? '';
    $hits = [];
    if ($q) {
        $stmt = $pdo->prepare("
            SELECT i.*, r.slug as region_slug, c.slug as county_slug
            FROM image i
            JOIN county c ON i.county_id = c.id
            JOIN region r ON c.region_id = r.id
            WHERE i.location LIKE ? OR i.description LIKE ? OR i.filename LIKE ?
            LIMIT 100
        ");
        $term = "%$q%";
        $stmt->execute([$term, $term, $term]);
        $hits = $stmt->fetchAll();
    }
    return $phpView->render($response, "search.php", ['phrase' => $q, 'hits' => $hits]);
});

$app->put('/update', function (Request $request, Response $response, $args) use ($pdo, $return404) {
    if ($_SESSION['loggedIn'] ?? false) {
        $body = $request->getParsedBody();
        $stmt = $pdo->prepare("
            UPDATE image 
            SET location = ?, years = ?, dimensions = ?, description = ?, gccode = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $body['location'] ?? null,
            $body['years'] ?? null,
            $body['dimensions'] ?? null,
            $body['description'] ?? null,
            $body['gccode'] ?? null,
            $body['id']
        ]);
        return $response;
    } else {
        return $return404($response);
    }
});

$app->get('/admin', function (Request $request, Response $response, $args) use ($pdo, $phpView) {
    if (!($_SESSION['loggedIn'] ?? false)) {
        return $response->withStatus(302)->withHeader('Location', '/login');
    }
    return $phpView->render($response, "admin.php", [
        'totalCount' => getTotalStampsCount($pdo)
    ]);
});

$app->get('/admin/edit', function (Request $request, Response $response, $args) use ($pdo, $phpView) {
    if (!($_SESSION['loggedIn'] ?? false)) {
        return $response->withStatus(302)->withHeader('Location', '/login');
    }
    $filters = $request->getQueryParams();
    return $phpView->render($response, "admin_edit.php", [
        'regions' => getAllRegions($pdo),
        'counties' => getAllCounties($pdo),
        'images' => searchImagesAdmin($pdo, $filters),
        'filters' => $filters
    ]);
});

$app->post('/admin/update-batch', function (Request $request, Response $response, $args) use ($pdo) {
    if (!($_SESSION['loggedIn'] ?? false)) {
        return $response->withStatus(403);
    }
    $body = $request->getParsedBody();
    $stamps = $body['stamps'] ?? [];

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("
            UPDATE image 
            SET location = ?, years = ?, dimensions = ?, description = ?, gccode = ?
            WHERE id = ?
        ");
        foreach ($stamps as $stamp) {
            $stmt->execute([
                $stamp['location'] ?? null,
                $stamp['years'] ?? null,
                $stamp['dimensions'] ?? null,
                $stamp['description'] ?? null,
                $stamp['gccode'] ?? null,
                $stamp['id']
            ]);
        }
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }

    $queryParams = $request->getQueryParams();
    $url = '/admin/edit' . ($queryParams ? '?' . http_build_query($queryParams) : '');
    return $response->withStatus(302)->withHeader('Location', $url);
});

$app->post('/admin/import', function (Request $request, Response $response, $args) use ($pdo) {
    if (!($_SESSION['loggedIn'] ?? false)) {
        return $response->withStatus(403);
    }

    $root = CONTENT_PATH . '/pieczatki';
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));

    $insVoiv = $pdo->prepare("INSERT INTO region(name, slug) VALUES(?, ?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
    $insCounty = $pdo->prepare("INSERT INTO county(region_id, name, slug) VALUES(?, ?, ?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
    $insImage = $pdo->prepare("INSERT INTO image(county_id, filename, real_path, ext) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE id=id");

    $slugify = new Cocur\Slugify\Slugify();
    $imported = 0;

    $pdo->beginTransaction();
    /** @var SplFileInfo $file */
    foreach ($rii as $file) {
        if ($file->isDir() || in_array($file->getFilename(), ['cover.svg', 'cover.png', '_list.yml'])) {
            continue;
        }
        $relPath = trim(str_replace($root, '', $file->getPathname()), '/');
        $parts = explode('/', dirname($relPath));
        if (count($parts) < 2) continue;

        $voivName = $parts[0];
        $countyName = $parts[1];

        $insVoiv->execute([$voivName, $slugify->slugify($voivName)]);
        $voivId = (int)$pdo->lastInsertId();

        $insCounty->execute([$voivId, $countyName, $slugify->slugify($countyName)]);
        $countyId = (int)$pdo->lastInsertId();

        $ext = strtolower($file->getExtension());
        $insImage->execute([$countyId, $file->getFilename(), $relPath, $ext]);
        if ($insImage->rowCount() > 0) {
            $imported++;
        }
    }
    $pdo->commit();

    $response->getBody()->write(json_encode(['imported' => $imported]));
    return $response->withHeader('Content-Type', 'application/json');
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
