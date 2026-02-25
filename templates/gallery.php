<div class="container-fluid page-header">
    <div class="container">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 370px">
            <h3 class="display-5 text-white text-uppercase">KOLEKCJA PIECZĄTEK</h3>
            <?php if ($suburl): ?>
                <div class="d-inline-flex flex-wrap text-white">
                    <p class="m-0 text-uppercase"><a class="text-white" href="/">Kolekcja</a></p>
                    <?php
                    $dirs = explode('/', $suburl);
                    $names = $breadcrumbs;
                    $last = array_pop($names);
                    array_pop($dirs);
                    $names = array_combine($dirs, $names);
                    $currentPath = '';
                    foreach ($names as $dir => $label):
                        $currentPath .= ($currentPath ? '/' : '') . $dir;
                        ?>
                        <i class="fa fa-angle-double-right pt-1 px-3"></i>
                        <p class="m-0 text-uppercase">
                            <a class="text-white" href="/pieczatki/<?= $currentPath ?>"><?= $label ?></a>
                        </p>
                    <?php endforeach; ?>
                    <i class="fa fa-angle-double-right pt-1 px-3"></i>
                    <p class="m-0 text-uppercase"><?= $last ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Packages Start -->
<div class="container-fluid py-5">
    <div class="container pb-3">
        <div class="text-center mb-3 pb-3">
            <!--            <h6 class="text-primary text-uppercase" style="letter-spacing: 5px;">Packages</h6>-->
            <h1><?= $last ?></h1>
        </div>
        <div class="row">
            <?php foreach ($images as $image):
                $fullFilename = $image['real_path'];
                ?>
                <div class="col-lg-4 col-md-6 mb-4" id="<?= $image['filename'] ?>">
                    <div class="package-item bg-white mb-2">
                        <img class="img-fluid" src="/media/<?= $fullFilename ?>" alt="">
                        <div class="p-4">
                            <div class="mb-3">
                                <?php if ($image['location'] ?? ''): ?>
                                    <div>
                                        <i class="fa fa-map-marker-alt text-primary mr-2 fa-fw"></i> <?= $image['location'] ?>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <i class="fa fa-calendar-alt text-primary mr-2 fa-fw"></i> <?= ($image['years'] ?? null) ?: '-' ?>
                                </div>
                                <div>
                                    <i class="fa fa-ruler text-primary mr-2 fa-fw"></i> <?= ($image['dimensions'] ?? null) ?: '-' ?>
                                </div>

                                <?php if ($image['gccode'] ?? ''): ?>
                                    <div>
                                        <i class="fa fa-box-open text-primary mr-2 fa-fw"></i>
                                        <a href="https://coord.info/<?= $image['gccode'] ?>" target="_blank"
                                           class="text-monospace">
                                            <?= $image['gccode'] ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <p class="h5" href=""><?= $image['description'] ?? '' ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!-- Packages End -->
