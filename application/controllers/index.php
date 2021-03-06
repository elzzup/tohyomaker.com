<?php

class Index extends CI_Controller
{

	/** @var Survey_model */
	public $survey;

	/** @var User_model */
	public $user;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Survey_model', 'survey', TRUE);
		$this->load->model('User_model', 'user', TRUE);
	}

	function index()
	{

		$user = $this->user->get_main_user();
		$surveys_hot = $this->survey->get_surveys_hot($user, 5, 0);
		$surveys_new = $this->survey->get_surveys_new($user, 5, 0);

		$meta = new Metaobj();
		$meta->setup_top();
		$this->load->view('head', array('meta' => $meta));
		$this->load->view('navbar', array('user' => $user));

		$topmain_info = array(
				'surveys_hot' => $surveys_hot,
				'surveys_new' => $surveys_new,
		);
		$this->load->view('topmain', $topmain_info);
		$this->load->view('surveysblock', array('surveys' => $surveys_hot, 'type' => SURVEY_BLOCKTYPE_HOT));
		$this->load->view('surveysblock', array('surveys' => $surveys_new, 'type' => SURVEY_BLOCKTYPE_NEW));

		$this->load->view('foot', array('user' => $user));
	}

}
