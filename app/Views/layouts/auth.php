<!DOCTYPE html>
<html>
<head>
    <title><?= $this->renderSection('title') ?> | Igniter</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: "PT Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
            background-color: #f1f1f1;
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
        }

        .auth-form-cont {
            min-width: 360px;
            border-radius: 4px;
            background: #fff;
            box-shadow: 0 0 4px #aaa;
        }
    </style>
</head>

<body>
<div class="d-flex flex-column min-vh-100 justify-content-center align-items-center">
    <div class="p-4 auth-form-cont">
        <?= $this->renderSection('content') ?>
    </div>
</div>
</body>
</html>
