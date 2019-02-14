<?php

class ProjectsModel extends BaseModel {
	public function get_all() {
		$request = $this->mysql->query('SELECT * FROM `project`');
		$projects = [];
		while ($data = $request->fetch_assoc()) {
			$projects[] = [
				'id' => $data['id'],
				'name' => $data['name'],
				'description' => $data['description'],
				'downloadable' => $data['downloadable'],
				'create_date' => $data['create_date'],
			];
		}
		return $projects;
	}

	public function add($name, $description, $path, $author, $downloadable) {
		$path = str_replace('\\', '/', $path);
		return $this->mysql->query('INSERT INTO `project` (`name`, `description`, `path`, `author`, `downloadable`) VALUES ("'.$name.'", "'.$description.'", "'.$path.'", "'.$author.'", '.$downloadable.')');
	}

	public function erase($projectId) {
		$req = $this->mysql->query('SELECT `path` FROM `project` WHERE `id` = '.$projectId);
		$_path = null;
		while (list($path) = $req->fetch_array()) {
			$_path = $path;
			break;
		}
		if($_path)
		{
			// une fonction qui permet de supprimer
			unlink($_path);
			if(!file_exists($_path)) {
				return $this->mysql->query('DELETE FROM `project` WHERE `id` = '.$projectId);
			}
			// return false si il n'as pas reussie à supprimer le zip
			// condition physique
			return false;
		}
		// return false s'il n'as pas trouver dans la BDD
		return false;
	}
}