<?php
// Expects: $title, $bodyClass, $current, optional $tagline
require_once __DIR__ . '/functions.php';
$tagline = $tagline ?? 'Where farmers meet quality services.';
$nav = ['index.php' => 'Home', 'about.php' => 'About', 'alerts.php' => 'Alerts',
        'prices.php' => 'Prices', 'ussd.php' => 'USSD', 'contact.php' => 'Contact'];
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($title) ?> - ShambaPulse</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600&amp;family=Source+Serif+4:wght@600&amp;display=swap">
<link rel="stylesheet" href="css/style.css">
<link rel="icon" type="image/jpeg" href="img/bcd.jpg">
</head>
<body class="<?= e($bodyClass) ?>">

<header>
    <h1>
        <img src="img/logo.jpg" alt="" class="logo">
        ShambaPulse
    </h1>
    <p><?= e($tagline) ?></p>
</header>

<nav>
    <ul>
<?php foreach ($nav as $href => $label): ?>
        <li><a href="<?= $href ?>"<?= $href === $current ? ' aria-current="page"' : '' ?>><?= $label ?></a></li>
<?php endforeach; ?>
    </ul>
</nav>

