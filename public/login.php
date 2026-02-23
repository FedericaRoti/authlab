<?php
require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/db.php';

$errors = [];
$old = ['email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    $old['email'] = $email;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email non valida.';
    }
    if ($pass === '') {
        $errors['password'] = 'Inserisci la password.';
    }

    if (!$errors) {
        $pdo = db();

        $stmt = $pdo->prepare("SELECT id, email, name, password_hash FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        # Verify password and log in the user 
        if (!$user) {
            $errors['general'] = 'Credenziali non valide.';
        } elseif (!password_verify($pass, $user['password_hash'])) {
            $errors['general'] = 'Credenziali non valide.';
        } else {
            #Regenerate session ID and log in the user
            login_user($user);
            header('Location: /dashboard.php');
            exit;
        }
    }
}

$success = flash('flash_success');
$error   = flash('flash_error');
?>
<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link rel="stylesheet" href="/assets/css/login.css">
</head>
<body>
  <h1>Login</h1>

  <?php if ($success): ?><p class="flash flash--success"><?= e($success) ?></p><?php endif; ?>
  <?php if ($error): ?><p class="flash flash--error"><?= e($error) ?></p><?php endif; ?>
  <?php if (isset($errors['general'])): ?><p class="flash flash--error"><?= e($errors['general']) ?></p><?php endif; ?>

  <form method="post" action="/login.php" novalidate>
    <label>Email</label>
    <input name="email" value="<?= e($old['email']) ?>">
    <?php if (isset($errors['email'])): ?><small><?= e($errors['email']) ?></small><?php endif; ?>

    <label>Password</label>
    <input type="password" name="password">
    <?php if (isset($errors['password'])): ?><small><?= e($errors['password']) ?></small><?php endif; ?>

    <button type="submit">Entra</button>
  </form>

  <p>Non hai un account? <a href="/register.php">Registrati</a></p>
</body>
</html>