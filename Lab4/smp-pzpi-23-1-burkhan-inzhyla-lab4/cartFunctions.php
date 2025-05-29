<?php
require_once 'functionsDB.php';

function removeFromCart(PDO $pdo, string $sessionId, int $productId): void {
    $stmt = $pdo->prepare("DELETE FROM Cart WHERE session_id = ? AND product_id = ?");
    $stmt->execute([$sessionId, $productId]);
}
function clearCart(PDO $pdo, string $sessionId): void {
    $stmt = $pdo->prepare("DELETE FROM Cart WHERE session_id = ?");
    $stmt->execute([$sessionId]);
}

function getCart(PDO $pdo, string $sessionId): array {
    $stmt = $pdo->prepare("
        SELECT p.product_id, p.name, p.price, c.quantity, p.price * c.quantity AS sum
        FROM Cart c
        JOIN Product p ON c.product_id = p.product_id
        WHERE c.session_id = ?
    ");
    $stmt->execute([$sessionId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
