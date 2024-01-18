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
    <div class="container pb-3">
        <div class="text-center mb-3 pb-3">
            <!--            <h6 class="text-primary text-uppercase" style="letter-spacing: 5px;">Packages</h6>-->
            <h1><?= $last ?></h1>
        </div>
        <div class="row">
            <?php foreach ($stamps['images'] as $filename):
                $fullFilename = $subdir . '/' . $filename;
                ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="package-item bg-white mb-2">
                        <img class="img-fluid" src="/media/<?= $fullFilename ?>" alt="">
                        <?php $desc = $descriptions[$fullFilename];
                        if ($_SESSION['loggedIn'] ?? false): ?>
                            <form class="p-4" onsubmit="return saveStamp(event)">
                                <h6><?= $subdir . '/' . $filename ?></h6>
                                <input type="hidden" name="filename" value="<?= $fullFilename ?>">
                                <div class="form-group">
                                    <label>Lokalizacja</label>
                                    <input type="text" class="form-control" name="location"
                                           value="<?= htmlentities($desc['location']) ?>">
                                </div>
                                <div class="form-group">
                                    <label>Lata</label>
                                    <input type="text" class="form-control" name="years"
                                           value="<?= htmlentities($desc['years']) ?>">
                                </div>
                                <div class="form-group">
                                    <label>Wymiary</label>
                                    <input type="text" class="form-control" name="dimensions"
                                           value="<?= htmlentities($desc['dimensions']) ?>">
                                </div>
                                <div class="form-group">
                                    <label>Opis</label>
                                    <textarea class="form-control"
                                              name="description"><?= htmlspecialchars($desc['description']) ?></textarea>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary btn-block" type="submit" name="submit">
                                        Zapisz
                                    </button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="p-4">
                                <div class="mb-3">
                                    <?php if ($desc['location']): ?>
                                        <div>
                                            <i class="fa fa-map-marker-alt text-primary mr-2 fa-fw"></i> <?= $desc['location'] ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($desc['years']): ?>
                                        <div>
                                            <i class="fa fa-calendar-alt text-primary mr-2 fa-fw"></i> <?= $desc['years'] ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($desc['dimensions']): ?>
                                        <div>
                                            <i class="fa fa-ruler text-primary mr-2 fa-fw"></i> <?= $desc['dimensions'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <p class="h5" href=""><?= $desc['description'] ?></p>
                                <!--                            <div class="border-top mt-4 pt-4">-->
                                <!--                                <div class="d-flex justify-content-between">-->
                                <!--                                    <h6 class="m-0"><i class="fa fa-star text-primary mr-2"></i>4.5 <small>(250)</small>-->
                                <!--                                    </h6>-->
                                <!--                                    <h5 class="m-0">$350</h5>-->
                                <!--                                </div>-->
                                <!--                            </div>-->
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!-- Packages End -->
<?php if ($_SESSION['loggedIn'] ?? false): ?>
    <script>
        function saveStamp(e) {
            const fields = e.target.elements;
            const req = {
                filename: fields.filename.value,
                location: fields.location.value,
                years: fields.years.value,
                dimensions: fields.dimensions.value,
                description: fields.description.value,
            };
            fields.submit.disabled = true;
            fetch('/update', {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(req),
            })
                .then(r => {
                    if (r.status !== 200) {
                        alert("Nie udało się zapisać zmian.");
                    }
                })
                .finally(() => fields.submit.disabled = false);
            return false;
        }
    </script>
<?php endif; ?>
