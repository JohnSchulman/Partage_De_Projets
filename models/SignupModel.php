<?php

class SignupModel extends BaseModel {
	public function add_user($name, $surname, $email, $password, $phone, $role) {
		/*$this->mysql->query('INSERT INTO `account` (`name`, `surname`, `email`, `password`, `phone`, `role`)
										VALUES("'.$name.'", "'.$surname.'", "'.$email.'", "'.$password.'", "'.$phone.'", "'.$role.'")');*/
		echo 'INSERT INTO `account` (`name`, `surname`, `email`, `password`, `phone`, `role`) 
										VALUES("'.$name.'", "'.$surname.'", "'.$email.'", "'.$password.'", "'.$phone.'", '.$role.')';
	}

	public function add_phone($phone) {

	}

	public function add_email($email) {

	}

	public function delete_account($id) {

	}

	public function update_account($id, $name, $surname, $email, $password, $phone, $role) {

	}
}