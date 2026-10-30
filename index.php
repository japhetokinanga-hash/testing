<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) { flash('Session expired. Try again.', 'error'); header('Location: index.php'); exit; }
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'new_alert') {
            $en = trim($_POST['body_en'] ?? ''); $sw = trim($_POST['body_sw'] ?? '');
            $type = $_POST['type'] ?? '';
            if (!in_array($type, ['weather','advisory','market'], true)) throw new RuntimeException('Pick a valid alert type.');
            if ($en === '' || $sw === '') throw new RuntimeException('Write the alert in both English and Kiswahili.');
            if (mb_strlen($en) > 160 || mb_strlen($sw) > 160) throw new RuntimeException('Each message must fit one SMS (160 characters).');
            $pdo->prepare('INSERT INTO alerts (county_id,type,body_en,body_sw,status,created_by) VALUES (?,?,?,?,?,?)')
                ->execute([(int)$_POST['county_id'], $type, $en, $sw,
                           isset($_POST['send_now']) ? 'queued' : 'draft', $_SESSION['admin_id']]);
            flash(isset($_POST['send_now']) ? 'Alert queued. The gateway will send it within seconds.' : 'Draft saved.');
        } elseif ($action === 'queue_alert') {
            $pdo->prepare("UPDATE alerts SET status='queued' WHERE id=? AND status='draft'")->execute([(int)$_POST['id']]);
            flash('Alert queued for sending.');
        } elseif ($action === 'new_price') {
            $price = (float)$_POST['price_kes'];
            if ($price <= 0) throw new RuntimeException('Price must be above zero.');
            $pdo->prepare('INSERT INTO prices (commodity,unit,market,price_date,price_kes,source) VALUES (?,?,?,?,?,?)
                           ON DUPLICATE KEY UPDATE price_kes=VALUES(price_kes), source=VALUES(source)')
                ->execute([trim($_POST['commodity']), trim($_POST['unit']), trim($_POST['market']),
                           $_POST['price_date'], $price, 'officer entry']);
            flash('Price saved.');
        }
    } catch (Throwable $ex) {
        flash($ex instanceof RuntimeException ? $ex->getMessage() : 'Could not save. Check your input.', 'error');
    }
    header('Location: index.php'); exit;
}

$flash = flash();
$counties = $pdo->query('SELECT * FROM counties ORDER BY name')->fetchAll();
$alerts = $pdo->query("SELECT a.*, c.name AS county,
    (SELECT COUNT(*) FROM alert_deliveries d WHERE d.alert_id=a.id) AS total,
    (SELECT COUNT(*) FROM alert_deliveries d WHERE d.alert_id=a.id AND d.status='delivered') AS delivered,
    (SELECT COUNT(*) FROM alert_deliveries d WHERE d.alert_id=a.id AND d.status='failed') AS failed
    FROM alerts a JOIN counties c ON c.id=a.county_id ORDER BY a.id DESC LIMIT 20")->fetchAll();
$subs = $pdo->query("SELECT c.name, COUNT(f.id) n FROM counties c
    LEFT JOIN wards w ON w.county_id=c.id LEFT JOIN farmers f ON f.ward_id=w.id AND f.subscribed=1
    GROUP BY c.id ORDER BY c.name")->fetchAll();
$msgs = $pdo->query('SELECT * FROM contact_messages ORDER BY id DESC LIMIT 20')->fetchAll();
?><!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Officer dashboard - ShambaPulse</title>
<link rel="stylesheet" href="../css/style.css"></head>
<body class="inner">
<header><h1>ShambaPulse</h1><p>Officer dashboard &middot; <a href="logout.php" style="color:#e2a72e">Log out</a></p></header>
<main class="admin-wrap">
<?php if ($flash): ?><p class="notice <?= $flash[1] === 'error' ? 'error' : '' ?>"><?= e($flash[0]) ?></p><?php endif; ?>

<div class="panel"><h2>Subscribers by county</h2>
<table><tr><th>County</th><th>Subscribed farmers</th></tr>
<?php foreach ($subs as $s): ?><tr><td><?= e($s['name']) ?></td><td><?= (int)$s['n'] ?></td></tr><?php endforeach; ?>
</table></div>

<div class="panel"><h2>New alert</h2>
<form method="post">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="new_alert">
  <label>County <select name="county_id"><?php foreach ($counties as $c): ?><option value="<?= $c['id'] ?>"><?= e($c['name']) ?></option><?php endforeach; ?></select></label>
  <label>Type <select name="type"><option value="weather">Weather</option><option value="advisory">Advisory</option><option value="market">Market</option></select></label>
  <label>English (max 160) <textarea name="body_en" maxlength="160" rows="2" required></textarea></label>
  <label>Kiswahili (max 160) <textarea name="body_sw" maxlength="160" rows="2" required></textarea></label>
  <label><input type="checkbox" name="send_now" value="1" style="width:auto"> Send now (otherwise save as draft)</label>
  <button class="hero-button" type="submit">Save alert</button>
</form></div>

<div class="panel"><h2>Alerts</h2>
<table><tr><th>#</th><th>County</th><th>Type</th><th>Status</th><th>Delivered / Failed / Total</th><th>Message</th><th></th></tr>
<?php foreach ($alerts as $a): ?>
<tr><td><?= $a['id'] ?></td><td><?= e($a['county']) ?></td><td><?= e($a['type']) ?></td><td><?= e($a['status']) ?></td>
<td><?= (int)$a['delivered'] ?> / <?= (int)$a['failed'] ?> / <?= (int)$a['total'] ?></td><td><?= e($a['body_en']) ?></td>
<td><?php if ($a['status'] === 'draft'): ?><form method="post" style="margin:0"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="queue_alert"><input type="hidden" name="id" value="<?= $a['id'] ?>"><button class="hero-button" style="padding:6px 12px;margin:0">Queue</button></form><?php endif; ?></td></tr>
<?php endforeach; ?></table></div>

<div class="panel"><h2>Add / update a price</h2>
<form method="post">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="new_price">
  <input name="commodity" placeholder="Commodity (e.g. Maize)" required>
  <input name="unit" placeholder="Unit (e.g. 90 kg bag)" required>
  <input name="market" placeholder="Market" required>
  <input name="price_date" type="date" value="<?= date('Y-m-d') ?>" required>
  <input name="price_kes" type="number" step="0.01" min="0.01" placeholder="Price (KES)" required>
  <button class="hero-button" type="submit">Save price</button>
</form></div>

<div class="panel"><h2>Contact messages</h2>
<table><tr><th>When</th><th>Name</th><th>Email</th><th>Role</th><th>Message</th></tr>
<?php foreach ($msgs as $m): ?>
<tr><td><?= e($m['created_at']) ?></td><td><?= e($m['full_name']) ?></td><td><?= e($m['email']) ?></td><td><?= e($m['role']) ?></td><td><?= nl2br(e($m['message'])) ?></td></tr>
<?php endforeach; ?></table></div>
</main></body></html>
