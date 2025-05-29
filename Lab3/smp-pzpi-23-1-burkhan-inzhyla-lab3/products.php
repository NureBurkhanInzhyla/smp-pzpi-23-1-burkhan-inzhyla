<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Products</title>
  <style>
   
    .product { margin: 10px 0; }
  </style>
   <?php
      session_start();
      require 'functionsDB.php';

      $pdo = db();
      $sessionId = session_id();
      $products = getProducts($pdo);

      $selected = false; 

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          foreach ($_POST['product'] as $id => $count) {
              if ($count > 0) {
                  addToCart($pdo, $sessionId, (int)$id, $count);
                  $selected = true; 
              }
          }

          if (!$selected) {
            $errorMessage = "Виберіть будь ласка хоча б один товар";
        } else {
            header("Location: products.php");
            exit;
        }
      }

    ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Agdasima:wght@400;700&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Radley:ital@0;1&family=Red+Hat+Display:ital,wght@0,300..900;1,300..900&family=Rethink+Sans:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Lab3Styles.css">
</head>
<body>
  <header>
      <?php
          require_once 'header.html';
      ?>
  </header>

  <div class="productsBlock">
  <?php if (isset($errorMessage)): ?>
    <p style="color:red;"><?= $errorMessage ?></p>
  <?php endif; ?>
  <form action="products.php" method="post">
    <?php foreach ($products as $product): ?>
      <div class="product">
        <span><?= $product['name'] ?></span>
        <span>$<?= $product['price'] ?></span>
        <input type="number" name="product[<?= $product['product_id'] ?>]" min="0" value="0">
      </div>
    <?php endforeach; ?>
    <button type="submit">Add to cart</button>
  </form>

</div>

<?php require_once 'footer.html'; ?>
  
</body>
</html>
