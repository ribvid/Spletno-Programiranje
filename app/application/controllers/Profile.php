<?php
class Profile extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->load->model('Microblog_model');
		$this->load->helper('url');
		$this->load->helper('url_helper');
		$this->load->library('ion_auth');
		$this->lang->load('english_lang', 'english');
	}

	public function view($authorId) {

		if ($this->session->userdata('language') == 'sl') $this->lang->load('slovenian_lang', 'slovenian');

		// Lokaliziran del vmesnika
		$data["language"]				= $this->session->userdata('language');
		$data["title"] 					= $this->lang->line('profile_title');
		$data["timeline"]				= $this->lang->line('timeline');
		$data["view_my_profile"]		= $this->lang->line('view_my_profile');
		$data["view_favorited_posts"]	= $this->lang->line('view_favorited_posts');
		$data["view_shared_posts"]		= $this->lang->line('view_shared_posts');
		$data["logout"]					= $this->lang->line('logout');
		$data["user_guide"]				= $this->lang->line('user_guide');
		$data["course"]					= $this->lang->line('course');
		$data["faculty"]				= $this->lang->line('faculty');

		// User je prijavljeni uporabnik, author pa avtor profila (lahko sta ista).

		$data["loggedIn"] = $this->ion_auth->logged_in();
		$data["userId"] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row()->id : -1;
		$data["user"] = $this->Microblog_model->get_user($data["userId"]);

		$data["isFollowing"] = False;
		foreach($this->Microblog_model->get_following_users($data["userId"]) as $followers) {
			if ($authorId == $followers["following_user_id"]) {
				$data["isFollowing"] = True;
				break;
			}
		}

		$data["favoritedPosts"] = $this->Microblog_model->get_favorited_posts($data["userId"]);
		$data["sharedPosts"] = $this->Microblog_model->get_all_shared_posts($data["userId"]);

		$posts = $this->Microblog_model->get_posts($authorId);
		$sharedPosts = $this->Microblog_model->get_shared_posts($authorId);
		$allPosts = array_merge($sharedPosts, $posts);
		usort($allPosts, function($a, $b) {
			return strtotime($b['pub_date']) - strtotime($a['pub_date']);
		});
		$data["posts"] = $allPosts;

		$data["author"] = $this->Microblog_model->get_user($authorId);
		$data["users"] = $this->Microblog_model->get_user();

		$this->load->view('templates/header', $data);
		$this->load->view('profile', $data);
		$this->load->view('templates/posts', $data);
		$this->load->view('templates/footer');
	}

	public function follow($id) {
		if ($this->ion_auth->logged_in()) {
			$this->Microblog_model->set_followers($this->ion_auth->user()->row()->id, $id);
		}
		redirect("profile/".$id);
	}

	public function unfollow($id) {
		if ($this->ion_auth->logged_in()) {
			$this->Microblog_model->delete_followers($this->ion_auth->user()->row()->id, $id);
		}
		redirect("profile/".$id);
	}
}