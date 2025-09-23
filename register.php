<?php
require_once __DIR__ . '/functions.php';

$errors = [];
$old = ['username'=>'', 'email'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf()) {
        $errors[] = "Invalid CSRF token.";
    }

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    $old['username'] = $username;
    $old['email'] = $email;

    // Server-side validation
    if (strlen($username) < 3) $errors[] = "Username must be at least 3 characters.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
    if ($password !== $password_confirm) $errors[] = "Passwords do not match.";

    if (empty($errors)) {
        // Check duplicates
        $stmt = $pdo->prepare("SELECT id, username, email FROM users WHERE username = :u OR email = :e LIMIT 1");
        $stmt->execute([':u' => $username, ':e' => $email]);
        $row = $stmt->fetch();
        if ($row) {
            if ($row['username'] === $username) $errors[] = "Username already taken.";
            if ($row['email'] === $email) $errors[] = "Email already registered.";
        } else {
            // Insert user
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:u, :e, :p)");
            $ins->execute([':u' => $username, ':e' => $email, ':p' => $hash]);

            flash('success', 'Registration successful. Please log in.');
            header('Location: login.php');
            exit;
        }
    }
}

$token = csrf_token();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Register — MyApp</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg">
  <main class="center">
    <div class="card animate-up">
      <h2>Create account</h2>

      <?php if (!empty($errors)): ?>
        <div class="msg error shake"><ul><?php foreach ($errors as $err) echo '<li>'.e($err).'</li>'; ?></ul></div>
      <?php endif; ?>

      <form id="registerForm" method="post" novalidate>
        <input type="hidden" name="csrf_token" value="<?= e($token) ?>">
        <div class="field">
          <label>Username</label>
          <input name="username" required minlength="3" value="<?= e($old['username']) ?>" />
        </div>

        <div class="field">
          <label>Email</label>
          <input name="email" type="email" required value="<?= e($old['email']) ?>" />
        </div>

        <div class="field">
          <label>Password</label>
          <div class="pw">
            <input name="password" type="password" required minlength="6" id="password"/>
            <button type="button" class="show-pw" data-target="password">Show</button>
          </div>
        </div>

        <div class="field">
          <label>Confirm Password</label>
          <div class="pw">
            <input name="password_confirm" type="password" required minlength="6" id="password_confirm"/>
            <button type="button" class="show-pw" data-target="password_confirm">Show</button>
          </div>
        </div>

        <button class="btn" type="submit">Register</button>
        <p class="center small">Already have an account? <a href="index.php">Log in</a></p>
      </form>
    </div>
  </main>

  <script src="assets/js/main.js"></script>
</body>
</html>
