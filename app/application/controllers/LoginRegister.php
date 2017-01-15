<?php defined('BASEPATH') OR exit('No direct script access allowed');

class LoginRegister extends CI_Controller {

	public function __construct() {
		parent::__construct();

		// Konfiguracijski podatki za naložene slike.
		$config['upload_path'] = './uploads';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size'] = 1000;
		$config['max_width'] = 2048;
		$config['max_height'] = 1536;

		$this->load->database();
		
		$this->load->library('upload', $config);
		$this->load->library(array('ion_auth','form_validation'));

		$this->load->helper(array('url','language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('ion_auth_lang', 'english');
		$this->lang->load('auth_lang', 'english');
		$this->lang->load('english_lang', 'english');
	}

	public function index() {
		// Če je uporabnik prijavljen, ga takoj preusmeri na njegov timeline.
		if ($this->ion_auth->logged_in()) {
			redirect('/timeline');
		}

		// Prevod vmesnika
		if ($this->session->userdata('language') == 'sl') {
			$this->lang->load('slovenian_lang', 'slovenian'); 
			$this->lang->load('ion_auth_lang', 'slovenian'); 
			$this->lang->load('auth_lang', 'slovenian');
		}

		$this->data["title"]					= $this->lang->line('welcome');
		$this->data["user_guide"]				= $this->lang->line('user_guide');
		$this->data["course"]					= $this->lang->line('course');
		$this->data["faculty"]					= $this->lang->line('faculty');
		$this->data["profile_picture_label"]	= $this->lang->line('profile_picture_label');
		$this->data["search_placeholder"]		= $this->lang->line('search_placeholder');

		// Tip oddanega obrazca. loginForm je obrazec za prijavo. registerForm pa za registracijo.
		$formType = $this->input->post('formType');

		// Ustvari prijavna in registracijska polja.
		$this->data['identity'] = array('name' => 'identity',
			'id'    => 'identity',
			'type'  => 'text',
		);
		$this->data['password'] = array('name' => 'password',
			'id'   => 'password',
			'type' => 'password',
		);

		$this->data['bio'] = array(
			'name'  => 'bio',
			'id'    => 'bio',
			'type'  => 'text',
		);
		$this->data['profile_picture'] = array(
			'name'  => 'profile_picture',
			'id'    => 'profile_picture',
			'type'  => 'text',
			'value' => '',
		);
		$this->data['email'] = array(
			'name'  => 'email',
			'id'    => 'email',
			'type'  => 'text',
		);
		$this->data['password'] = array(
			'name'  => 'password',
			'id'    => 'password',
			'type'  => 'password',
		);
		$this->data['password_confirm'] = array(
			'name'  => 'password_confirm',
			'id'    => 'password_confirm',
			'type'  => 'password',
		);

		// Scenarij, ko uporabnik odda prijavo.
		if ($formType == "loginForm") {

			// Validaraj uporabniško ime in geslo.
			$this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
			$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

			if ($this->form_validation->run() == TRUE) {
				// Če je validacija uspela in so vnešeni podatki pravilni,
				// preusmeri uporabnika na njegov timeline.
				// V nasprotnem primeru ga obvesti, kaj je šlo narobe.
				if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'))) {
					redirect('/timeline', 'refresh');
				} else {
					// Beleženje napak
					log_message('error', 'Prijava ni uspela. IP: '.$_SERVER['REMOTE_ADDR'].' '.
						$this->ion_auth->errors().' '.$this->input->post('identity'));
					
					redirect('loginRegister', 'refresh');
				}
			} else {
				// Če pa validacija ni uspela, uporabnika obvesti, katera polja niso pravilno izpolnjena.
				$this->data['loginMessage'] = validation_errors() ? validation_errors() : $this->session->flashdata('loginMessage');

				// Beleženje napak
				log_message('error', 'Prijava ni uspela. IP: '.$_SERVER['REMOTE_ADDR'].' '.$this->data['loginMessage']);

				$this->load->view('loginRegister', $this->data);
				$this->load->view('templates/footer', $this->data);
			}

		// Scenarij, ko uporabnik odda registracijo.
		} else if ($formType = "registerForm") {
			// Za preverjanje ali podano uporabniško ime že obstaja.
			$tables = $this->config->item('tables','ion_auth');
			$identity_column = $this->config->item('identity','ion_auth');
			$this->data['identity_column'] = $identity_column;

			// Validiraj oddana polja.
			$this->form_validation->set_rules('bio', $this->lang->line('create_user_validation_bio_label'), 'required');
			$this->form_validation->set_rules('identity',$this->lang->line('create_user_validation_identity_label'),'required|is_unique['.$tables['users'].'.'.$identity_column.']');
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
			$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
			$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

			// Zastavica, ki sporoča, ali je bila vnešena slika pravilna ali ne.
			$imageProblem = FALSE;

			// Naloži sliko in preveri, če ni prišlo pri nalaganju do napak.
			// Preveri tudi, ali je bil obrazec sploh oddan.
			// Če slika ne ustreza parametrom, dvigni zastavico.
			if ( ! $this->upload->do_upload('profile_picture') && $_SERVER['REQUEST_METHOD'] == 'POST') {
				$imageProblem = TRUE;
			}

			// Če je validacija uspela in s sliko ni bilo težav, registriraj uporabnika.
			if ($this->form_validation->run() == TRUE && !$imageProblem) {
				$email    = strtolower($this->input->post('email'));
				$identity = $this->input->post('identity');
				$password = $this->input->post('password');

				$additional_data = array(
					'bio' => $this->input->post('bio'),
					'profile_picture'  => $this->upload->data('file_name'),
				);

				if ($this->ion_auth->register($identity, $password, $email, $additional_data)) {
					// Če je registracija uspela, prijavi uporabnika in ga preusmeri na timeline.
					$this->ion_auth->login($this->input->post('identity'), $this->input->post('password'));
					redirect('/timeline', 'refresh');
				} else {
					// V nasprotnem, javi, kaj je šlo narobe.
					$this->session->set_flashdata('registerMessage', $this->ion_auth->errors());

					// Beleženje napak
					log_message('error', 'Registracija ni uspela. IP: '.$_SERVER['REMOTE_ADDR'].' '.
						$this->ion_auth->errors().' '.$identity.', '.$email.', '.$additional_data["bio"].', '.
						$additional_data["profile_picture"]);

					redirect('loginRegister', 'refresh');
				}
			} else {
				// Če pa validacija ni uspela, sporoči uporabniku težave.
				$this->data['registerMessage'] = validation_errors() ? validation_errors() : $this->session->flashdata('registerMessage');
				$this->data["uploadErrors"] = $imageProblem ? $this->upload->display_errors() : '';

				// Beleženje napak
				log_message('error', 'Registracija ni uspela. IP: '.$_SERVER['REMOTE_ADDR'].' '.
					$this->data['registerMessage'].' '.$this->data["uploadErrors"]);

				$this->load->view('loginRegister', $this->data);
				$this->load->view('templates/footer', $this->data);
			}
		}
	}

	public function logout() {
		$logout = $this->ion_auth->logout();
		redirect('loginRegister', 'refresh');
	}

}
