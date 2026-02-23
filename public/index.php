<?php
require_once __DIR__ . '/../app/bootstrap.php';

# If the user is already logged in, we can redirect them to the dashboard.
if (current_user()) {
    header('Location: /dashboard.php');
    exit;
}
# If not logged in, we redirect to the login page.
header('Location: /login.php');
exit;