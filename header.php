<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Komorebi</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
    <h1>Komorebi</h1>
    <nav>
        <a href="index.php">Home</a>
        <?php if(empty($_SESSION['user_id'])): ?>
            <a href="signup.php">Sign Up</a>
            <a href="signin.php">Sign In</a>
        <?php else: ?>
            <span>Hello, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
            <a href="create_post.php">New Post</a>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </nav>
</header>
