<?php
// View chat untuk designer — menggunakan komponen yang sama dengan user chat
// Variabel role akan ditangani otomatis oleh JavaScript di dalam view
$currentUserId = $_SESSION['user_id'] ?? 0;
$currentUserRole = $_SESSION['user_role'] ?? 'designer';
require APP_ROOT . '/views/dashboard/user/chat.php';