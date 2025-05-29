<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Page404</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Agdasima:wght@400;700&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Radley:ital@0;1&family=Red+Hat+Display:ital,wght@0,300..900;1,300..900&family=Rethink+Sans:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Lab3Styles.css">
</head>
<body>
<header>
    <?php
        require_once 'header.php';
    ?>
</header>

    <h2 style="text-align:center; margin-top: 100px;font-size:26px;">Please <a href="index.php?page=login">Login </a> first</h2>
    <?php require_once 'footer.php'; ?>
 
</body>
</html>