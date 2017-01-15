<?php
class Post extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Microblog_model');
		$this->load->helper('url');
		$this->load->helper('url_helper');
		$this->load->library('ion_auth');
		$this->load->library('user_agent');
	}

	public function favorite($postId) {
		if ($this->ion_auth->logged_in()) {
			$this->Microblog_model->set_favorited_posts($postId, $this->ion_auth->user()->row()->id);
		}
		redirect($this->agent->referrer());
	}

	public function undo_favorite($postId) {
		if ($this->ion_auth->logged_in()) {
			$this->Microblog_model->delete_favorited_posts($postId, $this->ion_auth->user()->row()->id);
		}
		redirect($this->agent->referrer());
	}

	public function share($postId) {
		if ($this->ion_auth->logged_in()) {
			$this->Microblog_model->set_shared_posts($postId, $this->ion_auth->user()->row()->id);
		}
		redirect($this->agent->referrer());
	}

	public function undo_share($postId) {
		if ($this->ion_auth->logged_in()) {
			$this->Microblog_model->delete_shared_posts($postId, $this->ion_auth->user()->row()->id);
		}
		redirect($this->agent->referrer());
	}

}