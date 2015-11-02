<?php

	try
	{
	    $bdd = new PDO('mysql:host=localhost;dbname=noel', 'root', 'root');
	}

	catch (Exception $e)
	
	{
	    die(print $e->getMessage());
	}

	$bdd->query('SET NAMES "utf8"');
	
?>