<?php
require_once __DIR__ . '/includes/helpers.php';
session_unset();
session_destroy();
header('Location: login.php');
exit;
