<?php
require_once 'functionsDB.php';

$pdo = db();
$sessionId = session_id();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    if ($_POST['action'] === 'remove' && isset($_POST['product_id'])) {
        echo "remove";
        removeFromCart($pdo, $sessionId, (int)$_POST['product_id']);
    } elseif ($_POST['action'] === 'clear') {
        clearCart($pdo, $sessionId);
    }
    header("Location: cart.php");
    exit;
}

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
$cart = getCart($pdo, $sessionId);
?>
