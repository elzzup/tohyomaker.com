<?php

//投票ページ
class Survey extends CI_Controller
{
	/* @var $survey Survey_model */
	/* @var $user Survey_model */

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->config->load('my_twitter');
		$this->load->helper('url');
		$this->load->helper('func');
		$this->load->helper('token');
		$this->load->helper('parts');

		$this->load->model('Survey_model', 'survey', TRUE);
		$this->load->model('User_model', 'user', TRUE);
	}

	public function Index()
	{
		// TODO: jump vote page
		echo 'index on survey';
	}

	function vote($id_survey)
	{
		/* @var $survey SurveyObj */
		if (($survey = $this->survey->get_survey($id_survey)) === FALSE)
		{
			die("no found id : {$id_survey}");
			// TODO: jump no found page
		}

		$title = $survey->title;
		$head_info = array(
				'title' => $title,
				'less_name' => 'main',
		);
		$this->load->view('head', $head_info);
		$this->load->view('title', array('title' => $title));
		$this->load->view('navbar', array('user' => $this->user->get_user()));

		$vote_info = array(
				'survey' => $survey,
		);
		$this->load->view('vote', $vote_info);
		$this->load->view('foot');
	}

	function view($id_survey)
	{
		
	}

	public function info($id_survey)
	{
		
	}

}
