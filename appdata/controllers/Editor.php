<?php

class Editor extends CI_Controller {

    public function __construct()
    {
        session_start();
        parent::__construct();
        $this->load->model('editor_model');
        $this->load->model('common_model');
        $this->load->helper('custom');
        $this->load->database();

        $this->pagination = array
        (
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
    #$this->output->enable_profiler(TRUE);
    }
    /*-- starter method --*/
    public function index()
    {
        $this->home();
    }
    public function admin($page=null)
    {
        if(!$page)
        {
            $this->home();
            return;
        }
        $allowed = array
        (
            'home'                    => 'home',
            //  AUTHOR
            'create-author-profile'   => 'author_create',
            'edit-author-profile'     => 'author_edit',
            'view-author-profile'     => 'author_display',
            'authors'                 => 'author_results',
            //  THEME
            'install-theme'           => 'theme_create',
            'update-theme-template'   => 'theme_update_template',
            'template-validator'      => 'theme_validator',
            'activate-theme'          => 'theme_activate',
            'delete-theme'            => 'theme_delete',
            'installed-themes'        => 'theme_results',
            //  ARTICLES
            'articles'                => 'page_results',
            'preview-article'         => 'page_preview',
            'edit-article'            => 'page_edit',
            'set-page-status'         => 'page_publish_status_change',
/*
            'set-home-page'           => 'page_home',
            'unset-home-page'         => 'page_home_unset',
*/
            'delete-article'          => 'page_delete',
            'edit-fold'               => 'page_edit',
            //  FILEBROWSER
            'filebrowser'             => 'filebrowser',
            'image-editor'            => 'image_editor',
            'replace-image'           => 'image_replace',
            // LOGIN
            'enter'                   => 'login',
            'logout'                  => 'logout',
            'recover-password'        => 'login_recover',
            'reset-password'          => 'login_reset',
            //  VARIOUS
            'settings'                => 'settings',
            'about'                   => 'about',
            'update'                  => 'update_version',
            'combine-js'              => 'combine_js',
            'combine-css'             => 'combine_css',
            'test'                    => 'test'
        );
        if(!array_key_exists($page,$allowed))
        {
            $this->error404('editor');
            return;
        }
        /* parameter generating code */
        list($null,$parameters) = explode($allowed[$page],str_replace($page,$allowed[$page],$_SERVER['REQUEST_URI']));
        eval('$this->'.$allowed[$page].'("'.str_replace('/','","',substr($parameters,1,strlen($parameters))).'");');
    }
    protected function home()
    {
        $this->editor_model->authenticate();

        $data = $this->editor_model->home_model();

        $data['title'] = 'Editor Dashboard';
        $this->load->view('header-editor',$data);
        $this->load->view('editor-home');
        $this->load->view('footer-editor');
    }
    /* -- Authentication --*/
    protected function login ()
    {
        $this->editor_model->login_model();
        $data['title'] = 'Editor Login';
        $this->load->view('header-blank',$data);
        $this->load->view('editor-login');
        $this->load->view('footer-blank');
    }
    public function logout()
    {
        $this->editor_model->logout();
    }
    protected function login_recover()
    {
        $data = $this->editor_model->login_recover_model();
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
        $this->load->view('common-login-recover');
        $this->load->view('footer-blank');
    }
    protected function login_reset($token=null)
    {
        if(!$token)
        {
            $this->error404();
            return;
        }
        $data = $this->editor_model->login_reset_model($token);

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
        $this->load->view('editor-login-reset');
        $this->load->view('footer-blank');

    }
    /* -- Theme --*/
    protected function theme_create()
    {
        $this->editor_model->authenticate();
        $data = $this->editor_model->theme_create_model();

        if(isset($data['status']))
        {
            switch($data['status'])
            {
                case 'installed': $title = 'Theme Installed';break;
                case 'updated': $title = 'Theme Updated Successfully';break;
                case 'error': $title = 'Theme Installation Failed';break;
            }

            $this->message(
                array
                (
                    'title'		=> $title,
                    'message'	=> $data['message'],
                    'action'	=> '<input type="submit" onclick="go(\''.$_SERVER['REQUEST_URI'].'\');" class="button" value="Again">'.
                                   '<input type="submit" onclick="go(\''.base_url().'editor/admin\');" class="button space-left-1" value="home">'
                ));
            return;
        }

        $data['title'] = 'Shakeel Ahmed';
        $this->load->view('header-editor',$data);
        $this->load->view('editor-theme-add');
        $this->load->view('footer-editor');
    }
    protected function theme_activate($theme_id)
    {
        $this->editor_model->authenticate();
        $data = $this->editor_model->theme_activate_model($theme_id);
        die('{"status":"'.$data['status'].'","current":"'.$data['current'].'"}');
    }
    protected function theme_update_template($theme_id=null,$directive=null)
    {
        $this->editor_model->authenticate();
        $data = $this->editor_model->theme_update_template_model($theme_id);

        if(!$data)
        {
            $this->error404();
            return;
        }

        if($directive == 'ajax')
        {
            if(isset($data['status']) and $data['status']=='error')
            {
                die('{"status":"error","message":"<hr/>"}');
            }
            if(isset($data['status']) and $data['status']=='false') die('{"status":"false","message":"error in database"}');
            else die('{"status":"success","message":"no message is required"}');
            return;
        }
        if(isset($data['status']) and $data['status']=='error')
        {
            $this->message(
                array
                (
                    'title'		=> 'Error!',
                    'message'	=> 'Your template editing have errors. Fix these errors and try again.<hr/>'.$data['message'],
                    'action'	=> '<input type="submit" onclick="goBack();" class="button" value="Back">'.
                                   '<input type="submit" onclick="go(\''.base_url().'editor/admin/\');" class="button space-left-1" value="home">'
                ));
            return;
        }
        if(isset($data['status']) and $data['status']=='success')
        {
            $this->message(
                array
                (
                    'title'		=> 'Template Updated',
                    'message'	=> 'Template changes are saved successfully.<hr/>',
                    'action'	=> '<input type="submit" onclick="go(\''.base_url().'editor/admin/\');" class="button space-left-1" value="home">'
                ));
            return;
        }

        $data['title'] ='Update Theme Template';
        $this->load->view('header-editor',$data);
        $this->load->view('editor-theme-update');
        $this->load->view('footer-editor');
    }
    protected function theme_validator()
    {
        $this->editor_model->authenticate();
        $data = $this->editor_model->theme_validator_model();

        if(isset($data['status']) and $data['status']=='error')
        {
            $this->message(
                array
                (
                    'title'		=>  'Error!',
                    'message'	=>  'Your template has errors. Fix these errors and try again.<hr/>'.$data['message'],
                    'action'	=>  '<input type="submit" onclick="goBack();" class="button" value="Back">'.
                                    '<input type="submit" onclick="go(\''.base_url().'editor/admin/\');" class="button space-left-1" value="home">'
                ));
            return;
        }
        if(isset($data['status']) and $data['status']=='success')
        {
            $this->message(
                array
                (
                    'title'		=>  'Valid Articlar Template',
                    'message'	=>  $data['message'],
                    'action'	=>  '<input type="submit" onclick="goBack();" class="button" value="Back">'.
                                    '<input type="submit" onclick="go(\''.base_url().'editor/admin/\');" class="button space-left-1" value="home">'
                ));
            return;
        }

        $data['title'] ='Template Validator';
        $this->load->view('header-editor',$data);
        $this->load->view('editor-theme-validator');
        $this->load->view('footer-editor');
    }
    protected function theme_results($search=null,$order=null,$sort=null,$current_page=null)
    {
        $this->editor_model->authenticate();
        $data = $this->editor_model->theme_results_model($search,$order,$sort,$current_page);

        if(empty($data['rows']))
        {
            $this->message(
                array
                (
                    'title'		=> 'No Search Result',
                    'message'	=> 'Your search did not fetch any result. Please try again with different search criteria.',
                    'action'	=> null
                ));
            return;
        }

        $this->pagination['url'] = base_url("editor/admin/installed-themes/$search/$order/$sort");
        $this->pagination['current'] = $current_page;
        $this->pagination['results'] = $data['results'];

        $data['title'] = 'Installed Themes';
        $data['config']  = $this->pagination;

        $this->load->view('header-editor',$data);
        $this->load->view('editor-theme-results');
        $this->load->view('footer-editor');

    }
    protected function theme_delete($theme_id)
    {
        $this->editor_model->authenticate();
        $data = $this->editor_model->theme_delete_model($theme_id);
        if($data==true) die('true');
        else die('false');
    }
    /* -- Author --*/
    protected function author_create()
    {
        $this->editor_model->authenticate();
        $data = $this->editor_model->author_create_model();
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

        $data['title'] = 'Create Author Profile';
        $this->load->view('header-editor',$data);
        $this->load->view('editor-writer-add');
        $this->load->view('footer-editor');
    }
    protected function author_edit($author_id=null)
    {
        $this->editor_model->authenticate();
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

        $data['title'] = 'Edit Author Profile';
        $this->load->view('header-editor',$data);
        $this->load->view('common-writer-edit');
        $this->load->view('footer-editor');

    }
    protected function author_display($author_id=null)
    {
        $this->editor_model->authenticate();
        if(!$author_id)
        {
            $this->error404();
            return;
        }
        $data = $this->editor_model->author_display_model($author_id);
        if(!$data)
        {
            $this->error404();
            return;
        }

        $data['title'] = 'Author Details';
        $this->load->view('header-editor', $data);
        $this->load->view('editor-writer-display');
        $this->load->view('footer-editor');


    }
    protected function author_results($search=null,$order=null,$sort=null,$current_page=null)
    {
        $this->editor_model->authenticate();

        if(!$search) $search = 'datasearch';
        if(!$order) $order = 'name';
        if(!$sort) $sort = 'standard';
        if(!$current_page) $current_page = 1;

        $search = str_replace('-',' ',$search);
        $search_data = array
        (
            // database table to search in
            'database-table' => $this->config->item('DBTAuthor'),
            // make it complusry to find certain values in
            // certain database columns like author-id must be xxxx
            // FORMAT ['DB COLUMN'=>['VALUE'=>'OPERATOR'],'DB COLUMN'=>['VALUE'=>'OPERATOR']]
            'restrict' => null,//array('author-id' => '123456789'),
            // select database columns to retiriev data from.
            // for all use *
            'select' => 'author-id,name,picture,d_n_t',
            // fulltext or null
            'search-type' => 'fulltext',
            // if fulltext then define MYSQL
            // fulltext search modes
            'fulltext-mode' => 'IN BOOLEAN MODE',
            // search in 'database column' => 'search string'
            'search' => array('name'=>$search,'email'=>$search),
            // order by database column
            'order' => $order,
            // sort order standard or reverse
            'sort' => $sort,
            // current serach result page index
            'current-page' => $current_page,
        );

        $data = $this->common_model->search_model($search_data);

        if(empty($data['rows']))
        {
            $this->message(
                array
                (
                    'title'		=> 'No Search Result',
                    'message'	=> 'Your search did not fetch any result. Please try again with different search criteria.',
                    'action'	=> '<input onclick="go(\''.base_url().'editor/admin/\')" type="submit" class="button" value="dashboard"></a>'.
                                   '<input onclick="goBack()" type="submit" class="button" value="Back"></a>'
                ));
            return;
        }

        $this->pagination['url'] = base_url("editor/admin/authors/$search/$order/$sort");
        $this->pagination['current'] = $current_page;
        $this->pagination['results'] = $data['results'];

        $data['title']    = 'Authors';
        $data['uploadir'] = base_url($this->config->item('uploadir'));
        $data['config']   = $this->pagination;
        $data['current']  = $current_page;

        $this->load->view('header-editor',$data);
        $this->load->view('editor-writer-results');
        $this->load->view('footer-editor');
    }
    /* -- Articles -- */
    protected function page_home($page_id)
    {
        $this->editor_model->authenticate();
        $this->editor_model->page_home_model($page_id);
    }
    protected function page_edit($author_id=null,$page_id=null)
    {
        if(!$page_id or !$author_id)
        {
            $this->error404('writer');
            return;
        }
        $this->editor_model->authenticate();
        $data = $this->common_model->page_edit_model($page_id);
        if(!$data)
        {
            $this->error404();
            return;
        }
        if(isset($data['status']))
        {
            if($data['status']===true)
            {
                $data['action'] =   '<a href="'.base_url('editor/admin').'"><input type="submit" class="button" value="Dashboard"></a>'.
                                    '<a href="'.base_url('editor/admin/preview-article/'.dash_url('updated article').'/'.$page_id.'/backbutton').'"><input type="submit" class="button" value="Preview"></a>'.
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
        $data['row']['body'] = str_replace('../','',str_replace('../../../../images',base_url().'images',$data['row']['body']));
        $data['mod'] = 'editor';

        $this->load->view('header-editor',$data);
        $this->load->view('common-page-edit');
        $this->load->view('footer-editor');
    }
    protected function page_results ($search=null,$order=null,$sort=null,$current_page=null)
    {
        $this->editor_model->authenticate();

        if(!$search) $search = 'datasearch';
        if(!$order) $order = 'name';
        if(!$sort) $sort = 'standard';
        if(!$current_page) $current_page = 1;

        $search = str_replace('-',' ',$search);
        $search_data = array
        (
            'database-table' => $this->config->item('DBTPage'),
            'restrict' => [['status'=>['2'=>'>']]],
            'select' => 'author-id,page-id,status,title,image,description,d_n_t,t_n_d',
            'search-type' => 'fulltext',
            'fulltext-mode' => 'IN BOOLEAN MODE',
            'search' => array('title'=>$search,'keywords'=>$search),
            'order' => $order,
            'sort' => $sort,
            'current-page' => $current_page
        );
        $data = $this->common_model->search_model($search_data);

        $this->pagination['url'] = base_url()."editor/admin/articles/$search/$order/$sort";
        $this->pagination['current'] = $current_page;
        $this->pagination['results'] = $data['results'];

        $data['config']  = $this->pagination;
        if(empty($data['rows']))
        {
            $this->message(
                array
                (
                    'title'   => 'No Search Results',
                    'message' => 'No article exists with this search criteria. Please try again.',
                    'action'  => '<input type="submit" class="button" value="Dashboard" onclick="go(\''.base_url().'editor/admin/\');">'.
                                 '<input type="submit" class="button" value="Back" onclick="goBack();">'
                ));
            return;
        }
        $data['uploadir'] = $this->config->item('uploadir');
        $data['title'] = 'Articles';
        $this->load->view('header-editor', $data);
        $this->load->view('editor-page-results');
        $this->load->view('footer-editor');
    }
    protected function page_preview($title=null,$page_id=null,$backbutton=null)
    {
        $this->editor_model->authenticate();
        $page = $this->common_model->page_preview_model($page_id,'editor',$backbutton);
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
        $this->editor_model->authenticate();
        $data = $this->editor_model->page_publish_status_change_model($page_id,$status,'editor');
        die($data);
    }
    protected function page_delete($page_id)
    {
        $this->editor_model->authenticate();
        $data = $this->common_model->page_delete_model($page_id);
        // data contains JSON string
        die($data);
    }
    /* -- Filebrowser --*/
    protected function filebrowser($author_id)
    {
        $this->editor_model->authenticate();
        $data = $this->common_model->filebrowser_model($author_id);
        $data['author_id'] = $author_id;
        $data['mod'] = 'editor';
        $this->load->view('common-filebrowser',$data);
    }
    protected function image_editor($author_id,$image)
    {
        $this->editor_model->authenticate();

        $data = $this->common_model->image_editor_model($author_id,$image);
        if($data['status']===true)
        {
            $this->filebrowser($author_id);
            return;
        }

        $data['author_id'] = $author_id;
        $data['mod'] = 'editor';
        $data['image'] = $image;
        $this->load->view('common-image-editor',$data);
    }
    protected function image_replace($author_id,$image)
    {
        $this->editor_model->authenticate();

        $data = $this->common_model->image_replace_model($author_id,$image);
        if($data['status']===true)
        {
            $this->filebrowser($author_id);
            return;
        }

        $data['author_id'] = $author_id;
        $data['image'] = $image;
        $data['mod'] = 'editor';
        $this->load->view('common-image-replace', $data);
    }
    /* -- Messaging -- */
    public function error404()
    {
        $this->message(
            array
            (
                'title'		=> 'Page Not Found .. Error 404!',
                'message'	=> 'Sorry! The page you are looking for doesn\'t exist. Check the URL and retry.',
                'action'	=> '<input onclick="go(\''.base_url().'editor/admin/\')" type="submit" class="button" value="dashboard"></a>'.
                               '<input onclick="goBack()" type="submit" class="button" value="Back"></a>'
            ));
    }
    protected function message($data)
    {
        $this->editor_model->authenticate();
        $this->load->view('header-editor',$data);
        $this->load->view('message');
        $this->load->view('footer-editor');
    }
    /* -- Various -- */
    protected function settings()
    {
        $this->editor_model->authenticate();
        $data = $this->editor_model->settings_model();

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

        $data['title'] = 'Edit Settings';
        $this->load->view('header-editor',$data);
        $this->load->view('editor-settings');
        $this->load->view('footer-editor');
    }
    protected function about()
    {
        $this->editor_model->authenticate();
        $data = $this->editor_model->about_model();

        if(isset($data['status']))
        {
            $this->message
           (
               array
               (
                   'title'      => $data['title'],
                   'message'    => $data['message'],
                   'action'     => $data['action'],
               )
           );
           return;
        }

        $data['title'] = 'About';
        $data['lickey'] = $data['lic-key'];
        $this->load->view('header-editor',$data);
        $this->load->view('editor-about');
        $this->load->view('footer-editor');
    }
    protected function update_version()
    {
        $data['title'] = 'About';
        $this->load->view('header-editor',$data);
        $this->load->view('editor-version-update');
        $this->load->view('footer-editor');
    }
    protected function combine_js()
    {
        $this->editor_model->authenticate();
        $jsFiles =
               'include/plugins/jquery.js,
                include/plugins/mmenu/jquery.mmenu.js,
                include/plugins/dialog/dialog.min.js,
                include/javascripts.dev.min.js';

        $files = explode(',',$jsFiles);
        $data = null;
        foreach ($files as $file)
        {
            $data.= file_get_contents(trim($file));
        }
        file_put_contents('include/javascripts.js',$data);
        echo 'Done ...';
    }
    protected function combine_css()
    {
        $this->editor_model->authenticate();
        $cssFiles =
            'include/styles.css,
             include/plugins/dialog/dialog.min.css';

        $files = explode(',',$cssFiles);
        $data = null;

        foreach ($files as $file)
        {
            $data.= file_get_contents(trim($file));
        }
        file_put_contents('include/combined.css',$data);
        echo 'Done ...';
    }
    protected function test()
    {
        $email['subject'] = 'Testing Email setup';
        $email['message'] = 'Hello World';
        $email['from'] = 'sinermme@gmail.com';
        $email['to'] = 'sinermme@gmail.com';
        $this->common_model->sendmail($email);
    }

}
?>