<?php
require __DIR__ . '/../controllers/db.php';
$conn = dbConnect();

// Fetch top 4 FAQs created by admin
$query = "
    SELECT question, answer
    FROM faqs
    ORDER BY created_at DESC
    LIMIT 4
";

$result = $conn->query($query);
$faqs = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $faqs[] = [
            'question' => $row['question'],
            'answer' => $row['answer']
        ];
    }
}

echo json_encode($faqs);
$conn->close();
?>
