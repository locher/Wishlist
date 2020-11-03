<?php

if(isset($_POST['dbname']) && $_POST['dbname'] != '' && isset($_POST['dbuser']) && $_POST['dbuser'] != '' && isset($_POST['dbpassword']) && $_POST['dbpassword'] != '' && isset($_POST['dbhost']) && $_POST['dbhost'] != '' && isset($_POST['dbprefix'])){

	$dbname = filter_var($_POST['dbname'], FILTER_SANITIZE_STRING);

	$dbuser = filter_var($_POST['dbuser'], FILTER_SANITIZE_STRING);

	$dbpassword = filter_var($_POST['dbpassword'], FILTER_SANITIZE_STRING);

	$dbhost = filter_var($_POST['dbhost'], FILTER_SANITIZE_STRING);

	$dbprefix = filter_var($_POST['dbprefix'], FILTER_SANITIZE_STRING);


	$connect_code='<?php $config = array(
	    "db" => array(
		  "dbname" => "'.$dbname.'",
		  "username" => "'.$dbuser.'",
		  "password" => "'.$dbpassword.'",
		  "host" => "'.$dbhost.'"
	    ),
		
		"db_tables" => array(
			"db_users" => "'.$dbprefix.'peoples",
			"db_gifts" => "'.$dbprefix.'gifts",
			"db_parents" => "'.$dbprefix.'parents"
			
		)
	);';

	//Connexion to the new BDD

	try{
	    $bdd = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.'', ''.$dbuser.'', ''.$dbpassword.'');

	    	//If connection is ok, write the details in db.php

		if(is_writable('../inc/conf/db.php')){
			$fp = fopen('../inc/conf/db.php', 'w+');
			fwrite($fp,$connect_code);
			fclose($fp);
		}
	}

	//Else, return to the form
	catch (Exception $e){
	    header("location:install-form.php?error=db");
	}

	$bdd->query('SET NAMES "utf8"');


	$create_structure = 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
	SET time_zone = "+00:00";

	CREATE TABLE `'.$dbprefix.'gifts` (
	  `ID` int(11) NOT NULL,
	  `userID` int(11) NOT NULL,
	  `title` varchar(300) NOT NULL,
	  `link` varchar(2000) NOT NULL,
	  `description` text NOT NULL,
	  `isReserved` tinyint(1) NOT NULL,
	  `reservationUserID` int(11) NOT NULL,
	  `reservationGuestName` text NOT NULL,
	  `isList` tinyint(1) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;

	CREATE TABLE `'.$dbprefix.'parents` (
	  `ID_parent` int(11) NOT NULL,
	  `ID_child` int(11) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;


	CREATE TABLE `'.$dbprefix.'peoples` (
	  `userID` int(11) NOT NULL,
	  `name` varchar(30) NOT NULL,
	  `picture` int(11) NOT NULL,
	  `isChildAccount` tinyint(1) NOT NULL DEFAULT "0",
	  `birthday_date` date DEFAULT NULL,
	  `size_top` text,
	  `size_bottom` text,
	  `size_feet` text
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;


	ALTER TABLE `'.$dbprefix.'gifts`
	  ADD PRIMARY KEY (`ID`);

	ALTER TABLE `'.$dbprefix.'peoples`
	  ADD PRIMARY KEY (`userID`);

	ALTER TABLE `'.$dbprefix.'gifts`
	  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

	ALTER TABLE `'.$dbprefix.'peoples`
	  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT;
	';

	$saveBDD = $bdd->prepare($create_structure);

	if($saveBDD->execute()){
		//Delete install folder
		system("rm -rf ".escapeshellarg('../install'));
		//Back to index
		header("location:../");
	}else{
		header("location:install-form.php?error=bdd");
	}

}else{
	header("location:install-form.php?error=form");
}



