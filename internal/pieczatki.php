<?php

$directory = __DIR__ . "/../content/pieczatki/$woj/$pow";
$list = scandir($directory);
if ($list) {
    $files = array_diff($list, ['.', '..']);
    foreach ($files as $file) {
        echo <<<HTML
<div>
    <img src="/pieczatki/$woj/$pow/$file">
</div>
HTML;
    }
}
