<?php
if (!isset($subdir)) {
    $subdir = '';
}
$subdirHash = ($subdir ? '/' . $subdir : '');
?>

<div class="container-fluid page-header">
    <div class="container">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 400px">
            <h3 class="display-4 text-white text-uppercase">KOLEKCJA PIECZĄTEK</h3>
            <?php if ($subdir): ?>
                <div class="d-inline-flex text-white">
                    <p class="m-0 text-uppercase"><a class="text-white" href="/">Kolekcja</a></p>
                    <i class="fa fa-angle-double-right pt-1 px-3"></i>
                    <p class="m-0 text-uppercase"><?= $subdir ?></p>
                </div>
            <?php else: ?>
                <div class="text-white">
                    Zbieraj pieczątki tak jak yuve wchodzi na drzewa.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Booking Start -->
<div class="container-fluid booking mt-5">
    <div class="container">
        <div class="bg-light shadow" style="padding: 30px;">
            <div class="d-flex">
                <div class="flex-grow-1 pr-3">
                    <input type="text" class="form-control p-4" placeholder="Szukana fraza"/>
                </div>
                <div>
                    <button class="btn btn-primary btn-block" type="submit" style="height: 49px;">
                        Przeszukaj
                        <span class="d-none d-sm-inline">kolekcję</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Booking End -->

<!-- Destination Start -->
<div class="container-fluid py-5">
    <div class="container pt-5 pb-3">
        <!--        <div class="text-center mb-3 pb-3">-->
        <!--            <h6 class="text-primary text-uppercase" style="letter-spacing: 5px;">Destination</h6>-->
        <!--            <h1>Explore Top Destination</h1>-->
        <!--        </div>-->
        <div class="row">
            <?php
            $root = __DIR__ . '/../content/pieczatki' . $subdirHash;
            $list = scandir($root);
            $directories = array_diff($list, ['.', '..']);
            sort($directories);
            foreach ($directories as $directory):
                if (is_dir($root . '/' . $directory)):
                    ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="destination-item position-relative overflow-hidden mb-2" style="min-height: 200px">
                            <img class="img-fluid" src="<?= $subdirHash . '/' . $directory ?>/cover.svg" alt="">
                            <a class="destination-overlay text-white text-decoration-none"
                               href="/pieczatki<?= $subdirHash ?>/<?= $directory ?>">
                                <h5 class="text-white"><?= $directory ?></h5>
                                <span>100 Cities</span>
                            </a>
                        </div>
                    </div>
                <?php
                endif;
            endforeach;
            ?>
        </div>
    </div>
</div>
<!-- Destination Start -->
