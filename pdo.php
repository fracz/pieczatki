<?php
$config = require __DIR__ . '/config.php';

$pdo = new PDO(
    $config['DB_DSN'],
    $config['DB_USER'],
    $config['DB_PASS'],
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);

function getRegions(PDO $pdo)
{
    return $pdo->query("
        SELECT r.id, r.name, r.slug, COUNT(i.id) as stamps_count
        FROM region r
        LEFT JOIN county c ON r.id = c.region_id
        LEFT JOIN image i ON c.id = i.county_id
        GROUP BY r.id
        ORDER BY r.name
    ")->fetchAll();
}

function getRegionBySlug(PDO $pdo, string $slug)
{
    $stmt = $pdo->prepare("SELECT * FROM region WHERE slug = ?");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

function getCounties(PDO $pdo, int $regionId)
{
    $stmt = $pdo->prepare("
        SELECT c.id, c.name, c.slug, COUNT(i.id) as stamps_count
        FROM county c
        LEFT JOIN image i ON c.id = i.county_id
        WHERE c.region_id = ?
        GROUP BY c.id
        ORDER BY c.name
    ");
    $stmt->execute([$regionId]);
    return $stmt->fetchAll();
}

function getCountyBySlug(PDO $pdo, int $regionId, string $slug)
{
    $stmt = $pdo->prepare("SELECT * FROM county WHERE region_id = ? AND slug = ?");
    $stmt->execute([$regionId, $slug]);
    return $stmt->fetch();
}

function getImages(PDO $pdo, int $countyId)
{
    $stmt = $pdo->prepare("SELECT * FROM image WHERE county_id = ? ORDER BY filename");
    $stmt->execute([$countyId]);
    return $stmt->fetchAll();
}

function getAllRegions(PDO $pdo)
{
    return $pdo->query("SELECT * FROM region ORDER BY name")->fetchAll();
}

function getAllCounties(PDO $pdo)
{
    return $pdo->query("SELECT * FROM county ORDER BY name")->fetchAll();
}

function searchImagesAdmin(PDO $pdo, array $filters)
{
    $sql = "SELECT i.*, r.slug as region_slug, c.slug as county_slug, r.name as region_name, c.name as county_name 
            FROM image i 
            JOIN county c ON i.county_id = c.id 
            JOIN region r ON c.region_id = r.id 
            WHERE 1=1";
    $params = [];

    if (!empty($filters['region_id'])) {
        $sql .= " AND r.id = ?";
        $params[] = $filters['region_id'];
    }
    if (!empty($filters['county_id'])) {
        $sql .= " AND c.id = ?";
        $params[] = $filters['county_id'];
    }
    if (!empty($filters['q'])) {
        $sql .= " AND (i.location LIKE ? OR i.description LIKE ? OR i.filename LIKE ? OR i.gccode LIKE ?)";
        $term = "%" . $filters['q'] . "%";
        $params[] = $term;
        $params[] = $term;
        $params[] = $term;
        $params[] = $term;
    }

    $sql .= " ORDER BY i.created_at DESC LIMIT 100";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getTotalStampsCount(PDO $pdo)
{
    return $pdo->query("SELECT COUNT(*) FROM image")->fetchColumn();
}

return $pdo;
