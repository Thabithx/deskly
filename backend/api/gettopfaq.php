<?php
require __DIR__ . '/../controllers/db.php';
$conn = dbConnect();

// Fetch top 4 most frequently asked answered messages
$query = "
    SELECT message, answer, COUNT(message) as freq
    FROM contact_messages
    WHERE status = 'Answered'
    GROUP BY message, answer
    ORDER BY freq DESC
    LIMIT 4
";

$result = $conn->query($query);
$faqs = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $faqs[] = [
            'question' => $row['message'],
            'answer' => $row['answer'],
            'freq' => $row['freq']
        ];
    }
}

echo json_encode($faqs);
$conn->close();
?>
