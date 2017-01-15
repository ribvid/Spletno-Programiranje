<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Search extends REST_Controller {
	
	public function __construct() {
		parent::__construct();

		$this->load->model('Microblog_model');
		$this->load->helper('url');
	}

	public function index_get() {
		$users = $this->Microblog_model->get_user();
		$tags = array_unique(array_map(function ($i) { return $i['name']; }, $this->Microblog_model->get_tag()));

		$response = array();

		foreach ($users as $user) {
			array_push($response, ['name' => $user["username"], 'link' => base_url().'profile/'.$user["id"]]);
		}

		foreach ($tags as $tag) {
			array_push($response, ['name' => $tag, 'link' => base_url().'tag/'.trim($tag, "#")]);
		}

		if ($users) {
			$this->response($response, 200);
		} else {
			$this->response(NULL, 404);
		}
	}

}