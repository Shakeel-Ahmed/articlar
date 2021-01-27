<?php

class Author extends CI_Controller {

    public function __construct()
    {
        session_start();
        parent::__construct();
        $this->load->model ('author_model');
        $this->load->model('common_model');
        $this->load->helper('custom');
        $this->load->database();
        #$this->output->enable_profiler(TRUE);
    }
    /*-- gatekeeper method --*/
    public function index()
    {
        $this->home();
    }
    public function admin($page=null)
    {
        if(!$page)
        {
            $this->index();
            return;
        }
        $allowed = array
        (
            'home'              =>  'home',
            'edit-my-profile'   =>  'author_edit',
            'create-new-article'=>  'page_create',
            'edit-article'      =>  'page_edit',
            'set-page-status'   =>  'page_publish_status_change',
            'preview-article'   =>  'page_preview',
            'view-article'      =>  'page_preview',
            'delete-article'    =>  'page_delete',
            'publish-article'   =>  'page_publisher',
            'unpublish-article' =>  'page_unpublish',
            'set-home-page'     =>  'page_home',
            'articles'          =>  'page_results',
            'generate-element'  =>  'ajax_processor',
            'filebrowser'       =>  'filebrowser',
            'image-editor'      =>  'image_editor',
            'replace-image'     =>  'image_replace',
            'adjust'            =>  'adjust',
            'test'              =>  'test',
            'enter'             =>  'login',
            'logout'            =>  'logout',
            'recover-password'  =>  'login_recover',
            'reset-password'    =>  'login_reset'
        );
        if(!array_key_exists($page,$allowed))
        {
            $this->error404();
            return;
        }
        /* parameter generating code */
        list($null,$parameters) = explode($allowed[$page],str_replace($page,$allowed[$page],$_SERVER['REQUEST_URI']));
        eval('$this->'.$allowed[$page].'("'.str_replace('/','","',substr($parameters,1,strlen($parameters))).'");');
    }
    protected function home()
    {
        $this->author_model->authenticate();
        $data = $this->author_model->home_model();
        $data['title'] = 'Articlar Dashboard';
        $this->load->view('header-writer',$data);
        $this->load->view('writer-home');
        $this->load->view('footer-writer');
    }
    protected function author_edit($author_id=null)
    {
        $this->author_model->authenticate();

        if(!$author_id)
        {
            $this->error404();
            return;
        }
        $data = $this->common_model->author_edit_model($author_id);
        if(!$data)
        {
            $this->error404();
            return;
        }
        if(isset($data['status']))
        {
            if($data['status']===true)
            {
                $this->home();
                return;
            }
            $this->message(
                array
                (
                    'title'		=> $data['title'],
                    'message'	=> $data['message'],
                    'action'	=> $data['action']
                ));
            return;
        }

        $data['title'] = 'Edit My Profile';
        $this->load->view('header-writer',$data);
        $this->load->view('common-writer-edit');
        $this->load->view('footer-writer');

    }
    protected function page_create()
    {
        $this->author_model->authenticate();
        $data = $this->author_model->page_create_model();
        if(isset($data['status']))
        {
            $this->message(
                array
                (
                    'title'		=> $data['title'],
                    'message'	=> $data['message'],
                    'action'	=> $data['action']
                ));
            return;
        }

        $data['title'] = 'Create Article';
        $this->load->view('header-writer',$data);
        $this->load->view('writer-page-add');
        $this->load->view('footer-writer');
    }
    protected function page_edit($title=null,$page_id=null,$backbutton=null)
    {
        if(!$page_id)
        {
            $this->error404('writer');
            return;
        }
        $this->author_model->authenticate();
        $data = $this->common_model->page_edit_model($page_id,'author',$backbutton);

        if(!$data)
        {
            $this->error404();
            return;
        }
        if(isset($data['status']))
        {
            if($data['status']===true)
            {
                $data['action'] =   '<a href="'.base_url('author/admin/').'"><input type="submit" class="button" value="Dashboard"></a>'.
                                    '<a href="'.base_url('author/admin/preview-article/'.dash_url('updated article').'/'.$page_id.'/noback').'"><input type="submit" class="button" value="Preview"></a>'.
                                    '<a href="'.$_SERVER['REQUEST_URI'].'"><input type="submit" class="button" value="Edit"></a>';
            }
            $this->message(
                array
                (
                    'title'		=> $data['title'],
                    'message'	=> $data['message'],
                    'action'	=> $data['action']
                ));
            return;
        }
        if($data['row']['image']) $data['row']['image'] = base_url($this->config->item('uploadir').'page-title-pictures/'.$data['row']['image']);
        else $data['row']['image'] = base_url($this->config->item('uploadir').'picture.svg');

        $data['title']       = 'Edit Article - '.ucwords($data['row']['title']);
        $data['row']['body'] = str_replace('../','',str_replace('../../../../images','/images',$data['row']['body']));
        $data['mod'] = 'author';

        $this->load->view('header-writer',$data);
        $this->load->view('common-page-edit');
        $this->load->view('footer-writer');
    }
    protected function page_preview($title=null,$page_id=null,$backbutton=null)
    {
        $this->author_model->authenticate();
        $page = $this->common_model->page_preview_model($page_id,'author',$backbutton);
        if($page===false)
        {
            $this->error404();
            return;
        }
        else echo $page;
        return;
    }
    protected function page_publish_status_change($page_id=null,$status=null)
    {
        $this->author_model->authenticate();
        $this->load->model('editor_model');
        $data = $this->editor_model->page_publish_status_change_model($page_id,$status,'author');
        die($data);
    }
    protected function page_results($search=null,$order=null,$sort='ASC',$current_page=1)
    {
        $this->author_model->authenticate();

        if(!$search) $search = 'datasearch';
        if(!$order) $order = 'name';
        if(!$sort) $sort = 'standard';
        if(!$current_page) $current_page = 1;

        $search = str_replace('-',' ',$search);
        $search_data = array
        (
            'database-table' => $this->config->item('DBTPage'),
            'restrict' => [['author-id' => [$_SESSION['author-id']=>'=']]],
            'select' => 'author-id,page-id,status,title,image,description,views,d_n_t,t_n_d',
            'search-type' => 'fulltext',
            'fulltext-mode' => 'IN BOOLEAN MODE',
            'search' => array('title'=>$search,'keywords'=>$search),
            'order' => $order,
            'sort' => $sort,
            'current-page' => $current_page
        );
        $data = $this->common_model->search_model($search_data);

        // PAGINATION CONFIG
        $data['config'] = array
        (
            'url'      => base_url("author/admin/articles/$search/$order/$sort"),
            'results'  => $data['results'],
            'current'  => $current_page,
            'perpage'  => $this->config->item('perpage'),
            'segsize'  => $this->config->item('segsize'),
            'back'     => '<i class="art-icon hover" style="vertical-align: middle; margin-right: 10px;">arrow_back</i>',
            'backward' => '<i class="art-icon hover" style="vertical-align: middle;">chevron_left</i>',
            'first'    => '<span class="hover">FIRST</span> ',
            'next'     => ' <i class="art-icon hover" style="vertical-align: middle; margin-left: 10px;">arrow_forward</i>',
            'forward'  => '<i class="art-icon hover" style="vertical-align: middle;">chevron_right</i>',
            'last'     => ' <span class="hover">LAST</span>',
            'seprator' => ' . ',
            'class'    => 'text-color-highlight'
        );
        if(empty($data['rows']))
        {
            $this->message(
                array
                (
                    'title'   => 'No Results',
                    'message' => 'Your search has not fetch any results matching your search criteria. Search again with different key words.',
                    'action'  => '<a href="javascript:goBack();"><input type="submit" class="button" value="Back"></a>'
                ));
            return;
        }
        $data['uploadir'] = $this->config->item('uploadir');
        $data['title'] = 'Author Articles';
        $this->load->view('header-writer', $data);
        $this->load->view('writer-page-results');
        $this->load->view('footer-writer');
    }
    protected function page_delete($page_id)
    {
        $this->author_model->authenticate();
        $data = $this->common_model->page_delete_model($page_id);
        // data contains JSON string
        die($data);
    }
    protected function page_home($page_id)
    {
        $this->author_model->authenticate();
        $this->author_model->page_home_model($page_id);
    }
    protected function filebrowser($author_id)
    {
        $this->author_model->authenticate();
        $data = $this->common_model->filebrowser_model($author_id);
        $data['author_id'] = $author_id;
        $data['mod'] = 'author';
        $this->load->view('common-filebrowser',$data);
    }
    protected function image_editor($author_id,$image)
    {
        $this->author_model->authenticate();

        $data = $this->common_model->image_editor_model($author_id,$image);
        if($data['status']===true)
        {
            $this->filebrowser($author_id);
            return;
        }

        $data['author_id'] = $author_id;
        $data['mod'] = 'author';
        $data['image'] = $image;
        $this->load->view('common-image-editor',$data);
    }
    protected function image_replace($author_id,$image)
    {
        $this->author_model->authenticate();


        $data = $this->common_model->image_replace_model($author_id,$image);
        if($data['status']===true)
        {
            $this->filebrowser($author_id);
            return;
        }

        $data['author_id'] = $author_id;
        $data['image'] = $image;
        $data['mod'] = 'author';
        $this->load->view('common-image-replace', $data);
    }
    protected function adjust ()
    {
        $data['title'] = 'Theme Preview';
        $this->load->view('header-writer',$data);
        $this->load->view('theme-adjustment-page.php');
        $this->load->view('footer-writer');
    }
    public function error404()
    {
        $this->message(
            array
            (
                'title'		=> 'Page Not Found .. Error 404',
                'message'	=> 'Sorry! The page you are looking for dosen\'t exist.',
                'action'	=> '<a href="'.base_url().'author/admin/"><input type="submit" class="button" value="Home"></a>'
            ));
    }
    protected function login ()
    {
        $this->author_model->login_model();
        $data['title'] = 'Author Login';
        $this->load->view('header-blank',$data);
        $this->load->view('writer-login');
        $this->load->view('footer-writer');
    }
    protected function login_recover()
    {
        $data = $this->author_model->login_recover_model();
        if(isset($data['status']))
        {
            $this->common_model->message(
                array
                (
                    'title'		=>  $data['title'],
                    'message'	=>  $data['message'],
                    'action'	=>  '<a href="'.base_url().'"><input type="submit" class="button" value="Home"></a>'.$data['button']
                ));
            return;
        }
        $data['title'] = 'Password Recover';
        $this->load->view('header-blank',$data);
        $this->load->view('writer-login-recover');
        $this->load->view('footer-blank');
    }
    protected function login_reset($token=null)
    {
        if(!$token)
        {
            $this->error404();
            return;
        }
        $data = $this->author_model->login_reset_model($token);

        if(isset($data['status']))
        {
            $this->common_model->message(
                array
                (
                    'title'		=>  $data['title'],
                    'message'	=>  $data['message'],
                    'action'	=>  '<a href="'.base_url().'"><input type="submit" class="button" value="Home"></a>'.$data['button']
                ));
            return;
        }

        $data['title'] = 'Password Recover';
        $this->load->view('header-blank',$data);
        $this->load->view('writer-login-reset');
        $this->load->view('footer-blank');

    }
    protected function message($data)
    {
        $this->author_model->authenticate();
        $this->load->view('header-writer',$data);
        $this->load->view('message');
        $this->load->view('footer-writer');
    }
    protected function password_maker()
    {
        /*
        * current login
        * login@demo.com
        * abcd1234
        */
        $insert_login = array
        (
            'editor-id' => ranum(12),
            'login' => md5('jibrankassim@gmail.com'),
            'hash' => password_hash('abcd1234', PASSWORD_BCRYPT)
        );
        if($this->db->insert($this->config->item('DBTEditorLogin'),$insert_login)) die("created ...");else die('Error...');
    }
    public function logout()
    {
        $this->author_model->logout();
    }
    public function test()
    {

        $data['title'] = 'test';
        $this->load->view('header-writer',$data);
        $this->load->view('test');
        $this->load->view('footer-writer');

        $re = '/(\[art-link-)(.*?)\]/im';
        $str = 'this is [art-link-loverboy] a best thing [art-link-loverman] to do when you are hungry';

        preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
        foreach($matches as $match) echo $match[2]."<br/>";
    }
    public function testb()
    {
        echo filesize('articlar-master-backup.sql');
    }
}
?>