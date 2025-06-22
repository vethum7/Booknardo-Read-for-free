<?php
header('Content-Type: application/json');
require_once 'includes/auth.php';
require_once 'includes/db.php';

// Verify this request is coming from the admin interface
$admin_token = $_SERVER['HTTP_X_ADMIN_TOKEN'] ?? '';
if ($admin_token !== 'SECURE_ADMIN_TOKEN') {
    http_response_code(403);
    die(json_encode(['error' => 'Unauthorized access']));
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'update_website_settings':
        // Handle website name/logo updates
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate and sanitize input
        $website_name = filter_var($data['name'] ?? '', FILTER_SANITIZE_STRING);
        $logo_url = filter_var($data['logo'] ?? '', FILTER_SANITIZE_URL);
        
        if (empty($website_name)) {
            http_response_code(400);
            echo json_encode(['error' => 'Website name is required']);
            exit;
        }
        
        // Update in database
        try {
            $stmt = $db->prepare("UPDATE website_settings SET value = ? WHERE setting = 'name'");
            $stmt->execute([$website_name]);
            
            if (!empty($logo_url)) {
                $stmt = $db->prepare("UPDATE website_settings SET value = ? WHERE setting = 'logo'");
                $stmt->execute([$logo_url]);
            }
            
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
        break;
        
    case 'update_advertisements':
        // Handle ad updates
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate ads
        $ads = [];
        foreach ($data['ads'] as $ad) {
            $ad_id = filter_var($ad['id'] ?? '', FILTER_SANITIZE_STRING);
            $ad_content = filter_var($ad['content'] ?? '', FILTER_SANITIZE_STRING);
            $ad_position = filter_var($ad['position'] ?? '', FILTER_SANITIZE_STRING);
            
            if (empty($ad_id) || empty($ad_content) || empty($ad_position)) {
                continue;
            }
            
            $ads[] = [
                'id' => $ad_id,
                'content' => $ad_content,
                'position' => $ad_position,
                'active' => (bool)($ad['active'] ?? false)
            ];
        }
        
        // Update in database
        try {
            $db->beginTransaction();
            
            // Clear existing ads
            $db->exec("DELETE FROM advertisements");
            
            // Insert new ads
            $stmt = $db->prepare("INSERT INTO advertisements (id, content, position, is_active) VALUES (?, ?, ?, ?)");
            foreach ($ads as $ad) {
                $stmt->execute([$ad['id'], $ad['content'], $ad['position'], $ad['active'] ? 1 : 0]);
            }
            
            $db->commit();
            echo json_encode(['success' => true, 'count' => count($ads)]);
        } catch (PDOException $e) {
            $db->rollBack();
            http_response_code(500);
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
        break;
        
    case 'get_novel_data':
        // Get novel data for reader interface
        $novel_id = filter_var($_GET['novel_id'] ?? 0, FILTER_VALIDATE_INT);
        
        if (!$novel_id) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid novel ID']);
            exit;
        }
        
        try {
            // Get novel info
            $stmt = $db->prepare("SELECT * FROM books WHERE id = ?");
            $stmt->execute([$novel_id]);
            $novel = $stmt->fetch();
            
            if (!$novel) {
                http_response_code(404);
                echo json_encode(['error' => 'Novel not found']);
                exit;
            }
            
            // Get chapters
            $stmt = $db->prepare("SELECT * FROM chapters WHERE book_id = ? ORDER BY chapter_number");
            $stmt->execute([$novel_id]);
            $chapters = $stmt->fetchAll();
            
            echo json_encode([
                'novel' => $novel,
                'chapters' => $chapters
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
        break;
        
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
        break;
}