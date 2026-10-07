<?php
require_once __DIR__ . '/config/auth.php';
requireLogin();
session_destroy();
redirect(BASE_URL . '/index.php');
