<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/header.php';

$chapter_id = $_GET['chapter_id'] ?? 0;

// Get chapter details
$stmt = $db->prepare("SELECT chapters.*, books.title as book_title 
                     FROM chapters 
                     JOIN books ON chapters.book_id = books.id 
                     WHERE chapters.id = ?");
$stmt->execute([$chapter_id]);
$chapter = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$chapter) {
    header("Location: index.php");
    exit;
}
?>

<div class="admin-container">
    <h1>Edit Chapter: <?= htmlspecialchars($chapter['title']) ?></h1>
    <h2>From: <?= htmlspecialchars($chapter['book_title']) ?></h2>
    
    <form id="editChapterForm" class="chapter-form">
        <input type="hidden" name="chapter_id" value="<?= $chapter['id'] ?>">
        
        <div class="form-group">
            <label for="chapterNumber">Chapter Number</label>
            <input type="number" id="chapterNumber" name="chapterNumber" value="<?= $chapter['chapter_number'] ?>" min="1" step="1">
        </div>
        
        <div class="form-group">
            <label for="chapterTitle">Chapter Title</label>
            <input type="text" id="chapterTitle" name="chapterTitle" value="<?= htmlspecialchars($chapter['title']) ?>">
        </div>
        
        <div class="form-group">
            <label for="chapterStatus">Status</label>
            <select id="chapterStatus" name="chapterStatus">
                <option value="draft" <?= $chapter['status'] == 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="published" <?= $chapter['status'] == 'published' ? 'selected' : '' ?>>Published</option>
                <option value="scheduled" <?= $chapter['status'] == 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
            </select>
        </div>
        
        <div class="form-group scheduled-date" style="<?= $chapter['status'] == 'scheduled' ? '' : 'display: none;' ?>">
            <label for="publishDate">Publish Date & Time</label>
            <input type="datetime-local" id="publishDate" name="publishDate" value="<?= date('Y-m-d\TH:i', strtotime($chapter['publish_date'])) ?>">
        </div>
        
        <div class="form-group">
            <label for="chapterContent">Chapter Content</label>
            <textarea id="chapterContent" name="chapterContent" rows="20"><?= htmlspecialchars($chapter['content']) ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="chapterNotes">Author Notes (Optional)</label>
            <textarea id="chapterNotes" name="chapterNotes" rows="5"><?= htmlspecialchars($chapter['notes']) ?></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="novel-control.php?book_id=<?= $chapter['book_id'] ?>" class="btn btn-secondary">Cancel</a>
            <button type="button" id="deleteChapterBtn" class="btn btn-danger">Delete Chapter</button>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>