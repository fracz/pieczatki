<div class="container-fluid page-header">
    <div class="container">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 370px">
            <h3 class="display-5 text-white text-uppercase">KOLEKCJA PIECZĄTEK</h3>
            <?php if ($subdir): ?>
                <div class="d-inline-flex flex-wrap text-white">
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
                <div class="col-lg-4 col-md-6 mb-4" id="<?= basename($fullFilename) ?>">
                    <div class="package-item bg-white mb-2">
                        <img class="img-fluid" src="/media/<?= $fullFilename ?>" alt="">
                        <?php $desc = $descriptions[$fullFilename] ?? [];
                        if ($_SESSION['loggedIn'] ?? false): ?>
                            <form class="p-4" onsubmit="return saveStamp(event)">
                                <h6><?= $subdir . '/' . $filename ?></h6>
                                <input type="hidden" name="filename" value="<?= $fullFilename ?>">
                                <div class="form-group">
                                    <label>Lokalizacja</label>
                                    <input type="text" class="form-control" name="location"
                                           value="<?= htmlentities($desc['location'] ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label>Lata</label>
                                    <input type="text" class="form-control" name="years"
                                           value="<?= htmlentities($desc['years'] ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label>Wymiary</label>
                                    <input type="text" class="form-control" name="dimensions"
                                           value="<?= htmlentities($desc['dimensions'] ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label>Kod GC</label>
                                    <input type="text" class="form-control" name="gccode"
                                           value="<?= htmlentities($desc['gccode'] ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label>Opis</label>
                                    <textarea class="form-control"
                                              name="description"><?= htmlspecialchars($desc['description'] ?? '') ?></textarea>
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
                                    <?php if ($desc['location'] ?? ''): ?>
                                        <div>
                                            <i class="fa fa-map-marker-alt text-primary mr-2 fa-fw"></i> <?= $desc['location'] ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <i class="fa fa-calendar-alt text-primary mr-2 fa-fw"></i> <?= ($desc['years'] ?? null) ?: '-' ?>
                                    </div>
                                    <div>
                                        <i class="fa fa-ruler text-primary mr-2 fa-fw"></i> <?= ($desc['dimensions'] ?? null) ?: '-' ?>
                                    </div>

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
                gccode: fields.gccode.value,
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
