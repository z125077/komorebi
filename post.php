<?php include('header.php');
$id = $_GET['id'] ?? null;
if (!$id) die('No post specified.');
$posts = array_map('str_getcsv', file('posts.csv'));
$post = null; foreach ($posts as $p) { if ($p[0] == $id) $post = $p; }
if (!$post) die('Post not found.');
?>
<div class="container">
    <h2><?= htmlspecialchars($post[1]) ?></h2>
    <p><?= nl2br(htmlspecialchars($post[2])) ?></p>
    <div>By <b><?= htmlspecialchars($post[3]) ?></b> on <?= htmlspecialchars($post[4]) ?></div>
    <?php
        // Like button
        if(!empty($_SESSION['username'])){
            $userhash = md5($_SESSION['username']); // Simple unique id for user
            $liked = false;
            if(file_exists('likes.csv')){
                foreach(file('likes.csv') as $like){
                    list($uid, $pid) = str_getcsv($like);
                    if($uid==$userhash && $pid==$id) $liked = true;
                }
            }
            echo "<form method='post' style='display:inline;'>
                    <input type='hidden' name='like' value='1'>
                    <button class='btn' type='submit'>" . ($liked?'❤️ Unlike':'♡ Like') . "</button>
                </form>";
        }
        // Likes count
        $likes = 0;
        if(file_exists('likes.csv')){
            foreach(file('likes.csv') as $like){
                list($uid, $pid) = str_getcsv($like);
                if($pid==$id) $likes++;
            }
        }
        echo "<span class='likes'>❤ $likes</span>";

        if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['like']) && !empty($_SESSION['username'])){
            $userhash = md5($_SESSION['username']);
            $lines = file_exists('likes.csv') ? file('likes.csv') : [];
            $found = false;
            foreach($lines as $i=>$line){
                list($uid, $pid) = str_getcsv($line);
                if($uid==$userhash && $pid==$id){
                    unset($lines[$i]);
                    $found = true;
                }
            }
            if(!$found) $lines[] = "$userhash,$id\n";
            file_put_contents('likes.csv', implode('', $lines));
            header("Location: post.php?id=$id");
            exit;
        }
    ?>
    <hr>
    <h4>Comments</h4>
    <?php
    if(file_exists('comments.csv')) {
        $comm = array_map('str_getcsv', file('comments.csv'));
        foreach($comm as $c) {
            if(count($c)<4) continue;
            [$cid, $pid, $author, $body] = $c;
            if($pid!=$id) continue;
            echo "<div class='comment'><b>".htmlspecialchars($author).": </b>".htmlspecialchars($body)."</div>";
        }
    }
    if(!empty($_SESSION['username'])): ?>
    <form method="post">
        <textarea name="comment" required placeholder="Add a comment"></textarea>
        <button class="btn" type="submit" name="addcomment">Post Comment</button>
    </form>
    <?php
        if(isset($_POST['addcomment']) && !empty(trim($_POST['comment']))) {
            $new = [];
            $body = str_replace(["\r","\n",","],[' ',' ',' '], $_POST['comment']);
            $cid = uniqid();
            $line = "$cid,$id,{$_SESSION['username']},$body\n";
            file_put_contents('comments.csv', $line, FILE_APPEND);
            header("Location: post.php?id=$id");
            exit;
        }
    endif;
    ?>
</div>
<?php include('footer.php'); ?>
