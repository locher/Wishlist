<?php

class user {
	// Attributs
	public $ID;
	public $name;
	public $picture;
	public $birthday_date;
	public $size_top;
	public $size_bottom;
	public $size_feet;
	public $isChildAccount;

	// Méthodes  
	public function __construct($userID, $userName, $userPicture, $userBday, $userSizeTop, $userSizeBottom, $userSizeFeet, $userChildAccount) {
		$this->ID = $userID;
	    $this->name = $userName;
	    $this->picture = $userPicture;
	    $this->birthday_date = $userBday;
	    $this->size_top = $userSizeTop;
	    $this->size_bottom = $userSizeBottom;
	    $this->size_feet = $userSizeFeet;
	    $this->isChildAccount = $userChildAccount;
	    $this->age = age($this->birthday_date);
	    $this->nice_birthday_date = birthdayDate($this->birthday_date);
	}
}
