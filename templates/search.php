<div class="container-fluid page-header">
    <div class="container">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 400px">
            <h3 class="display-4 text-white text-uppercase">KOLEKCJA PIECZĄTEK</h3>
            <div class="d-inline-flex flex-wrap text-white">
                <p class="m-0 text-uppercase"><a class="text-white" href="/">Kolekcja</a></p>
                <i class="fa fa-angle-double-right pt-1 px-3"></i>
                <p class="m-0 text-uppercase">Wyszukiwarka</p>
            </div>
        </div>
    </div>
</div>

<!-- Booking Start -->
<div class="container-fluid booking mt-5">
    <div class="container">
        <div class="bg-light shadow" style="padding: 30px;">
            <form class="d-flex" method="get" action="/szukaj">
                <div class="flex-grow-1 pr-3">
                    <input type="text" class="form-control p-4" placeholder="Szukana fraza" name="q"
                           value="<?= htmlentities($phrase) ?>"/>
                </div>
                <div>
                    <button class="btn btn-primary btn-block" type="submit" style="height: 49px;">
                        Przeszukaj
                        <span class="d-none d-sm-inline">kolekcję</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Booking End -->

<?php if ($phrase): ?>
    <!-- Packages Start -->
    <div class="container-fluid py-5">
        <div class="container pb-3">
            <div class="text-center mb-3 pb-3">
                <!--            <h6 class="text-primary text-uppercase" style="letter-spacing: 5px;">Packages</h6>-->
                <h1>Wyniki wyszukiwania &mdash; "<?= htmlspecialchars($phrase) ?>"</h1>
            </div>
            <?php if ($hits): ?>
                <div class="row">
                    <?php foreach ($hits as $filepath => $desc):
                        ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="package-item bg-white mb-2">
                                <img class="img-fluid" src="/media/<?= $filepath ?>" alt="">
                                <div class="p-4">
                                    <div class="mb-3">
                                        <?php if ($desc['location'] ?? ''): ?>
                                            <div>
                                                <i class="fa fa-map-marker-alt text-primary mr-2 fa-fw"></i> <?= $desc['location'] ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($desc['years'] ?? ''): ?>
                                            <div>
                                                <i class="fa fa-calendar-alt text-primary mr-2 fa-fw"></i> <?= $desc['years'] ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($desc['dimensions'] ?? ''): ?>
                                            <div>
                                                <i class="fa fa-ruler text-primary mr-2 fa-fw"></i> <?= $desc['dimensions'] ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($desc['gccode'] ?? ''): ?>
                                            <div>
                                                <i class="fa fa-box-open text-primary mr-2 fa-fw"></i>
                                                <a href="https://coord.info/<?= $desc['gccode'] ?>" target="_blank"
                                                   class="text-monospace">
                                                    <?= $desc['gccode'] ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <p class="h5" href=""><?= $desc['description'] ?? '' ?></p>
                                    <div class="border-top mt-4 pt-4">
                                        <h6 class="m-0">
                                            <i class="fa fa-link text-primary mr-2"></i>
                                            <a href="/pieczatki/<?= dirname($filepath) ?>#<?= basename($filepath) ?>">
                                                <?= dirname($filepath) ?>
                                            </a>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center">Nie ma jeszcze takiej pieczątki.</div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
