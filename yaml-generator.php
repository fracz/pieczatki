<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

$root = __DIR__ . '/content/pieczatki';

$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
$categories = array();

/** @var SplFileInfo $file */
foreach ($rii as $file) {
    if ($file->isDir()) {
        continue;
    }
    $basePath = trim(str_replace($root, '', $file->getPathname()), '/');
    $parts = explode('/', $basePath);
    $filename = array_pop($parts);
    $category = implode('/', $parts);

    $categories[] = $category;
}

$categories = array_values(array_unique($categories));

foreach ($categories as $directoryWithImages) {
    $dir = __DIR__ . '/content/pieczatki/' . $directoryWithImages;
    $images = array_diff(scandir($dir), ['.', '..', '_list.yml']);
    $ymlPath = $dir . '/_list.yml';
    $yaml = file_exists($ymlPath) ? Yaml::parseFile($ymlPath) : ['images' => []];
    foreach ($images as $image) {
        if (!isset($yaml[$image])) {
            $yaml['images'][$image] = [
                'location' => '',
                'years' => '',
                'dimensions' => '',
                'description' => $image,
            ];
        }
    }
    if (stripos(current($images), '.jpg')) {
        file_put_contents($ymlPath, Yaml::dump($yaml, 2, 2));
    } else {
        @unlink($ymlPath);
    }
}
