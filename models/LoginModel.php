<?php
session_start();

class LoginModel extends BaseModel {

	public function user_exists($email, $password) {
		$password = sha1(sha1($password));
		$query = $this->mysql->query('SELECT COUNT(*) FROM account WHERE email="'.$email.'" AND password="'.$password.'"');
		$result = $query->fetch_row();

		return (int)$result[0] === 0 ? false : true;
	}

	public function get_current_user($email, $password) {
		$query = $this->mysql->query('SELECT id, name, surname, email, phone, role, inscription_date FROM account WHERE email="'.$email.'" AND password="'.$password.'"');
		$user = $query->fetch_assoc();
		return $user;
	}

	// permet de creer la session. un peu ma connexion
	public function set_session($user) {
		$_SESSION['user'] = $user;
	}

	// permet de l'utiliser la session sans remettre le start
	public function get_logged_user() {
		if(isset($_SESSION['user'])) {
			return $_SESSION['user'];
		}
		return false;
	}

	public function delete_session() {
		unset($_SESSION['user']);
		if(isset($_SESSION['user'])) {
			return false;
		}
		else {
			return true;
		}
	}
}