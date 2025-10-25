<?php
require_once __DIR__ . '/functions.php';
require_login();

$username = $_SESSION['username'] ?? 'User';
$success = flash('success');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Home — MyApp</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg">
  <header class="top">
    <div class="wrap">
      <h3>MyApp</h3>
      <nav>
        <span>Hello, <?= e($username) ?></span>
        <a class="btn small" href="logout.php">Logout</a>
      </nav>
    </div>
  </header>

  <main class="wrap content">
    <?php if ($success): ?>
      <div class="msg success"><?= e($success) ?></div>
    <?php endif; ?>

    <section class="card animate-up">
      <h2>Dashboard</h2>
      <p>This page is protected and visible only after login.</p>
    </section>
  </main>
</body>
</html>

