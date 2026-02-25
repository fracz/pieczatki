<div class="container-fluid page-header">
    <div class="container">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 370px">
            <h3 class="display-5 text-white text-uppercase">EDYCJA PIECZĄTEK</h3>
        </div>
    </div>
</div>

<div class="container-fluid py-5">
    <div class="container">
        <form method="get" action="/admin/edit" class="bg-light p-4 shadow mb-5">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label>Województwo</label>
                    <select name="region_id" class="form-control" onchange="this.form.submit()">
                        <option value="">Wszystkie</option>
                        <?php foreach ($regions as $r): ?>
                            <option value="<?= $r['id'] ?>" <?= ($filters['region_id'] ?? '') == $r['id'] ? 'selected' : '' ?>>
                                <?= $r['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Powiat</label>
                    <select name="county_id" class="form-control" onchange="this.form.submit()">
                        <option value="">Wszystkie</option>
                        <?php foreach ($counties as $c): ?>
                            <?php if (!$filters['region_id'] || $c['region_id'] == $filters['region_id']): ?>
                                <option value="<?= $c['id'] ?>" <?= ($filters['county_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                                    <?= $c['name'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Szukaj (tekst, nazwa pliku, GC)</label>
                    <input type="text" name="q" class="form-control" value="<?= htmlentities($filters['q'] ?? '') ?>">
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-block">Filtruj</button>
                </div>
            </div>
        </form>

        <?php if ($images): ?>
            <form action="/admin/update-batch" method="post" id="batchEditForm">
                <div class="table-responsive bg-white shadow">
                    <table class="table table-bordered mb-0">
                        <thead class="thead-light">
                        <tr>
                            <th style="width: 150px">Podgląd</th>
                            <th>Dane</th>
                            <th style="width: 150px">Akcja</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($images as $image): ?>
                            <?php $fullFilename = $image['region_slug'] . '/' . $image['county_slug'] . '/' . $image['filename']; ?>
                            <tr>
                                <td>
                                    <img src="/media/<?= $fullFilename ?>" class="img-fluid mb-2">
                                    <small class="d-block text-muted"><?= $image['filename'] ?></small>
                                    <small class="d-block font-weight-bold"><?= $image['region_name'] ?>
                                        / <?= $image['county_name'] ?></small>
                                </td>
                                <td>
                                    <input type="hidden" name="stamps[<?= $image['id'] ?>][id]"
                                           value="<?= $image['id'] ?>">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label><small>Lokalizacja</small></label>
                                            <input type="text" name="stamps[<?= $image['id'] ?>][location]"
                                                   class="form-control form-control-sm"
                                                   value="<?= htmlentities($image['location'] ?? '') ?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label><small>Lata</small></label>
                                            <input type="text" name="stamps[<?= $image['id'] ?>][years]"
                                                   class="form-control form-control-sm"
                                                   value="<?= htmlentities($image['years'] ?? '') ?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label><small>Wymiary</small></label>
                                            <input type="text" name="stamps[<?= $image['id'] ?>][dimensions]"
                                                   class="form-control form-control-sm"
                                                   value="<?= htmlentities($image['dimensions'] ?? '') ?>">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label><small>Kod GC</small></label>
                                            <input type="text" name="stamps[<?= $image['id'] ?>][gccode]"
                                                   class="form-control form-control-sm"
                                                   value="<?= htmlentities($image['gccode'] ?? '') ?>">
                                        </div>
                                        <div class="form-group col-md-9">
                                            <label><small>Opis</small></label>
                                            <textarea name="stamps[<?= $image['id'] ?>][description]"
                                                      class="form-control form-control-sm"
                                                      rows="1"><?= htmlspecialchars($image['description'] ?? '') ?></textarea>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <button type="button" class="btn btn-success btn-sm btn-block mb-2"
                                            onclick="saveSingle(<?= $image['id'] ?>, this)">Zapisz
                                    </button>
                                    <div class="status-indicator" id="status-<?= $image['id'] ?>"></div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 text-right">
                    <button type="submit" class="btn btn-primary btn-lg px-5">Zapisz wszystkie widoczne</button>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-info">Nie znaleziono pieczątek spełniających kryteria.</div>
        <?php endif; ?>
    </div>
</div>

<script>
    function saveSingle(id, btn) {
        const row = btn.closest('tr');
        const data = {
            id: id,
            location: row.querySelector(`[name="stamps[${id}][location]"]`).value,
            years: row.querySelector(`[name="stamps[${id}][years]"]`).value,
            dimensions: row.querySelector(`[name="stamps[${id}][dimensions]"]`).value,
            gccode: row.querySelector(`[name="stamps[${id}][gccode]"]`).value,
            description: row.querySelector(`[name="stamps[${id}][description]"]`).value
        };

        btn.disabled = true;
        const indicator = document.getElementById('status-' + id);
        indicator.innerHTML = '<span class="text-muted small">Zapisywanie...</span>';

        fetch('/update', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
            .then(r => {
                if (r.ok) {
                    indicator.innerHTML = '<span class="text-success small">Zapisano!</span>';
                    setTimeout(() => {
                        indicator.innerHTML = '';
                    }, 2000);
                } else {
                    indicator.innerHTML = '<span class="text-danger small">Błąd!</span>';
                }
            })
            .catch(() => {
                indicator.innerHTML = '<span class="text-danger small">Błąd sieci!</span>';
            })
            .finally(() => {
                btn.disabled = false;
            });
    }
</script>
