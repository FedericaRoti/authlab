<?php
require_once __DIR__ . '/../app/bootstrap.php';


# This page is protected, so we require authentication before showing it.
require_auth();
# Get the current user information to display on the dashboard.
$user = current_user();
?>


<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="/assets/css/dashboard.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@300;400;500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
</head>
</head>
<body>

  <div class="layout">
    <aside class="art-rail" aria-hidden="true"><span class="art-rail-label">AuthLab</span></aside>

    <main class="panel">
      <header class="panel__header">
        <div>
          <h1>Dashboard</h1>
          <p class="muted">Benvenuta, <?= e($user['name'] ?? $user['email']) ?>.</p>
        </div>
        <a class="btn" href="/logout.php">Logout</a>
      </header>

      <section class="card">
        <h2>Profilo</h2>
        <ul class="list">
          <li><span>User ID</span><strong><?= e((string)$user['id']) ?></strong></li>
          <li><span>Email</span><strong><?= e($user['email']) ?></strong></li>
        </ul>
      </section>

      <section class="card">
        <h2>Azioni</h2>
        <p class="muted">Qui poi potrai fare delle aggiunte....</p>
      </section>
    </main>
  </div>

</body>
</html>