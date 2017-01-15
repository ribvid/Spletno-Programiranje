<?php
class Tag extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('Microblog_model');
		$this->load->helper('url');
		$this->load->helper('url_helper');
		$this->load->library('ion_auth');
		$this->lang->load('english_lang', 'english');
	}

	public function view($tagName) {

		if ($this->session->userdata('language') == 'sl') $this->lang->load('slovenian_lang', 'slovenian');

		// Lokaliziran del vmesnika
		$data["language"]				= $this->session->userdata('language');
		$data["title"] 					= $this->lang->line('tag_title');
		$data["timeline"]				= $this->lang->line('timeline');
		$data["view_my_profile"]		= $this->lang->line('view_my_profile');
		$data["view_favorited_posts"]	= $this->lang->line('view_favorited_posts');
		$data["view_shared_posts"]		= $this->lang->line('view_shared_posts');
		$data["logout"]					= $this->lang->line('logout');
		$data["user_guide"]				= $this->lang->line('user_guide');
		$data["course"]					= $this->lang->line('course');
		$data["faculty"]				= $this->lang->line('faculty');

		$tagName = "#".$tagName;

		$data["loggedIn"] = $this->ion_auth->logged_in();
		$data["userId"] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row()->id : -1;
		$data["user"] = $this->Microblog_model->get_user($data["userId"]);

		$data["tag"] = $tagName;
		$data["posts"] = $this->Microblog_model->get_tag($tagName);

		$data["favoritedPosts"] = $this->Microblog_model->get_favorited_posts($data["userId"]);
		$data["sharedPosts"] = $this->Microblog_model->get_all_shared_posts($data["userId"]);


		$data["users"] = $this->Microblog_model->get_user();
		$data["author"] = $data["user"];
		
		$this->load->view('templates/header', $data);
		$this->load->view('tag', $data);
		$this->load->view('templates/posts.php', $data);
		$this->load->view('templates/footer');
	}

}