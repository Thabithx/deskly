<?php
require __DIR__ . '/../controllers/db.php';

$conn = dbConnect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['id'] ?? null;

    if ($productId) {
        //Delete product
        $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $productId);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "Failed to delete product"]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Invalid product ID"]);
    }

    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid request"]);
}
?>
