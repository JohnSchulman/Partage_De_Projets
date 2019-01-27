<?php

class Conf {
	private $conf;
	public function __construct($conf) {
		$this->conf = require_once __DIR__.'/../conf/'.$conf.'.php';
	}

	public function get($key) {
		if(isset($this->conf[$key])) {
			return $this->conf[$key];
		}
		else {
			return null;
		}
	}
}