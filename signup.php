<?php include('header.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pw = $_POST['password'] ?? '';
    $pw_hash = password_hash($pw, PASSWORD_DEFAULT);
    // check for user existence
    $users = file_exists('user.csv') ? array_map('str_getcsv', file('user.csv')) : [];
    foreach($users as $user) {
        if($user[1] == $email) $err = "Email already exists!";
    }
    if(empty($err) && $username && $email && $pw) {
        $uid = uniqid();
        $line = "$uid,$email,$pw_hash,$username\n";
        file_put_contents('user.csv', $line, FILE_APPEND);
        $_SESSION['username'] = $username;
        header('Location: index.php'); exit;
    }
}
?>
<div class="container">
    <h2>Sign Up</h2>
    <?php if(!empty($err)) echo "<p style='color:red;'>$err</p>"; ?>
    <form method="post">
        <input name="username" required placeholder="Username">
        <input name="email" required type="email" placeholder="Email">
        <input name="password" required type="password" placeholder="Password">
        <button class="btn" type="submit">Create Account</button>
    </form>
    <div>Already have an account? <a href="signin.php">Sign in</a></div>
</div>
<?php include('footer.php'); ?>
