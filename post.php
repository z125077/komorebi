<?php 
include('header.php');
$post_id = $_GET['id'] ?? die('No post specified.');

try {
    // Fetch post
    $stmt = $pdo->prepare("
        SELECT p.*, u.username 
        FROM posts p
        JOIN users u ON p.author_id = u.id
        WHERE p.id = ?
    ");
    $stmt->execute([$post_id]);
    $post = $stmt->fetch();
    if (!$post) die('Post not found.');

    // Update view count
    $pdo->prepare("UPDATE posts SET views = views + 1 WHERE id = ?")
        ->execute([$post_id]);

    // Handle likes
    if (!empty($_SESSION['user_id']) && isset($_POST['like'])) {
        $likeStmt = $pdo->prepare("
            INSERT INTO post_likes (user_id, post_id) 
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE id=id
        ");
        $likeStmt->execute([$_SESSION['user_id'], $post_id]);
        header("Location: post.php?id=$post_id");
        exit;
    }

    // Handle comments
    if (!empty($_SESSION['user_id']) && isset($_POST['comment'])) {
        $comment = trim($_POST['comment']);
        if (!empty($comment)) {
            $pdo->prepare("
                INSERT INTO comments (post_id, user_id, content)
                VALUES (?, ?, ?)
            ")->execute([$post_id, $_SESSION['user_id'], $comment]);
            header("Location: post.php?id=$post_id");
            exit;
        }
    }

    // Get comments
    $comments = $pdo->prepare("
        SELECT c.*, u.username 
        FROM comments c
        JOIN users u ON c.user_id = u.id
        WHERE c.post_id = ?
        ORDER BY c.created_at
    ")->execute([$post_id]);

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<div class="container">
    <h2><?= htmlspecialchars($post['title']) ?></h2>
    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
    <div>By <b><?= htmlspecialchars($post['username']) ?></b> on 
        <?= date('m/d/Y H:i', strtotime($post['created_at'])) ?>
    </div>
    
    <!-- Like Button -->
    <?php if (!empty($_SESSION['user_id'])): ?>
        <form method="post" class="like-form">
            <button type="submit" name="like">❤ Like</button>
        </form>
    <?php endif; ?>
    
    <!-- Comments Section -->
    <h4>Comments</h4>
    <?php while($comment = $comments->fetch()): ?>
        <div class="comment">
            <b><?= htmlspecialchars($comment['username']) ?>:</b>
            <?= htmlspecialchars($comment['content']) ?>
        </div>
    <?php endwhile; ?>
    
    <!-- Comment Form -->
    <?php if (!empty($_SESSION['user_id'])): ?>
        <form method="post">
            <textarea name="comment" required placeholder="Add a comment"></textarea>
            <button type="submit">Post Comment</button>
        </form>
    <?php endif; ?>
</div>
<?php include('footer.php'); ?>