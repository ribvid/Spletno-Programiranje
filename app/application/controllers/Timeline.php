<?php
class Timeline extends CI_Controller {

	public function __construct() {
		parent::__construct();

		// Parametri za nalaganje slik
		$config['upload_path'] = './uploads';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size'] = 1000;
		$config['max_width'] = 2048;
		$config['max_height'] = 1536;

		$this->load->model('Microblog_model');
		
		$this->load->helper('url');
		$this->load->helper('url_helper');
		$this->load->helper('form');
		
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->library('upload', $config);
		$this->load->library('user_agent');
		
		$this->lang->load('english_lang', 'english');
	}

	public function index() {
		// Če uporabnik ni registriran, ga preusmeri na prijavno stran.
		if (!$this->ion_auth->logged_in()) {
			redirect('');
		}

		if ($this->session->userdata('language') == 'sl') $this->lang->load('slovenian_lang', 'slovenian');

		// Lokaliziran del vmesnika
		$data["language"]				= $this->session->userdata('language');
		$data["title"] 					= $this->lang->line('timeline_title');
		$data["timeline"]				= $this->lang->line('timeline');
		$data["view_my_profile"]		= $this->lang->line('view_my_profile');
		$data["view_favorited_posts"]	= $this->lang->line('view_favorited_posts');
		$data["view_shared_posts"]		= $this->lang->line('view_shared_posts');
		$data["logout"]					= $this->lang->line('logout');
		$data["new_post_title"]			= $this->lang->line('new_post_title');
		$data["new_image_title"]		= $this->lang->line('new_image_title');
		$data["new_post_placeholder"]	= $this->lang->line('new_post_placeholder');
		$data["new_image_placeholder"]	= $this->lang->line('new_image_placeholder');
		$data["publish"]				= $this->lang->line('publish');
		$data["upload_image"]			= $this->lang->line('upload_image');
		$data["user_guide"]				= $this->lang->line('user_guide');
		$data["course"]					= $this->lang->line('course');
		$data["faculty"]				= $this->lang->line('faculty');

		// Podatki za prikaz objav
		$data["loggedIn"] = $this->ion_auth->logged_in();
		$data["userId"] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row()->id : -1;
		$data["user"] = ($this->ion_auth->logged_in()) ? $this->Microblog_model->get_user($data["userId"]) : FALSE;
		$data["author"] = $data["user"];
		$data["posts"] = $this->Microblog_model->get_timeline_posts($data["userId"]);
		$data["favoritedPosts"] = $this->Microblog_model->get_favorited_posts($data["userId"]);
		$data["sharedPosts"] = $this->Microblog_model->get_all_shared_posts($data["userId"]);
		$data["users"] = $this->Microblog_model->get_user();

		// Validacija vnosnih polj
		$this->form_validation->set_rules('text', 'text', 'required');
		
		$imageProblem = FALSE;
		
		// Če je tip objave fotografija (1), potem jo naloži.
		if ($this->input->post('type') == 1) {
			if (!$this->upload->do_upload('image')) {
				$data["upload_errors"] = $this->upload->display_errors();
				$imageProblem = TRUE;
			}
		}

		if ($this->form_validation->run() === TRUE && !$imageProblem) {
			$this->Microblog_model->set_posts($data["userId"]);
		} else {
			// Beleženje napak
			log_message('error', 'Nalaganje prispevka ni uspelo. IP: '.$_SERVER['REMOTE_ADDR'].' '.validation_errors());
			if ( isset($data["upload_errors"]) ) { 
				log_message('error', 'Nalaganje slike ni uspelo. IP: '.$_SERVER['REMOTE_ADDR'].' '.$data["upload_errors"]);
			}
		}

		$this->load->view('templates/header', $data);
		$this->load->view('timeline/index', $data);
		$this->load->view('templates/posts', $data);
		$this->load->view('templates/footer');
	}

	// Funkcija, ki nastavi slovenski jezik
	public function slovenian_language() {
		$this->session->set_userdata('language', 'sl');
		redirect($this->agent->referrer());
	}

	// Funkcija, ki nastavi angleški jezik
	public function english_language() {
		$this->session->set_userdata('language', 'en');
		redirect($this->agent->referrer());
	}

}