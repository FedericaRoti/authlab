<?php
require_once __DIR__ . '/../app/bootstrap.php';

logout_user();
$_SESSION['flash_success'] = 'Logout effettuato.';
header('Location: /login.php');
exit;