
<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once "functionsDB.php";
    require_once "profileFunctions.php";

    $pdo = db();
    $username = $_SESSION['user'];
    $error = '';
    $success = '';

    $user = getUserProfile($pdo, $username);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (
            empty($_POST['name']) ||
            empty($_POST['surname']) ||
            empty($_POST['birthday']) ||
            empty($_POST['description'])
        ) {
            $error = 'Всі поля мають бути заповнені.';
        }
        elseif (!is_string($_POST['name']) || strlen(trim($_POST['name'])) <= 1) {
            $error = 'Ім\'я має містити більше одного символу.';
        }
        elseif (!is_string($_POST['surname']) || strlen(trim($_POST['surname'])) <= 1) {
            $error = 'Прізвище має містити більше одного символу.';
        }
        elseif (isset($_POST['birthday'])) {
            $birthday = new DateTime($_POST['birthday']);
            $today = new DateTime();
            $ageInterval = $today->diff($birthday);
            if ($ageInterval->y < 16) {
                $error = 'Користувач має бути не молодший за 16 років.';
            }
        }

        elseif (strlen(trim($_POST['description'])) < 50) {
            $error = 'Стисла інформація має містити не менше 50 символів.';
        }

        $profile_image_path = $user['profile_image'] ?? '';

        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true); 
            }
            $tmpName = $_FILES['profile_image']['tmp_name'];
            $fileName = basename($_FILES['profile_image']['name']);
            $targetFile = $uploadDir . uniqid() . "_" . $fileName;

            if (move_uploaded_file($tmpName, $targetFile)) {
                $profile_image_path = $targetFile;
            } else {
                $error = "Error loading image";
            }
        }

        if (!$error) {
                $data = [
                    'name' => $_POST['name'] ?? '',
                    'surname' => $_POST['surname'] ?? '',
                    'birthday' => $_POST['birthday'] ?? null,
                    'profile_image' => $profile_image_path,
                    'description' => $_POST['description'] ?? '',
                ];

                if (updateUserProfile($pdo, $username, $data)) {
                    $success = "Profile is updated";
                    $user = getUserProfile($pdo, $username); 
                } else {
                    $error = "Failed updating profile.";
                }
        }
        

    }

?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Products</title>
  <style>
   
    .product { margin: 10px 0; }
  </style>


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
  <form method="post" action="profile.php" enctype="multipart/form-data">
    <div class="profileBody">
        <div class="profileLeft">
            <h2 style="color:#363636;">Профіль користувача: <?= ($username) ?></h2>

            <?php if ($error): ?>
                <p style="color:red;"><?= $error ?></p>
            <?php endif; ?>

            <?php if ($success): ?>
                <p style="color:green;"><?= $success ?></p>
            <?php endif; ?>

            <div class="profileImage">
                <label>Profile image:</label><br/>
                <?php if (!empty($user['profile_image'])): ?>
                    <img style="margin-top:5px;" src="<?= ($user['profile_image']) ?>" alt="Profile Image" width="150" /><br/>
                <?php endif; ?>
                <input type="file" name="profile_image" accept="image/*" />
            </div>
        </div>

        <div class="profileRight">
            <div>
                <label>Name:</label>
                <input type="text" name="name" value="<?= ($user['name'] ?? '') ?>" />
            </div>

            <div>
                <label>Surname:</label>
                <input type="text" name="surname" value="<?= ($user['surname'] ?? '') ?>" />
            </div>

            <div>
                <label>Date of Birth:</label>
                <input type="date" name="birthday" value="<?= ($user['birthday'] ?? '') ?>" />
            </div>

           <div class="formRow">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4"><?= $user['description'] ?? '' ?></textarea>
            </div>


            <div>
                <button type="submit">Save</button>
            </div>
        </div>
    </div>
</form>


<?php require_once 'footer.php'; ?>
  
</body>
</html>