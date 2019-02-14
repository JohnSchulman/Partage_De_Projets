<?php

class SignupModel extends BaseModel {
	public function add_user($name, $surname, $email, $password, $phone, $role) {
		 return $this->mysql->query('INSERT INTO `account` (`name`, `surname`, `email`, `password`, `phone`, `role`)
										VALUES("'.$name.'", "'.$surname.'", "'.$email.'", "'.$password.'", "'.$phone.'", "'.$role.'")');
	}

	public function add_phone($phone, $account) {
		return $this->mysql->query('INSERT INTO `phone` (`phone`, `account`) VALUES("'.$phone.'", "'.$account.'")');
	}

	public function add_email($email, $account) {
		return $this->mysql->query('INSERT INTO `mail` (`mail`, `account`) VALUES("'.$email.'", "'.$account.'")');
	}

	public function delete_account($id) {

	}

	public function update_account($id, $name, $surname, $email, $password, $phone, $role) {

	}

}