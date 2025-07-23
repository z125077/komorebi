<?php 
include('header.php');
if (empty($_SESSION['user_id'])) header("Location: signin.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    
    if (!empty($title) && !empty($body)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO posts (title, content, author_id)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$title, $body, $_SESSION['user_id']]);
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $error = "Error creating post: " . $e->getMessage();
        }
    } else {
        $error = "Title and content are required!";
    }
}
?>
<div class="container">
    <h2>New Post</h2>
    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="title" required placeholder="Title">
        <textarea name="body" required placeholder="Post content"></textarea>
        <button type="submit">Create Post</button>
    </form>
</div>
<?php include('footer.php'); ?>
