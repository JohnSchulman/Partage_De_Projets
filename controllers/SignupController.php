<?php

class SignupController extends Controller {
	const SUCCESS = 'success';
	const ERROR = 'error';
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
		$request = $this->model->add_user($this->get('name'), $this->get('surname'), $this->get('email'), $this->get('password'), $this->get('phone'), $this->get('role'));
		return [
			'status' => self::SUCCESS,
			'request' => $request
		];
	}
}
