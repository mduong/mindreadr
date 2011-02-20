<?php

/*
 * User.php
 * Object-Relationship model class for User
 */

require_once('MindReadrDb.php');

class User {

	protected $id, $first_name, $last_name, $email, $phone, $salt, $hashed_password, $db;

	public function User() {
		$this->db = new MindReadrDb();
	}
	
	public function register() {}
}

?>