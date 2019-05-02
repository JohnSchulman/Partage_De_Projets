<?php

class ProjectsModel extends BaseModel {
	public function get_all() {
		$request = $this->mysql->query('SELECT * FROM `project`');
		// projetcs egale a un tableau vide
		$projects = [];
		// recupère les résultat de la requête dans data
		// Puis on fait un matrice cad un tableau de tableau.
		while ($data = $request->fetch_assoc()) {
			$projects[] = [
				// je recupère chaque du clé de data et je les met dans un tabelau associative qui
				// correspondaera à une ligne de projects
				'id' => $data['id'],
				'path' => $data['path'],
				'name' => $data['name'],
				'description' => $data['description'],
				'downloadable' => $data['downloadable'],
				'create_date' => $data['create_date'],
			];
		}
		return $projects;
	}

	public function add($name, $description, $path, $author, $downloadable) {
		// je remplace le double backslash par le slash le projet
		$path = str_replace('\\', '/', $path);
		return $this->mysql->query('INSERT INTO `project` (`name`, `description`, `path`, `author`, `downloadable`) 
											VALUES ("'.$name.'", "'.$description.'", "'.$path.'", "'.$author.'", '.$downloadable.')');
	}

	private function del_dir_recursive($dirname) {
		// j'ouvre le repertoire
		$dir = opendir($dirname);
		// je boucle sur le contenu du repertoire
		while (($elem = readdir($dir)) !== false) {
			// pour eviter les repertoire . et ..
			if($elem !== '.' && $elem !== '..') {
				//
				if (is_dir($dirname.'/'.$elem)) {
					// supprime le contenue du repertoire
					$this->del_dir_recursive($dirname.'/'.$elem);
				} elseif (is_file($dirname.'/'.$elem)) {
					unlink($dirname.'/'.$elem);
				}
			}
		}
		rmdir($dirname);
	}

	public function erase($projectId) {
		$req = $this->mysql->query('SELECT `path`, `name` FROM `project` 
										   WHERE `id` = '.$projectId);
		// variable externe pour pouvoir la recuperer à l'exterieure de la boucle
		// sinon le $path ne corresponde à null
		// list va avec fetch array
		$_path = null;
		$_project_name = null;
		while (list($path, $project_name) = $req->fetch_array()) {
			$_path = $path;
			$_project_name = $project_name;
			break;
		}
		//while ($data = $req->fetch_assoc()) {
		//	$_path = $data['path'];
		//	break;
		//}
		if($_path)
		{
			// une fonction qui permet de supprimer de la BDD
			if(is_file($_path)) {
				unlink($_path);
			}
			if(is_dir(__ROOT__.'/uploads/projects/extracted/'.$_project_name)) {
				$this->del_dir_recursive(__ROOT__.'/uploads/projects/extracted/'.$_project_name);
			}
			if(!file_exists($_path) && !is_dir(__ROOT__.'/uploads/projects/extracted/'.$_project_name)) {
				return $this->mysql->query('DELETE FROM `project` WHERE `id` = '.$projectId);
			}
			// return false si il n'as pas reussie à supprimer le zip
			// condition physique
			return false;
		}
		// return false s'il n'as pas trouver dans la BDD
		return false;
	}

	public function extract ($zip_name, $extracted_name) {
		$zip = new ZipArchive();
		$opened = $zip->open(__ROOT__.'/uploads/projects/'.$zip_name.'.zip');
		if ($opened) {
			if(!is_dir(__ROOT__.'/uploads/projects/extracted')) {
				mkdir(__ROOT__.'/uploads/projects/extracted', 0777, true);
			}
			if($zip->extractTo(__ROOT__.'/uploads/projects/extracted/'.$extracted_name)) {
				$status = true;
				if(!$zip->close()) {
					$status = false;
				}
			}
			else $status = false;
			return $status;
		}
		return false;
	}

	public function install($project_name)
	{
		$this->execute_file_script($project_name, 'install');
	}

	public function uninstall($project_id)
	{
		// requette qui permet de récupérer le nom du projet en fonction de son id
		$req = $this->mysql->query('SELECT `name` FROM project WHERE id='.$project_id);
		$project_name = null;
		// je remplis le tableau avec les noms
		while (list($name) = $req->fetch_array()) {
			$project_name = $name;
			break;
		}
		// si le projet existe
		if($project_name) {
			// je lance la desinstallation et je renvoie true
			$this->execute_file_script($project_name, 'uninstall');
			return true;
		}
		// si non je renvoie false
		return false;
	}

	private function execute_file_script($project_name, $file_type) {
		// on recupère le contenue du fichier install.txt de mon projet courant
		$file = file_get_contents(__ROOT__.'/uploads/projects/extracted/'.$project_name.'/'.$file_type.'.txt');
		// on trnsforme chaque ligne en ligne d'un tableau
		$file_array = explode("\n", $file);
		foreach ($file_array as $key => $line) {
			if($line === '') {
				unset($file_array[$key]);
			}
		}

		$commands = [];
		$last_key = null;
		foreach ($file_array as $line) {
			if($line !== "\0" && $line !== "\t" && $line !== "\r" && $line !== "\n") {
				// Si je tombe sur une ligne qui contiens la chaine de caractères **php**
				if (strstr($line, '**php**')) {
					// enregistre dans une variable pour pouvoir réutiliser la même clé dans les autres lignes de la boucle
					$last_key = 'php';
					// Si la clée n'existe pas je la crée et je l'initialise avec un tableau vide
					// Si le tableau existe on ne le crée pas.
					if (!isset($commands[$last_key])) {
						$commands[$last_key] = [];
					}
					continue;
				} elseif (strstr($line, '**shell_lin**')) {
					$last_key = 'shell_lin';
					if (!isset($commands[$last_key])) {
						$commands[$last_key] = [];
					}
					continue;
				} elseif (strstr($line, '**shell_win**')) {
					$last_key = 'shell_win';
					if (!isset($commands[$last_key])) {
						$commands[$last_key] = [];
					}
					continue;
				}
				// un test pour un ligne de commande
				if (!is_null($last_key)) {
					$commands[$last_key][] = $line;
				}
			}
		}

		// si j'ai des instructions php à executer
		if(isset($commands['php'])) {
			// Je rassemble le tableau en une string
			$php_commands = implode("\n", $commands['php']);
			// puis j'execute le code en un bloque.
			eval($php_commands);
		}

		// Sert à détecter si on est sous windows ou linux

		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$key = 'shell_win';
		} else {
			$key = 'shell_lin';
		}

		foreach ($commands[$key] as $command) {
			exec($command);
		}
	}
}