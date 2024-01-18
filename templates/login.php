<?php
if (!isset($subdir)) {
    $subdir = '';
}
$subdirHash = ($subdir ? '/' . $subdir : '');
?>

<div class="container-fluid page-header">
    <div class="container">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 400px">
            <h3 class="display-4 text-white text-uppercase">LOGOWANIE</h3>
        </div>
    </div>
</div>

<!-- Booking Start -->
<div class="container-fluid booking mt-5">
    <div class="container">
        <form class="bg-light shadow" style="padding: 30px;" method="post">
            <div class="form-group">
                <input type="password" name="password" class="form-control p-4" placeholder="Hasło"/>
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-block" type="submit" style="height: 49px;">
                    Zaloguj się
                </button>
            </div>
        </form>
    </div>
</div>
<!-- Booking End -->
