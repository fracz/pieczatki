<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

function getStamps()
{
    $cacheFile = __DIR__ . '/var/stamps.php';
    if (file_exists($cacheFile) && (filemtime($cacheFile) > (time() - 60 * 5))) {
        return require $cacheFile;
    }
    $root = __DIR__ . '/content/pieczatki';
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
    $stamps = ['count' => 0];

    /** @var SplFileInfo $file */
    foreach ($rii as $file) {
        if ($file->getFilename() === '_list.yml') {
            $yaml = Yaml::parseFile($file->getPathname());
            $yaml['count'] = count($yaml['images']);
            $basePath = dirname(trim(str_replace($root, '', $file->getPathname()), '/'));
            $cat = &$stamps;
            foreach (explode('/', $basePath) as $dir) {
                if (!isset($cat[$dir])) {
                    $cat[$dir] = ['count' => 0];
                }
                $cat['count'] += $yaml['count'];
                $cat = &$cat[$dir];
            }
            $cat = $yaml;
        }
    }
    file_put_contents($cacheFile, "<?php\nreturn " . var_export($stamps, true) . ';');
    return $stamps;
}


getStamps();
