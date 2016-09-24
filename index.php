<?php
require_once 'Autoloader.php';
$autoloader = new Autoloader(null, 'classes');
$autoloader->register();

$e = new EvernoteCLI();

echo '<pre>';
print_r($e->ENScript('password', 'username')->listNotebooks());
echo '</pre>';