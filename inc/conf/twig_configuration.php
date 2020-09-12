<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

$loader = new \Twig\Loader\FilesystemLoader('views');
$twig = new \Twig\Environment($loader, [
	'debug' => true
]);

$twig->addExtension(new \Twig\Extension\DebugExtension());

$context = array();
