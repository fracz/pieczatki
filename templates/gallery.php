<div class="container-fluid page-header">
    <div class="container">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 400px">
            <h3 class="display-4 text-white text-uppercase">KOLEKCJA PIECZĄTEK</h3>
            <?php if ($subdir): ?>
                <div class="d-inline-flex text-white">
                    <p class="m-0 text-uppercase"><a class="text-white" href="/">Kolekcja</a></p>
                    <?php
                    $dirs = explode('/', $subdir);
                    $last = array_pop($dirs);
                    ?>
                    <?php
                    if ($dirs):
                        ?>
                        <i class="fa fa-angle-double-right pt-1 px-3"></i>
                        <p class="m-0 text-uppercase">
                            <a class="text-white" href="/pieczatki/<?= $dirs[0] ?>"><?= $dirs[0] ?></a>
                        </p>
                    <?php endif; ?>
                    <i class="fa fa-angle-double-right pt-1 px-3"></i>
                    <p class="m-0 text-uppercase"><?= $last ?></p>
                </div>
            <?php else: ?>
                <div class="text-white">
                    Zbieraj pieczątki tak jak yuve wchodzi na drzewa.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<!-- Packages Start -->
<div class="container-fluid py-5">
    <div class="container pt-5 pb-3">
        <div class="text-center mb-3 pb-3">
            <h6 class="text-primary text-uppercase" style="letter-spacing: 5px;">Packages</h6>
            <h1>Pefect Tour Packages</h1>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="package-item bg-white mb-2">
                    <img class="img-fluid" src="/img/package-1.jpg" alt="">
                    <div class="p-4">
                        <div class="d-flex justify-content-between mb-3">
                            <small class="m-0"><i class="fa fa-map-marker-alt text-primary mr-2"></i>Thailand</small>
                            <small class="m-0"><i class="fa fa-calendar-alt text-primary mr-2"></i>3 days</small>
                            <small class="m-0"><i class="fa fa-user text-primary mr-2"></i>2 Person</small>
                        </div>
                        <a class="h5 text-decoration-none" href="">Discover amazing places of the world with us</a>
                        <div class="border-top mt-4 pt-4">
                            <div class="d-flex justify-content-between">
                                <h6 class="m-0"><i class="fa fa-star text-primary mr-2"></i>4.5 <small>(250)</small>
                                </h6>
                                <h5 class="m-0">$350</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="package-item bg-white mb-2">
                    <img class="img-fluid" src="img/package-2.jpg" alt="">
                    <div class="p-4">
                        <div class="d-flex justify-content-between mb-3">
                            <small class="m-0"><i class="fa fa-map-marker-alt text-primary mr-2"></i>Thailand</small>
                            <small class="m-0"><i class="fa fa-calendar-alt text-primary mr-2"></i>3 days</small>
                            <small class="m-0"><i class="fa fa-user text-primary mr-2"></i>2 Person</small>
                        </div>
                        <a class="h5 text-decoration-none" href="">Discover amazing places of the world with us</a>
                        <div class="border-top mt-4 pt-4">
                            <div class="d-flex justify-content-between">
                                <h6 class="m-0"><i class="fa fa-star text-primary mr-2"></i>4.5 <small>(250)</small>
                                </h6>
                                <h5 class="m-0">$350</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="package-item bg-white mb-2">
                    <img class="img-fluid" src="img/package-3.jpg" alt="">
                    <div class="p-4">
                        <div class="d-flex justify-content-between mb-3">
                            <small class="m-0"><i class="fa fa-map-marker-alt text-primary mr-2"></i>Thailand</small>
                            <small class="m-0"><i class="fa fa-calendar-alt text-primary mr-2"></i>3 days</small>
                            <small class="m-0"><i class="fa fa-user text-primary mr-2"></i>2 Person</small>
                        </div>
                        <a class="h5 text-decoration-none" href="">Discover amazing places of the world with us</a>
                        <div class="border-top mt-4 pt-4">
                            <div class="d-flex justify-content-between">
                                <h6 class="m-0"><i class="fa fa-star text-primary mr-2"></i>4.5 <small>(250)</small>
                                </h6>
                                <h5 class="m-0">$350</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="package-item bg-white mb-2">
                    <img class="img-fluid" src="img/package-4.jpg" alt="">
                    <div class="p-4">
                        <div class="d-flex justify-content-between mb-3">
                            <small class="m-0"><i class="fa fa-map-marker-alt text-primary mr-2"></i>Thailand</small>
                            <small class="m-0"><i class="fa fa-calendar-alt text-primary mr-2"></i>3 days</small>
                            <small class="m-0"><i class="fa fa-user text-primary mr-2"></i>2 Person</small>
                        </div>
                        <a class="h5 text-decoration-none" href="">Discover amazing places of the world with us</a>
                        <div class="border-top mt-4 pt-4">
                            <div class="d-flex justify-content-between">
                                <h6 class="m-0"><i class="fa fa-star text-primary mr-2"></i>4.5 <small>(250)</small>
                                </h6>
                                <h5 class="m-0">$350</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="package-item bg-white mb-2">
                    <img class="img-fluid" src="img/package-5.jpg" alt="">
                    <div class="p-4">
                        <div class="d-flex justify-content-between mb-3">
                            <small class="m-0"><i class="fa fa-map-marker-alt text-primary mr-2"></i>Thailand</small>
                            <small class="m-0"><i class="fa fa-calendar-alt text-primary mr-2"></i>3 days</small>
                            <small class="m-0"><i class="fa fa-user text-primary mr-2"></i>2 Person</small>
                        </div>
                        <a class="h5 text-decoration-none" href="">Discover amazing places of the world with us</a>
                        <div class="border-top mt-4 pt-4">
                            <div class="d-flex justify-content-between">
                                <h6 class="m-0"><i class="fa fa-star text-primary mr-2"></i>4.5 <small>(250)</small>
                                </h6>
                                <h5 class="m-0">$350</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="package-item bg-white mb-2">
                    <img class="img-fluid" src="img/package-6.jpg" alt="">
                    <div class="p-4">
                        <div class="d-flex justify-content-between mb-3">
                            <small class="m-0"><i class="fa fa-map-marker-alt text-primary mr-2"></i>Thailand</small>
                            <small class="m-0"><i class="fa fa-calendar-alt text-primary mr-2"></i>3 days</small>
                            <small class="m-0"><i class="fa fa-user text-primary mr-2"></i>2 Person</small>
                        </div>
                        <a class="h5 text-decoration-none" href="">Discover amazing places of the world with us</a>
                        <div class="border-top mt-4 pt-4">
                            <div class="d-flex justify-content-between">
                                <h6 class="m-0"><i class="fa fa-star text-primary mr-2"></i>4.5 <small>(250)</small>
                                </h6>
                                <h5 class="m-0">$350</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Packages End -->
