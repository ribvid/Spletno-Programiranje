<?php
class FavoritedSharedPosts extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('Microblog_model');
		$this->load->helper('url');
		$this->load->helper('url_helper');
		$this->load->library('ion_auth');
		$this->lang->load('english_lang', 'english');
	}

	public function view($page, $userId) {
		if ( !($page == 'favorited-posts') && !($page == 'shared-posts') ) {
			show_404();
		} else if (!$this->ion_auth->logged_in()) {
			redirect('');
		}

		if ($this->session->userdata('language') == 'sl') $this->lang->load('slovenian_lang', 'slovenian');

		$title = ($page == "favorited-posts") ? $this->lang->line('favorited_posts_title') : $this->lang->line('shared_posts_title');

		// Lokaliziran del vmesnika
		$data["title"] 					= $title;
		$data["language"]				= $this->session->userdata('language');
		$data["timeline"]				= $this->lang->line('timeline');
		$data["view_my_profile"]		= $this->lang->line('view_my_profile');
		$data["view_favorited_posts"]	= $this->lang->line('view_favorited_posts');
		$data["view_shared_posts"]		= $this->lang->line('view_shared_posts');
		$data["logout"]					= $this->lang->line('logout');
		$data["user_guide"]				= $this->lang->line('user_guide');
		$data["course"]					= $this->lang->line('course');
		$data["faculty"]				= $this->lang->line('faculty');

		$data["loggedIn"] = $this->ion_auth->logged_in();
		$data["userId"] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row()->id : -1;
		$data["user"] = ($this->ion_auth->logged_in()) ? $this->Microblog_model->get_user($data["userId"]) : FALSE;

		$data["posts"] = ($page == "favorited-posts") ? $this->Microblog_model->get_favorited_posts($userId) : $this->Microblog_model->get_shared_posts($userId);
		$data["favoritedPosts"] = $this->Microblog_model->get_favorited_posts($userId);
		$data["sharedPosts"] = $this->Microblog_model->get_shared_posts($userId);
		$data["users"] = $this->Microblog_model->get_user();
		$data["author"] = ($this->ion_auth->logged_in()) ? $this->Microblog_model->get_user($data["userId"]) : FALSE;

		$this->load->view('templates/header', $data);
		$this->load->view('timeline/favoritedSharedPosts', $data);
		$this->load->view('templates/posts', $data);
		$this->load->view('templates/footer');
	}

}