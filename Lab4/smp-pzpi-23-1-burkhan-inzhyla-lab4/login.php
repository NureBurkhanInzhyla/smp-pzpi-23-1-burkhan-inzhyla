<?php 

    require_once "profileFunctions.php";
    require_once "functionsDB.php";
    $pdo = db();

    $error='';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (loginOrRegister($pdo, $_POST['username'], $_POST['password'])) {
            header("Location: index.php?page=profile");
            exit;
        }
        else{
            $error = "False username or password";
        }

    }
?>

<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8">
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
  <div class="loginBody">
    <?php if ($error): ?>
        <p style="color:red"><?= $error ?></p>
    <?php endif; ?>
    <form method="post" action="login.php">
        <label>Username: <input type="text" name="username" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <button type="submit">Login</button>
    </form>
  </div>
  <?php require_once 'footer.php'; ?>

</body>
</html>
