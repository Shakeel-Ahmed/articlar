<?php

	class Home_model extends CI_Model {

    public function __construct()
    {
        #$this->load->database();
    }
    public function article_model($page_id)
    {

        if($page_id)
        {
            if($page_id != 'results' and $this->db->query("SELECT `status` FROM `{$this->config->item('DBTPage')}` WHERE `page-id` = '$page_id'")->row_array()['status']!=4) return $this->page_404_model();
        }
        // if there is no page id then system suspect it is the homepage
        else $page_id = $this->db->query("SELECT `page-id` FROM `{$this->config->item('DBTPage')}` WHERE `status` = 5")->row_array()['page-id'];

        $page = $this->common_model->generate_html($page_id);
        if($page===false) return false;
        $page = str_replace
        (
            '<body>',
            '
            </style>
            <link href="'.base_url('include/articlar-styles.css').'" rel="stylesheet" type="text/css">
            <body>',$page['body']
        );
        if($page_id!=='results') $this->log_model($page_id);
        return $page;
    }
    public function log_model($page_id)
    {
        if(!$page_id) return false;

        $i = 1;
        $page = $this->db->query('SELECT `author-id`,`views` FROM `'.$this->config->item('DBTPage').'` WHERE `page-id` = '.$page_id.'')->row_array();
        $author = $this->db->query('SELECT `views` FROM `'.$this->config->item('DBTAuthor').'` WHERE `author-id` = '.$page['author-id'].'')->row_array();

        $author_views_update = array('views'=>($author['views']+$i));
        if(!$this->db->set($author_views_update)->where('author-id', $page['author-id'])->update($this->config->item('DBTAuthor'))) return false;

        $page_views_update = array('views'=>($page['views']+$i));
        $this->db->set($page_views_update)->where('page-id', $page_id)->update($this->config->item('DBTPage'));

        if(isset($_SERVER["HTTP_CF_IPCOUNTRY"])) $country = $_SERVER["HTTP_CF_IPCOUNTRY"];
        else $country = null;

        $insert = array
        (
            'page-id'   =>  $page_id,
            'author-id' =>  $page['author-id'],
            'country'   =>  $country
        );
        $this->db->insert($this->config->item('DBTLog'),$insert);

        return true;
    }
    public function page_404_model()
    {
        $data = $this->db->query("SELECT `page-id` FROM `{$this->config->item('DBTPage')}` WHERE `keywords` = '[art-404]' AND `status` = '4'")->row_array();
        if($data===null)
        {

            return $this->common_model->message([
                'title'		=> 'Error 404!',
                'message'	=> 'Sorry! The page you are looking for is not found.',
                'action'	=> '<a href="'.base_url().'"><input type="submit" class="button" value="Home"></a>'
            ]);
        }
        $page = $this->common_model->generate_html($data['page-id']);
        if($page===false) return false;
        $page = str_replace
        (
            '<body>',
            '
            </style>
            <link href="'.base_url('include/articlar-styles.css').'" rel="stylesheet" type="text/css">
            <body>',$page['body']
        );
        return $page;
    }

}

?>