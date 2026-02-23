<?php

require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/db.php';

$errors = [];
# for form repopulation
$old = ['email' => '', 'name' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $name  = trim($_POST['name'] ?? '');
    $pass  = $_POST['password'] ?? '';

    $old['email'] = $email;
    $old['name']  = $name;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Email non valida.';
    if (strlen($pass) < 8) $errors['password'] = 'Password minimo 8 caratteri.';

    if (!$errors) {
        $pdo = db();

        # check unique email
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        # if fetch returns a row, email is already registered
        if ($stmt->fetch()) {
            $errors['email'] = 'Email già registrata.';
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users (email, name, password_hash) VALUES (:email, :name, :hash)");
            $stmt->execute(['email' => $email, 'name' => $name, 'hash' => $hash]);

            # redirect to login
            $_SESSION['flash_success'] = 'Registrazione completata. Ora fai login.';
            header('Location: /login.php');
            exit;
        }
    }
}


$success = flash('flash_success');
$error   = flash('flash_error');
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Register</title>
<link rel="stylesheet" href="/assets/css/register.css">
</head>
<body>
  <?php if ($success): ?><p class="flash flash--success"><?= e($success) ?></p><?php endif; ?>
  <?php if ($error): ?><p class="flash flash--error"><?= e($error) ?></p><?php endif; ?>

  <h1>Registrazione</h1>

  <form method="post" action="/register.php" novalidate>
    <label>Email</label>
    <input name="email" value="<?= e($old['email']) ?>">
    <?php if (isset($errors['email'])): ?><small><?= e($errors['email']) ?></small><?php endif; ?>

    <label>Nome</label>
    <input name="name" value="<?= e($old['name']) ?>">

    <label>Password</label>
    <input type="password" name="password">
    <?php if (isset($errors['password'])): ?><small><?= e($errors['password']) ?></small><?php endif; ?>

    <button type="submit">Crea account</button>
  </form>
  <p>Hai già un account? <a href="/login.php">Accedi</a></p>
</body>
</html>
