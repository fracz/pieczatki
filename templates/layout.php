<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Katalog polskich pieczątek turystycznych" name="description">

    <!-- Favicon -->
    <link href="/img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet"/>

    <!-- Customized Bootstrap Stylesheet -->
    <link href="/css/style.min.css" rel="stylesheet">
    <link href="/css/custom.css" rel="stylesheet">
</head>

<body>
<!-- Topbar Start -->
<div class="container-fluid bg-light pt-3 d-none d-lg-block">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 text-center text-lg-left mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center">
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center">
                    <?php if ($_SESSION['loggedIn'] ?? false): ?>
                        <a class="text-primary px-3" href="/logout">
                            wyloguj
                        </a>
                    <?php endif; ?>
                    <!--                        <a class="text-primary px-3" href="">-->
                    <!--                            <i class="fab fa-linkedin-in"></i>-->
                    <!--                        </a>-->
                    <!--                        <a class="text-primary px-3" href="">-->
                    <!--                            <i class="fab fa-instagram"></i>-->
                    <!--                        </a>-->
                    <!--                        <a class="text-primary pl-3" href="">-->
                    <!--                            <i class="fab fa-youtube"></i>-->
                    <!--                        </a>-->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Topbar End -->


<!-- Navbar Start -->
<div class="container-fluid position-relative nav-bar p-0">
    <div class="container-lg position-relative p-0 px-lg-3" style="z-index: 9;">
        <nav class="navbar navbar-expand-lg bg-light navbar-light shadow-lg py-3 py-lg-0 pl-3 pl-lg-5">
            <a href="/" class="navbar-brand">
                <h1 class="m-0 text-primary">
                    <span class="text-dark">PIECZĄTKI TURYSTYCZNE</span>
                </h1>
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between px-3" id="navbarCollapse">
                <div class="navbar-nav ml-auto py-0">
                    <?php
                    $activeLink = [
                        '/historia' => 'historia',
                        '/info' => 'info',
                        '/proces' => 'proces',
                        '/linki' => 'linki',
                    ][$_SERVER['REQUEST_URI']] ?? '';
                    ?>
                    <a href="/" class="nav-item nav-link <?= $activeLink === '' ? 'active' : '' ?>">Kolekcja</a>
                    <a href="/info" class="nav-item nav-link <?= $activeLink === 'info' ? 'active' : '' ?>">Info</a>
                    <a href="/historia" class="nav-item nav-link <?= $activeLink === 'historia' ? 'active' : '' ?>">Historia</a>
                    <a href="/proces" class="nav-item nav-link <?= $activeLink === 'proces' ? 'active' : '' ?>">Proces</a>
                    <a href="/linki" class="nav-item nav-link <?= $activeLink === 'linki' ? 'active' : '' ?>">Linki</a>
                    <!--                        <div class="nav-item dropdown">-->
                    <!--                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Pages</a>-->
                    <!--                            <div class="dropdown-menu border-0 rounded-0 m-0">-->
                    <!--                                <a href="blog.html" class="dropdown-item">Blog Grid</a>-->
                    <!--                                <a href="single.html" class="dropdown-item">Blog Detail</a>-->
                    <!--                                <a href="destination.html" class="dropdown-item">Destination</a>-->
                    <!--                                <a href="guide.html" class="dropdown-item">Travel Guides</a>-->
                    <!--                                <a href="testimonial.html" class="dropdown-item">Testimonial</a>-->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                        <a href="contact.html" class="nav-item nav-link">Contact</a>-->
                </div>
            </div>
        </nav>
    </div>
</div>
<!-- Navbar End -->

<!-- Header Start -->

<!-- Header End -->

<?= $content ?>

<!-- Footer Start -->
</div>
<div class="container-fluid bg-dark text-white border-top py-4 px-sm-3 px-md-5"
     style="border-color: rgba(256, 256, 256, .1) !important;">
    <div class="row">
        <div class="col-lg-6 text-center text-md-left mb-3 mb-md-0">
            <p class="text-white-50">
                contact: <span style="color: lightgreen">pieczatki.tur@gmail.com</span>
            </p>
            <p class="text-white-50">
                owner: <span style="color: yellow">yuve (MO)</span>
            </p>
            <p class="text-white-50">
                webmaster: <span style="color: yellow">kranfagel (WF)</span>
            </p>
        </div>
    </div>
</div>
<!-- Footer End -->


<!-- Back to Top -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa fa-angle-double-up"></i></a>


<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="/lib/easing/easing.min.js"></script>
<script src="/lib/owlcarousel/owl.carousel.min.js"></script>
<script src="/lib/tempusdominus/js/moment.min.js"></script>
<script src="/lib/tempusdominus/js/moment-timezone.min.js"></script>
<script src="/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

<!-- Contact Javascript File -->
<!--    <script src="/mail/jqBootstrapValidation.min.js"></script>-->
<!--    <script src="/mail/contact.js"></script>-->

<!-- Template Javascript -->
<script src="/js/main.js"></script>
</body>

</html>
