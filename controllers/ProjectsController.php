<?php

class ProjectsController extends Controller {
	/** @var ProjectsModel $model */
	private $model;
	public function __construct($action, $params) {
		parent::__construct($action, $params);
		$this->model = $this->get_model('projects');
	}

	public function index() {
		return $this->get_projects();
	}

	public function get_projects() {
		$results = $this->model->get_all();
		return $results;
	}

	/**
	 * @throws Exception
	 */
	public function add_project() {
		// Je récupère les infos sur le fichier dans la variable $file
		$file = $this->files('project');
		if($file['error'] > 0) {
			throw new Exception('Une erreur est survenue lors de l\'upload !!');
		}
		$extensions_valides = [
			'zip'
		];
		// Je détermine quelle extension le fichier que je viens d'uploader possède.
		// toto.txt
		// [ toto, txt ]
		// test.toto.txt.html
        // la fonction explode transforme la chaine en tableau
		$explode = explode('.', $file['name']);
		// -1 car commence à zero
		$extension_upload = $explode[count($explode)-1];
		if (in_array($extension_upload, $extensions_valides)) {
			if(!is_dir(__ROOT__.'/uploads/projects/')) {
				//Créer un dossier 'uploads/projects/'
				mkdir(__ROOT__.'/uploads/projects/', 0777, true);
			}
			//Créer un identifiant difficile à deviner / encryptage
			$nom = md5($file['name']);
			// je met le fichier temporaire dans le repertoire
			$resultat = move_uploaded_file($file['tmp_name'], __ROOT__.'/uploads/projects/'.$nom.'.zip');
			if($resultat) {
				$result = $this->model->add(
					$this->post('name'),
					$this->post('description'),
					realpath(__ROOT__.'/uploads/projects/'.$nom.'.zip'),
					$this->post('author'),
					(bool)$this->post('downloadable')
				);
			}
			else {
				$result = false;
			}

			$status = $result ? self::SUCCESS : self::ERROR;
			if($status === self::SUCCESS) {
				// si j'ai réussie à extraire le fichier je retourne un statut true sinon un statut false
				if($this->model->extract($nom, $this->post('name'))) {
					// Si le dezippage à réussi, je lance l'installation avec le fichier install.txt
					$this->model->install($this->post('name'));
					return [
						'status' => true,
					];
				}
			}
			return [
				'status' => false,
				'message' => 'l\'upload à échoué',
			];
		}
		return [
			'status' => false,
			'message' => 'l\'extension n\'est pas supportée',
		];
	}

	public function upload() {
		echo file_get_contents(__ROOT__.'/file_uplodad.html');
		return [];
	}

	public function delete(){
		// Si le dezippage à réussi, je lance l'installation avec le fichier install.txt
		if($this->model->uninstall($this->get('id'))) {
			if ($this->model->erase($this->get("id"))) {
				$result = [
					"success" => true
				];
			} else {
				$result = [
					"success" => false
				];
			}
			return $result;
		}
		return $result = [
			"success" => false
		];
	}
}

