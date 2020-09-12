<?php 

include_once('conf/twig_configuration.php');
include_once('conf/config.php');
include_once('library/functions.php');

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