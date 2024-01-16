<?php

$url = (isset($woj) && $woj ? '/' . $woj : '');
$directory = __DIR__ . '/../content/pieczatki' . $url;
$list = scandir($directory);
if ($list) {
    $directories = array_diff($list, ['.', '..']);
    sort($directories);
    foreach ($directories as $directory) {
        echo <<<HTML
<div>
    <a href="/pieczatki$url/$directory">$directory</a>
</div>
HTML;
    }
}
