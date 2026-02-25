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

function getCategories(PDO $pdo, int $parentId = 1)
{
    $sql = "
        WITH RECURSIVE roots AS (
          SELECT id, directory_name, url_slug, label
          FROM category
          WHERE parent_id = ?
        ),
        subtree AS (
          -- start: każdy root jest swoim własnym potomkiem
          SELECT r.id AS root_id, r.id AS cat_id
          FROM roots r
        
          UNION ALL
        
          -- rekurencja: schodzimy w dół drzewa
          SELECT s.root_id, c.id AS cat_id
          FROM subtree s
          JOIN category c ON c.parent_id = s.cat_id
        )
        SELECT
          r.id, r.directory_name, r.url_slug, r.label,
          COUNT(i.id) AS stamps_count
        FROM roots r
        LEFT JOIN subtree s ON s.root_id = r.id
        LEFT JOIN image i ON i.category_id = s.cat_id
        GROUP BY r.id, r.directory_name, r.url_slug, r.label
        ORDER BY r.url_slug;";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$parentId]);
    return $stmt->fetchAll();
}

function getCategoryBySlug(PDO $pdo, string $slug, int $parentId = 1)
{
    $sql = "SELECT * FROM category WHERE url_slug = ? AND parent_id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$slug, $parentId]);
    return $stmt->fetch();
}

function getImages(PDO $pdo, int $categoryId)
{
    $stmt = $pdo->prepare("SELECT * FROM image WHERE category_id = ? ORDER BY filename");
    $stmt->execute([$categoryId]);
    return $stmt->fetchAll();
}

function getAllCategories(PDO $pdo)
{
    return $pdo->query("SELECT * FROM category ORDER BY label")->fetchAll();
}

function searchImagesAdmin(PDO $pdo, array $filters)
{
    $sql = "SELECT i.*, c.label as category_name 
            FROM image i 
            JOIN category c ON i.category_id = c.id 
            WHERE 1=1";
    $params = [];

    if (!empty($filters['category_id'])) {
        $sql .= " AND c.id = ?";
        $params[] = $filters['category_id'];
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
