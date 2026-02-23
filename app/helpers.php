<?php

#escapes a string for safe output in HTML context
function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}


function flash(string $key): ?string {
    if (!isset($_SESSION[$key])) return null;
    #Save the message in a temporary variable
    $msg = $_SESSION[$key];
    #Remove the messagge, it will be shown only once
    unset($_SESSION[$key]);
    return $msg;
}
