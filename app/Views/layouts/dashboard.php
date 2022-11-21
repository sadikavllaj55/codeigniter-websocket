<!DOCTYPE html>
<html>
<head>
    <title><?= $this->renderSection('title') ?> | Igniter</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/css/admin.css">
    <?= $this->renderSection('head') ?>

</head>

<body>
    <div class="wrapper min-vh-100 vh-100 d-flex align-items-stretch">
        <nav id="sidebar" class="bg-dark">
            <a href="/dashboard" class="brand-link">Dashboard</a>
            <div class="p-4">
                <ul class="list-unstyled components mb-5">
                    <li>
                        <a href="<?= base_url() ?>/dashboard/profile">
                            <i class="fa fa-user-circle"></i> Edit Profile
                        </a>
                    </li>
                    <?php if (session()->get('user')->role_id == 2): ?>
                        <li>
                            <a class="collapsed" data-bs-toggle="collapse" href="#departments-menu" aria-expanded="false"
                               aria-controls="departments-menu">
                                <i class="fa fa-newspaper"></i> Departments
                                <i class="fa fa-angle-left menu-arrow"></i>
                            </a>
                            <ul class="list-unstyled collapse" id="departments-menu">
                                <li><a href="/dashboard/departments/new">Create</a></li>
                                <li><a href="/dashboard/departments">List All</a></li>
                            </ul>
                        </li>
                        <li>
                            <a data-bs-toggle="collapse" href="#users-menu" aria-expanded="false" aria-controls="users-menu">
                                <i class="fa fa-user"></i> Users
                                <i class="fa fa-angle-left menu-arrow"></i>
                            </a>
                            <ul class="list-unstyled collapse" id="users-menu">
                                <li class="">
                                    <a href="/dashboard/users">List All</a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <li>
                        <a href="<?= base_url() ?>/dashboard/chat">
                            <i class="fa fa-message"></i> Chat
                        </a>
                    </li>
                    <li><a href="/logout"><i class="fa fa-sign-out"></i> Logout</a></li>
                </ul>
            </div>
            <div class="footer" style="position:absolute; bottom: 0; width: 100%; text-align: center">
                <p>©2022 All rights reserved</p>
            </div>
        </nav>
        <div id="content" class="d-flex flex-column min-vh-100 vh-100">
            <nav class="main-header navbar navbar-expand bg-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" id="menu-toggle" role="button"><i class="fas fa-bars"></i></a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="/dashboard" class="nav-link">Home</a>
                    </li>
                </ul>
                <!-- Right navbar links -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"></li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link" data-toggle="dropdown" aria-expanded="false">
                            <i class="far fa-user"></i><?= session('user')->name ?></a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <a href="http://localhost:9090/logout" class="dropdown-item"
                               onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="fas fa-lock mr-2"></i> Logout
                            </a>
                            <form id="logout-form" action="/logout" method="post" class="d-none">
                                <input type="hidden" name="_token" value="rgEyRmpx6UZwTc8ljxKPBsfTvJXFgNAx1ncC31QK"></form>
                        </div>
                    </li>
                    <li>
                    </li>
                </ul>
            </nav>
            <div class="p-2 ml-4 p-2 ml-4 d-flex flex-column flex-fill" style="overflow: auto">
                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.min.js"></script>
    <script>
        $('#menu-toggle').on('click', function () {
            $('#sidebar').toggleClass('sidebar-closed');
        });

        function showNotification(type, message) {
            var toast_html = `<div id="notification-toast" class="toast text-white bg-${type} align-items-center border-0" role="alert">
                 <div class="d-flex">
                <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div></div>`;

            var $toast_el = $(toast_html);

            $('#notification-toast').remove();
            $('#toast-container').append($toast_el);

            var toast = new bootstrap.Toast(document.getElementById('notification-toast'));
            toast.show()
        }
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>