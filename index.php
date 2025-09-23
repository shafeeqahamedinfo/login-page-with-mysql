<?php
require_once __DIR__ . '/functions.php';

$errors = [];
$old = ['email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf()) {
        $errors[] = "Invalid CSRF token.";
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $old['email'] = $email;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Enter a valid email.";
    if (empty($password)) $errors[] = "Enter your password.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = :e LIMIT 1");
        $stmt->execute([':e' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            flash('success', 'Welcome back, ' . $user['username'] . '!');
            header('Location: https://recyclezone.neocities.org/');
            exit;
        } else {
            $errors[] = "Email or password incorrect.";
        }
    }
}

$token = csrf_token();
$success_msg = flash('success');
$error_msg = flash('error');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login — MyApp</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg">
  <main class="center">
    <div class="card animate-up">
      <h2>Welcome back</h2>

      <?php if ($success_msg): ?>
        <div class="msg success bounce"><?= e($success_msg) ?></div>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <div class="msg error shake"><ul><?php foreach ($errors as $err) echo '<li>'.e($err).'</li>'; ?></ul></div>
      <?php endif; ?>

      <form id="loginForm" method="post" novalidate>
        <input type="hidden" name="csrf_token" value="<?= e($token) ?>">
        <div class="field">
          <label>Email</label>
          <input name="email" type="email" required value="<?= e($old['email']) ?>"/>
        </div>

        <div class="field">
          <label>Password</label>
          <div class="pw">
            <input name="password" type="password" required id="login_password"/>
            <button type="button" class="show-pw" data-target="login_password">Show</button>
          </div>
        </div>

        <button class="btn" type="submit">Log in</button>
        <p class="center small">Don't have an account? <a href="register.php">Register</a></p>
      </form>
    </div>
  </main>

  <script src="assets/js/main.js"></script>
</body>
</html>

