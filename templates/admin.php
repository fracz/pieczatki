<div class="container-fluid page-header">
    <div class="container">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 370px">
            <h3 class="display-5 text-white text-uppercase">ADMIN PANEL</h3>
        </div>
    </div>
</div>

<div class="container-fluid py-5">
    <div class="container pb-3">
        <div class="row">
            <div class="col-lg-12">
                <div class="bg-light p-4 shadow">
                    <h3>Statystyki</h3>
                    <p>Liczba pieczątek w bazie: <strong><?= $totalCount ?></strong></p>

                    <hr>

                    <div class="mb-4">
                        <a href="/admin/edit" class="btn btn-success btn-lg">Edytuj pieczątki</a>
                    </div>

                    <hr>

                    <h3>Importuj nowe pieczątki</h3>
                    <p>Naciśnij przycisk poniżej, aby przeszukać folder <code>content/pieczatki</code> i dodać do bazy
                        nowo wgrane pliki.</p>
                    <button class="btn btn-primary" onclick="runImport(this)">Uruchom import</button>
                    <div id="importResult" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function runImport(btn) {
        btn.disabled = true;
        const resultDiv = document.getElementById('importResult');
        resultDiv.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="sr-only">Ładowanie...</span></div>';

        fetch('/admin/import', {
            method: 'POST'
        })
            .then(r => r.json())
            .then(data => {
                resultDiv.innerHTML = '<div class="alert alert-success">Import zakończony. Dodano nowych pieczątek: ' + data.imported + '</div>';
            })
            .catch(err => {
                resultDiv.innerHTML = '<div class="alert alert-danger">Wystąpił błąd podczas importu.</div>';
            })
            .finally(() => {
                btn.disabled = false;
            });
    }
</script>
