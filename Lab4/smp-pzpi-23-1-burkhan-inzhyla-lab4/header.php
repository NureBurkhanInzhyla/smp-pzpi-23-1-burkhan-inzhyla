<div class="nav">
  <a href="#">Home</a>
  <a href="index.php?page=products">Products</a>

  <?php if (isset($_SESSION['user'])): ?>
    <a href="index.php?page=cart">Cart</a>
    <a href="index.php?page=profile">Profile</a>

    <form method="post" action="profileFunctions.php" style="display:inline;">
      <button type="submit" name="logout" style="
        background: none;
        border: none;
        color: black;
        font-size: 19px;
        cursor: pointer;
        padding: 0;
        margin: 0;
        font-family: inherit;
      ">Logout</button>
    </form>

  <?php else: ?>
    <a href="index.php?page=login">Login</a>
  <?php endif; ?>
</div>
