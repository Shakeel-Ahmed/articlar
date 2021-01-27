<?php

	class Home extends CI_Controller {
  
    public function __construct()
    {
      parent::__construct();
      $this->load->model('home_model');
      $this->load->model('common_model');
      $this->load->database();
      $this->load->helper('custom');
      $_SERVER["HTTP_CF_IPCOUNTRY"] = 'id';
    }
    public function index()
    {
        $this->article();
    }
    public function article($slug=null,$page_id=null)
    {
        if($slug and !$page_id)
        {
            $this->page404();
            return;
        }
        $page = $this->home_model->article_model($page_id);
        if($page===false)
        {
            $this->page404();
            return;
        }
        echo($page);
    }
    public function page404()
    {
        $page = $this->home_model->page_404_model();
        echo($page);
    }
  }

?>