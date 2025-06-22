<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/header.php';

$author_id = $_GET['id'] ?? 0;

// Get author details
$stmt = $db->prepare("SELECT * FROM authors WHERE id = ?");
$stmt->execute([$author_id]);
$author = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$author) {
    header("Location: index.php");
    exit;
}

// Get author's books with view counts
$stmt = $db->prepare("SELECT books.*, SUM(chapter_views.views) as total_views 
                     FROM books 
                     LEFT JOIN chapters ON books.id = chapters.book_id 
                     LEFT JOIN chapter_views ON chapters.id = chapter_views.chapter_id 
                     WHERE books.author_id = ? 
                     GROUP BY books.id");
$stmt->execute([$author_id]);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-container">
    <div class="author-header">
        <div class="author-avatar">
            <img src="<?= !empty($author['avatar']) ? $author['avatar'] : 'images/default-avatar.jpg' ?>" alt="<?= htmlspecialchars($author['name']) ?>">
        </div>
        <div class="author-details">
            <h1><?= htmlspecialchars($author['name']) ?></h1>
            <p>Author ID: <?= $author['id'] ?></p>
            <p>Email: <?= htmlspecialchars($author['email']) ?></p>
            <p>Joined: <?= date('M d, Y', strtotime($author['created_at'])) ?></p>
        </div>
    </div>
    
    <div class="author-books">
        <h2>Books (<?= count($books) ?>)</h2>
        
        <div class="books-grid">
            <?php foreach ($books as $book): ?>
            <div class="book-card">
                <div class="book-cover">
                    <img src="<?= !empty($book['cover_image']) ? $book['cover_image'] : 'images/default-cover.jpg' ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                </div>
                <div class="book-info">
                    <h3><?= htmlspecialchars($book['title']) ?></h3>
                    <p>Total Views: <?= number_format($book['total_views'] ?? 0) ?></p>
                    <p>Status: <?= ucfirst($book['status']) ?></p>
                    <p>Created: <?= date('M d, Y', strtotime($book['created_at'])) ?></p>
                </div>
                <div class="book-actions">
                    <a href="novel-control.php?book_id=<?= $book['id'] ?>" class="btn btn-primary">Manage</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="author-actions">
        <button class="btn btn-danger" id="deleteAuthorBtn">Delete Author</button>
        <a href="#" class="btn btn-secondary">Edit Profile</a>
        <a href="#" class="btn btn-success">Add New Book</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>