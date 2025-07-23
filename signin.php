<?php include('header.php');
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = trim($_POST['email'] ?? '');
    $pw = $_POST['password'] ?? '';
    $users = file_exists('user.csv') ? array_map('str_getcsv', file('user.csv')) : [];
    foreach($users as $user) {
        if($user[1] == $email && password_verify($pw, $user[2])) {
            $_SESSION['username'] = $user[3];
            header('Location: index.php'); exit;
        }
    }
    $err = "Wrong email or password!";
}
?>
<div class="container">
    <h2>Sign In</h2>
    <?php if(!empty($err)) echo "<p style='color:red;'>$err</p>"; ?>
    <form method="post">
        <input name="email" type="email" required placeholder="Email">
        <input name="password" type="password" required placeholder="Password">
        <button class="btn" type="submit">Sign In</button>
    </form>
    <div>Don't have an account? <a href="signup.php">Sign up</a></div>
</div>
<?php include('footer.php'); ?>
