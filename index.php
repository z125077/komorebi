<?php include('header.php'); ?>
<div class="container">
    <h2>Recent Posts</h2>
    <?php
    $posts = array_map('str_getcsv', file('posts.csv'));
    foreach(array_reverse($posts) as $post) {
        if(count($post) < 5) continue;
        [$pid, $title, $body, $author, $dt] = $post;
        echo "<div class='post'>";
        echo "<h3><a href='post.php?id=$pid'>" . htmlspecialchars($title) . "</a></h3>";
        echo "<div>By <b>" . htmlspecialchars($author) . "</b> on " . htmlspecialchars($dt) . "</div>";
        // Show like count
        $likes = 0;
        if(file_exists('likes.csv')){
            foreach(file('likes.csv') as $like){
                list($uid, $postid) = str_getcsv($like);
                if($postid == $pid) $likes++;
            }
        }
        echo "<span class='likes'>❤ $likes</span>";
        echo "</div>";
    }
    ?>
</div>
<?php include('footer.php'); ?>
