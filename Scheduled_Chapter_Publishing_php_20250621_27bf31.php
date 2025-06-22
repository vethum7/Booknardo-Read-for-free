<?php
require __DIR__ . '/../includes/db.php';

// Find chapters scheduled for publishing
$now = date('Y-m-d H:i:s');
$stmt = $db->prepare("SELECT id FROM chapters WHERE status = 'scheduled' AND publish_date <= ?");
$stmt->execute([$now]);
$chapters = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($chapters as $chapter) {
    $db->prepare("UPDATE chapters SET status = 'published' WHERE id = ?")->execute([$chapter['id']]);
    echo "Published chapter ID: " . $chapter['id'] . "\n";
}