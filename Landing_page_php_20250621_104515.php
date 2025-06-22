<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/header.php';

// Get all authors from database
$query = "SELECT authors.*, COUNT(books.id) as book_count 
          FROM authors 
          LEFT JOIN books ON authors.id = books.author_id 
          GROUP BY authors.id";
$authors = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-container">
    <h1>Author Management</h1>
    
    <div class="search-box">
        <input type="text" id="authorSearch" placeholder="Search by Author ID or Name...">
        <button id="searchBtn"><i class="fas fa-search"></i></button>
    </div>
    
    <div class="author-list">
        <?php foreach ($authors as $author): ?>
        <div class="author-card" data-author-id="<?= $author['id'] ?>">
            <div class="author-avatar">
                <img src="<?= !empty($author['avatar']) ? $author['avatar'] : 'images/default-avatar.jpg' ?>" alt="<?= htmlspecialchars($author['name']) ?>">
            </div>
            <div class="author-info">
                <h3><?= htmlspecialchars($author['name']) ?></h3>
                <p>Author ID: <?= $author['id'] ?></p>
                <p>Books: <?= $author['book_count'] ?></p>
            </div>
            <div class="author-actions">
                <a href="author-profile.php?id=<?= $author['id'] ?>" class="btn btn-primary">View Profile</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>