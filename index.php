<?php include('header.php'); ?>
<div class="container">
    <h2>Recent Posts</h2>
    <?php
    try {
        $stmt = $pdo->query("
            SELECT p.*, u.username 
            FROM posts p
            JOIN users u ON p.author_id = u.id
            ORDER BY p.created_at DESC
            LIMIT 20
        ");
        
        while($post = $stmt->fetch()): ?>
            <div class="post">
                <h3><a href="post.php?id=<?= $post['id'] ?>">
                    <?= htmlspecialchars($post['title']) ?>
                </a></h3>
                <div>By <b><?= htmlspecialchars($post['username']) ?></b> on 
                    <?= date('m/d/Y H:i', strtotime($post['created_at'])) ?>
                </div>
                <span class="likes">❤ <?= $post['likes'] ?></span>
            </div>
        <?php endwhile;
    } catch (PDOException $e) {
        echo "<p>Error loading posts: " . $e->getMessage() . "</p>";
    }
    ?>
</div>
<?php include('footer.php'); ?>
