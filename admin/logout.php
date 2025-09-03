<?php
require_once __DIR__ . '/../php/autenticacion.php';
logout_user();
header('Location: ../login.html');
exit;