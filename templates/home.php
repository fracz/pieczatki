<?php
if (!isset($subdir)) {
    $subdir = '';
}
$subdirHash = ($subdir ? '/' . $subdir : '');
?>

<div class="container-fluid page-header">
    <div class="container">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 370px">
            <h3 class="display-5 text-white text-uppercase">KOLEKCJA PIECZĄTEK</h3>
            <?php if ($subdir): ?>
                <div class="d-inline-flex text-white">
                    <p class="m-0 text-uppercase"><a class="text-white" href="/">Kolekcja</a></p>
                    <i class="fa fa-angle-double-right pt-1 px-3"></i>
                    <p class="m-0 text-uppercase"><?= $subdir ?></p>
                </div>
            <?php else: ?>
                <div class="text-white text-center">
                    turystycznych, historycznych, okolicznościowych i innych...
                </div>
                <div class="text-white text-center">
                    Liczba pieczątek w kolekcji:
                    <h4 style="color:yellow"><?= $totalCount ?></h4>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Booking Start -->
<div class="container-fluid booking mt-5">
    <div class="container">
        <div class="bg-light shadow" style="padding: 30px;">
            <form class="d-flex" method="get" action="/szukaj">
                <div class="flex-grow-1 pr-3">
                    <input type="text" class="form-control p-4" placeholder="Szukana fraza" name="q"/>
                </div>
                <div>
                    <button class="btn btn-primary btn-block" type="submit" style="height: 50px;">
                        Przeszukaj
                        <span class="d-none d-sm-inline">kolekcję</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Booking End -->

<!-- Destination Start -->
<div class="container-fluid py-5">
    <div class="container pt-5 pb-3">
        <div class="row">
            <?php if (isset($regions)): ?>
                <?php foreach ($regions as $region): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="destination-item position-relative overflow-hidden mb-2" style="min-height: 200px">
                            <img class="img-fluid" src="/media/<?= $region['slug'] ?>/cover.png" alt="">
                            <a class="destination-overlay text-white text-decoration-none"
                               href="/pieczatki/<?= $region['slug'] ?>">
                                <h5 class="text-white"><?= $region['name'] ?></h5>
                                <span><?= $region['stamps_count'] ?> pieczątek</span>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php elseif (isset($counties)): ?>
                <?php foreach ($counties as $county): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="destination-item position-relative overflow-hidden mb-2" style="min-height: 200px">
                            <img class="img-fluid" src="/media/<?= $region['slug'] ?>/<?= $county['slug'] ?>/cover.png"
                                 alt="">
                            <a class="destination-overlay text-white text-decoration-none"
                               href="/pieczatki/<?= $region['slug'] ?>/<?= $county['slug'] ?>">
                                <h5 class="text-white"><?= $county['name'] ?></h5>
                                <span><?= $county['stamps_count'] ?> pieczątek</span>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Destination Start -->
