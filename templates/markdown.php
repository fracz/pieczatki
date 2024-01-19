<div class="container-fluid py-5">
    <div class="container pb-3 markdown-content">
        <?php

        use Michelf\Markdown;

        echo Markdown::defaultTransform(file_get_contents($filepath));
        ?>
    </div>
</div>
