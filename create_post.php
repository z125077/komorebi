<?php include('header.php');
if(empty($_SESSION['username'])) header("Location: signin.php");
if($_SERVER['REQUEST_METHOD']=='POST'){
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    if($title && $body){
        $pid = uniqid();
        $author = $_SESSION['username'];
        $dt = date('Y-m-d H:i');
        file_put_contents('posts.csv', "$pid,$title,$body,$author,$dt\n", FILE_APPEND);
        header('Location: index.php'); exit;
    }
    $err = "All fields required!";
}
?>
<div class="container">
    <h2>New Post</h2>
    <?php if(!empty($err)) echo "<p style='color:red;'>$err</p>"; ?>
    <form method="post">
        <input name="title" required maxlength="120" placeholder="Title">
        <textarea name="body" required rows="8" maxlength="1000" placeholder="Post content"></textarea>
        <button class="btn" type="submit">Post</button>
    </form>
</div>
<?php include('footer.php'); ?>
