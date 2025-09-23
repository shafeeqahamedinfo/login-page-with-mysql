<?php
require_once __DIR__ . '/functions.php';
// Clear session and logout
session_unset();
session_destroy();
session_start();
flash('success', 'You have been logged out.');
header('Location: login.php');
exit;
