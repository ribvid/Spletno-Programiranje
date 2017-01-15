<?php
class Microblog_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

	public function get_posts($authorId = FALSE) {
		if ( $authorId === FALSE ) {
			$query = $this->db->get("post");
			return $query->result_array();
		}

		$query = $this->db->order_by('pub_date', 'DESC')->limit(100)->get_where("post", array('author_id' => $authorId));
		return $query->result_array();
	}

	public function set_posts($authorId) {
		$this->load->helper('url');

		//
		// #1 - Vnesi v bazo novo objavo
		//
		date_default_timezone_set('Europe/Ljubljana');

		$imagePath = ($this->input->post('type') == 1 ? $this->upload->data('file_name') : '');

		$data = array(
			'type'			=>	$this->input->post('type'),
			'author_id'		=>	$authorId,
			'content'		=>	$this->input->post('text'),
			'photo'			=>	$imagePath,
			'pub_date'		=>	date('Y/m/d H:i:s')
		);

		$this->db->insert('post', $data);

		//
		// #2 - Vnesi v bazo znacke
		//
		$postId = $this->db->insert_id();
		preg_match_all("/[#]\w*/", $this->input->post('text'), $tags);
		foreach($tags[0] as $tag) {	
			$data = array(
				'name'		=> $tag,
				'post_id'	=> $postId
			);
			$this->db->insert('tags', $data);
		}		
	}

	public function get_user($authorId = FALSE) {
		if ( $authorId === FALSE ) {
			$query = $this->db->get("users");
			return $query->result_array();
		}

		$query = $this->db->get_where("users", array('id' => $authorId));
		return $query->row_array();
	}

	public function get_tag($tagName = FALSE) {
		if ( $tagName === FALSE ) {	
			$query = $this->db->get("tags");
			return $query->result_array();
		}

		$query = $this->db->select('*')->from('tags')->where('name', $tagName)->join('post', 'post.id = tags.post_id');
		return $query->order_by('pub_date', 'DESC')->get()->result_array();
	}

	public function set_followers($follower, $followingUser) {
		$data = array(
			"user_id"			=> $follower,
			"following_user_id"	=> $followingUser
		);
		return $this->db->insert('followers', $data);
	}

	public function delete_followers($follower, $followingUser) {
		$data = array(
			"user_id"			=> $follower,
			"following_user_id"	=> $followingUser
		);
		return $this->db->delete('followers', $data);
	}

	public function get_following_users($id) {
		return $this->db->get_where("followers", array('user_id' => $id))->result_array();
	}

	public function get_timeline_posts($id) {
		$query = $this->db->select('*')->from('followers')->where('user_id', $id)->join('users', 'users.id = followers.following_user_id')->join('post', 'post.author_id = users.id');
		return $query->order_by('pub_date', 'DESC')->limit(30)->get()->result_array();
	}

	public function set_favorited_posts($postId, $userId) {
		$data = array(
			"post_id"	=> $postId,
			"user_id"	=> $userId
		);
		$this->db->insert('favorites', $data);
		$this->db->set('favorites', 'favorites+1', FALSE)->where('id', $postId)->update('post');
	}

	public function delete_favorited_posts($postId, $userId) {
		$data = array(
			"post_id"	=> $postId,
			"user_id"	=> $userId
		);
		$this->db->delete('favorites', $data);
		$this->db->set('favorites', 'favorites-1', FALSE)->where('id', $postId)->update('post');
	}

	public function get_favorited_posts($id) {
		$query = $this->db->select('*')->from('favorites')->where('user_id', $id)->join('post', 'post.id = favorites.post_id');
		return $query->get()->result_array();
	}

	public function set_shared_posts($postId, $userId) {
		$data = array(
			"post_id"	=> $postId,
			"user_id"	=> $userId
		);
		$this->db->insert('shares', $data);
		$this->db->set('shares', 'shares+1', FALSE)->where('id', $postId)->update('post');
	}

	public function delete_shared_posts($postId, $userId) {
		$data = array(
			"post_id"	=> $postId,
			"user_id"	=> $userId
		);
		$this->db->delete('shares', $data);
		$this->db->set('shares', 'shares-1', FALSE)->where('id', $postId)->update('post');
	}

	public function get_all_shared_posts($id) {
		$query = $this->db->select('*')->from('shares')->where('user_id', $id)->join('post', 'post.id = shares.post_id');
		return $query->get()->result_array();
	}

	public function get_shared_posts($id) {
		$query = $this->db->select('*')->from('shares')->where('user_id', $id)->join('post', 'post.id = shares.post_id');
		return $query->order_by('pub_date', 'DESC')->limit(30)->get()->result_array();
	}

}