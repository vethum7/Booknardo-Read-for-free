<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/header.php';

$book_id = $_GET['book_id'] ?? 0;

// Get book details
$stmt = $db->prepare("SELECT books.*, authors.name as author_name 
                     FROM books 
                     JOIN authors ON books.author_id = authors.id 
                     WHERE books.id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    header("Location: index.php");
    exit;
}

// Get chapters for this book
$stmt = $db->prepare("SELECT * FROM chapters WHERE book_id = ? ORDER BY chapter_number");
$stmt->execute([$book_id]);
$chapters = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-container">
    <div class="book-header">
        <div class="book-cover">
            <img src="<?= !empty($book['cover_image']) ? $book['cover_image'] : 'images/default-cover.jpg' ?>" alt="<?= htmlspecialchars($book['title']) ?>">
        </div>
        <div class="book-details">
            <h1><?= htmlspecialchars($book['title']) ?></h1>
            <p>Author: <?= htmlspecialchars($book['author_name']) ?></p>
            <p>Status: <?= ucfirst($book['status']) ?></p>
            <p>Genre: <?= htmlspecialchars($book['genre']) ?></p>
            <p>Chapters: <?= count($chapters) ?></p>
        </div>
    </div>
    
    <div class="book-control-tabs">
        <button class="tab-btn active" data-tab="chapters">Chapters</button>
        <button class="tab-btn" data-tab="settings">Book Settings</button>
        <button class="tab-btn" data-tab="statistics">Statistics</button>
    </div>
    
    <div class="tab-content">
        <div class="tab-pane active" id="chapters">
            <div class="chapter-actions">
                <a href="chapter-new.php?book_id=<?= $book['id'] ?>" class="btn btn-success">Add New Chapter</a>
            </div>
            
            <div class="chapters-list">
                <table>
                    <thead>
                        <tr>
                            <th>Chapter</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Published</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($chapters as $chapter): ?>
                        <tr>
                            <td><?= $chapter['chapter_number'] ?></td>
                            <td><?= htmlspecialchars($chapter['title']) ?></td>
                            <td><?= ucfirst($chapter['status']) ?></td>
                            <td><?= number_format($chapter['views'] ?? 0) ?></td>
                            <td><?= date('M d, Y', strtotime($chapter['published_at'])) ?></td>
                            <td>
                                <a href="chapter-edit.php?chapter_id=<?= $chapter['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                <button class="btn btn-sm btn-danger delete-chapter" data-chapter-id="<?= $chapter['id'] ?>">Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="tab-pane" id="settings">
            <form class="book-settings-form">
                <div class="form-group">
                    <label for="bookTitle">Book Title</label>
                    <input type="text" id="bookTitle" name="bookTitle" value="<?= htmlspecialchars($book['title']) ?>">
                </div>
                
                <div class="form-group">
                    <label for="bookGenre">Genre</label>
                    <select id="bookGenre" name="bookGenre">
                        <option value="Fantasy" <?= $book['genre'] == 'Fantasy' ? 'selected' : '' ?>>Fantasy</option>
                        <option value="Sci-Fi" <?= $book['genre'] == 'Sci-Fi' ? 'selected' : '' ?>>Sci-Fi</option>
                        <option value="Romance" <?= $book['genre'] == 'Romance' ? 'selected' : '' ?>>Romance</option>
                        <!-- More genres -->
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="bookStatus">Status</label>
                    <select id="bookStatus" name="bookStatus">
                        <option value="ongoing" <?= $book['status'] == 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
                        <option value="completed" <?= $book['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="hiatus" <?= $book['status'] == 'hiatus' ? 'selected' : '' ?>>Hiatus</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="bookCover">Cover Image</label>
                    <input type="file" id="bookCover" name="bookCover">
                    <div class="current-cover">
                        <img src="<?= !empty($book['cover_image']) ? $book['cover_image'] : 'images/default-cover.jpg' ?>" alt="Current Cover" style="max-width: 200px;">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="bookDescription">Description</label>
                    <textarea id="bookDescription" name="bookDescription" rows="5"><?= htmlspecialchars($book['description']) ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
        
        <div class="tab-pane" id="statistics">
            <div class="stats-container">
                <div class="stat-card">
                    <h3>Total Views</h3>
                    <p><?= number_format(array_sum(array_column($chapters, 'views'))) ?></p>
                </div>
                <div class="stat-card">
                    <h3>Average Views/Chapter</h3>
                    <p><?= number_format(array_sum(array_column($chapters, 'views')) / max(count($chapters), 1) ?></p>
                </div>
                <!-- More stats -->
            </div>
            
            <div class="stats-chart">
                <canvas id="viewsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>