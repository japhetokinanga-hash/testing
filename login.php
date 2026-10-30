<?php
require_once __DIR__ . '/../includes/functions.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) { $error = 'Session expired. Try again.'; }
    else {
        $st = db()->prepare('SELECT id, password_hash FROM admin_users WHERE username = ?');
        $st->execute([trim($_POST['username'] ?? '')]);
        $u = $st->fetch();
        if ($u && password_verify($_POST['password'] ?? '', $u['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = (int)$u['id'];
            header('Location: index.php'); exit;
        }
        $error = 'Wrong username or password.';
    }
}
?><!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Officer login - ShambaPulse</title>
<link rel="stylesheet" href="../css/style.css"></head>
<body class="inner">
<header><h1>ShambaPulse</h1><p>Officer login</p></header>
<main class="admin-wrap"><div class="panel">
<?php if ($error): ?><p class="notice error"><?= e($error) ?></p><?php endif; ?>
<form method="post">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <label for="u">Username</label><input id="u" name="username" required autofocus>
  <label for="p">Password</label><input id="p" name="password" type="password" required>
  <button class="hero-button" type="submit">Log in</button>
</form></div></main></body></html>
