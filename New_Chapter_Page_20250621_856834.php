<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/header.php';

$book_id = $_GET['book_id'] ?? 0;

// Get book details
$stmt = $db->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    header("Location: index.php");
    exit;
}

// Get next chapter number
$stmt = $db->prepare("SELECT MAX(chapter_number) as max_chapter FROM chapters WHERE book_id = ?");
$stmt->execute([$book_id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$next_chapter = ($result['max_chapter'] ?? 0) + 1;
?>

<div class="admin-container">
    <h1>Add New Chapter to <?= htmlspecialchars($book['title']) ?></h1>
    
    <form id="newChapterForm" class="chapter-form">
        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
        
        <div class="form-group">
            <label for="chapterNumber">Chapter Number</label>
            <input type="number" id="chapterNumber" name="chapterNumber" value="<?= $next_chapter ?>" min="1" step="1">
        </div>
        
        <div class="form-group">
            <label for="chapterTitle">Chapter Title</label>
            <input type="text" id="chapterTitle" name="chapterTitle" placeholder="Chapter Title">
        </div>
        
        <div class="form-group">
            <label for="chapterStatus">Status</label>
            <select id="chapterStatus" name="chapterStatus">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
                <option value="scheduled">Scheduled</option>
            </select>
        </div>
        
        <div class="form-group scheduled-date" style="display: none;">
            <label for="publishDate">Publish Date & Time</label>
            <input type="datetime-local" id="publishDate" name="publishDate">
        </div>
        
        <div class="form-group">
            <label for="chapterContent">Chapter Content</label>
            <textarea id="chapterContent" name="chapterContent" rows="20"></textarea>
        </div>
        
        <div class="form-group">
            <label for="chapterNotes">Author Notes (Optional)</label>
            <textarea id="chapterNotes" name="chapterNotes" rows="5"></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Chapter</button>
            <a href="novel-control.php?book_id=<?= $book['id'] ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>