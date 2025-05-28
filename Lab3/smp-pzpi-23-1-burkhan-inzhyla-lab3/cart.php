<?php
session_start();
require_once 'cartFunctions.php';
require_once 'functionsDB.php';

$pdo = db();
$sessionId = session_id();

$cart = getCart($pdo, $sessionId);

?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Cart</title>
  <style>
    body { font-family: "Montserrat"; }
    table { width: 80%; margin: 20px auto; border-collapse: collapse; }
    th, td { border: 1px solid black; padding: 10px; text-align: center; }
    .buttons { text-align: center; margin: 20px; }
    .nav, .footer {
      display: flex;
      justify-content: space-around;
      padding: 10px;
      background-color: #f0f0f0;
    }
    a {
      text-decoration: none;
      color: black;
    }
  </style>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="Lab3Styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

  <div class="nav">
    <a href="#">Home</a>
    <a href="products.php">Products</a>
    <a href="cart.php">Cart</a>
  </div>

  <div class="cartBody">

      <?php if (empty($cart)): ?>
        <p style="text-align:center;">Ще не вибрано товари. <a href="products.php">Перейти до покупок</a></p>
      <?php else: ?>
        <table>
          <tr>
            <th>id</th>
            <th>name</th>
            <th>price</th>
            <th>count</th>
            <th>sum</th>
            <th>delete</th>
          </tr>

          <?php
          $total = 0;
          foreach ($cart as $item):
            $total += $item['sum'];
          ?>
            <tr>
              <td><?= htmlspecialchars($item['product_id']) ?></td>
              <td><?= htmlspecialchars($item['name']) ?></td>
              <td>$<?= number_format($item['price'], 2) ?></td>
              <td><?= htmlspecialchars($item['quantity']) ?></td>
              <td>$<?= number_format($item['sum'], 2) ?></td>
              <td>
                <form method="post" action="cart.php" style="display:inline;">
                  <input type="hidden" name="action" value="remove">
                  <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                  <button type="submit">🗑️</button>
                </form>

              </td>
            </tr>
          <?php endforeach; ?>

          <tr>
            <td colspan="4"><strong>Total</strong></td>
            <td colspan="2"><strong>$<?= number_format($total, 2) ?></strong></td>
          </tr>
        </table>

        <div class="buttons">
         <form method="post" action="cart.php">
            <input type="hidden" name="action" value="clear">
            <button type="submit">Clear</button>
          </form>



          <form action="checkout.php" method="post" style="display:inline;">
            <button type="submit">Pay</button>
          </form>
        </div>
      <?php endif; ?>
      </div>
  <div class="footer">
    <a href="#">Home</a>
    <a href="products.php">Products</a>
    <a href="cart.php">Cart</a>
  </div>

</body>
</html>
