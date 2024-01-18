<?php

require_once __DIR__ . '/vendor/autoload.php';

function getStamps()
{
    $slugify = new Cocur\Slugify\Slugify();
    $usort = fn($a, $b) => strcoll($slugify->slugify($a), $slugify->slugify($b));
    $cacheFile = __DIR__ . '/var/stamps.php';
    if (file_exists($cacheFile) && (filemtime($cacheFile) > (time() - 60 * 5))) {
        return require $cacheFile;
    }
    $root = __DIR__ . '/content/pieczatki';
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
    $stamps = ['count' => 0];
    $descriptions = require_once __DIR__ . '/var/descriptions.php';
    $descriptionsCount = count($descriptionsCount);

    /** @var SplFileInfo $file */
    foreach ($rii as $file) {
        if ($file->isDir() || in_array($file->getFilename(), ['cover.svg', '_list.yml'])) {
            continue;
        }
        $baseName = trim(str_replace($root, '', $file->getPathname()), '/');
        $basePath = dirname($baseName);
        $cat = &$stamps;
        foreach (explode('/', $basePath) as $dir) {
            if (!isset($cat[$dir])) {
                $cat[$dir] = ['count' => 0, 'images' => []];
            }
            ++$cat['count'];
            $cat = &$cat[$dir];
        }
        ++$cat['count'];
        $cat['images'][] = $file->getFilename();
        sort($cat['images']);
        if (!isset($descriptions[$baseName])) {
            $descriptions[$baseName] = ['description' => basename($baseName)];
        }
    }
    setlocale(LC_COLLATE, 'nl_BE.utf8');
    uksort($stamps, $usort);
    file_put_contents($cacheFile, "<?php\nreturn " . var_export($stamps, true) . ';', LOCK_EX);
    if (count($descriptions) != $descriptionsCount) {
        file_put_contents(__DIR__ . '/var/descriptions.php', "<?php\nreturn " . var_export($descriptions, true) . ';', LOCK_EX);
    }
    return $stamps;
}

//getStamps();
