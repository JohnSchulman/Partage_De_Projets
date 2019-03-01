<?php

class SignupController extends Controller {
	/** @var SignupModel $model */
	private $model;

	public function __construct($action, $params) {
		parent::__construct($action, $params);
		$this->model = $this->get_model('signup');
	}

	public function index() {
		return $this->signup();
	}

	public function signup() {
		$result = $this->model->add_user($this->get('name'), $this->get('surname'),
										 $this->get('email'), sha1(sha1($this->get('password'))),
										 $this->get('phone'), $this->get('role'));
		$status = $result ? self::SUCCESS : self::ERROR;
		return [
			'status' => $status
		];
	}

	public function add_phone() {
		$result = $this->model->add_phone($this->get('phone'), (int)$this->get('account'));
		$status = $result ? self::SUCCESS : self::ERROR;
		return [
			'status' => $status
		];
	}

	public function add_email() {
		$result = $this->model->add_email($this->get('email'), (int)$this->get('account'));
		$status = $result ? self::SUCCESS : self::ERROR;
		return [
			'status' => $status
		];
	}
}
