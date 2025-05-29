<?php 

function db(): PDO {
   return  new PDO('sqlite:lab3.db');
}

function getProducts(PDO $pdo): array {
    return $pdo->query("SELECT * FROM Product")->fetchAll(PDO::FETCH_ASSOC);
}

function addToCart(PDO $pdo, string $sessionId, int $productId, int $quantity): void {
    $stmt = $pdo->prepare("SELECT * FROM Cart WHERE session_id = ? AND product_id = ?");
    $stmt->execute([$sessionId, $productId]);
    $item = $stmt->fetch();

    if ($item) {
        $stmt = $pdo->prepare("UPDATE Cart SET quantity = quantity + ? WHERE session_id = ? AND product_id = ?");
        $stmt->execute([$quantity, $sessionId, $productId]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO Cart (session_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$sessionId, $productId, $quantity]);
    }
}

?>