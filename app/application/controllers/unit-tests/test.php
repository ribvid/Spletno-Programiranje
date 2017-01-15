<?php
class Test extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->load->model('Microblog_model');
		$this->load->library('unit_test');		
	}

	public function index() {
		$user = $this->Microblog_model->get_user(1);
		$test1 = $user["username"];
		$expected_result1 = "Vid Ribic";
		$test_name1 = "Users's name";
		echo $this->unit->run($test1, $expected_result1, $test_name1);

		$tag = $this->Microblog_model->get_tag("#Vienna");
		$test2 = $tag[0]["name"];
		$expected_result2 = "#Vienna";
		$test_name2 = "Tag";
		echo $this->unit->run($test2, $expected_result2, $test_name2);

		$test3 = count($this->Microblog_model->get_posts());
		$expected_result3 = 4;
		$test_name3 = "Number of posts";
		echo $this->unit->run($test3, $expected_result3, $test_name3);

		$test4 = count($this->Microblog_model->get_all_shared_posts(1));
		$expected_result4 = 0;
		$test_name4 = "Number of shared posts";
		echo $this->unit->run($test4, $expected_result4, $test_name4);
	}

}
