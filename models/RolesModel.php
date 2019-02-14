<?php

class RolesModel extends BaseModel {
	public function add($role) {
		return $this->mysql->query('INSERT INTO `role` (`name`) VALUES ("'.$role.'")');
	}

	public function delete($id) {
		return $this->mysql->query('DELETE FROM `role` WHERE id='.$id);
	}
}