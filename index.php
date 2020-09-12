<?php 

include_once('inc/header.php');

$context['users'] = getUsers('parents');

// Popup after account creation

if(isset($_GET['src']) && isset($_GET['user']) && $_GET['user'] != ''){

	if($_GET['src'] == 'CreateAccountOk'){

		$context['pagetype'] = array(
			'type' => 'creation',
			'user' => $_GET['user']
		);

	}else if($_GET['src'] == 'DeleteAccountOk'){

		$context['pagetype'] = array(
			'type' => 'delete',
			'user' => $_GET['user']
		);
	}
}

echo $twig->render('templates/frontpage.twig', $context);