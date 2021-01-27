<?php

class Common_model extends CI_Model {

    public function __construct()
    {
        $this->image_upload_settings = array
        (
            'field'             => 'page_title_picture',
            'upload_path'       => $this->config->item('uploadir').'page-title-pictures',
            'allowed_types'     => array('jpeg','jpg','png','gif','svg'),
            'file_ext_tolower'  => TRUE,
            'thumb_width'       => 640,
            'thumb_quality'     => 50,
            'thumb_prefix'      => 'tmb_',
            'tiny_width'       => 320,
            'tiny_quality'     => 60,
            'tiny_prefix'      => 'tiny_',
            'image_width'       => 1920,
            'image_quality'     => 50
        );
    }
    public function search_model($search_data)
    {
        $url['date']   = base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/date/standard/'.$this->uri->segment(7);
        $url['update'] = base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/updated/standard/'.$this->uri->segment(7);
        $url['views']  = base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/views/standard/'.$this->uri->segment(7);

        if($this->uri->segment(6) == 'standard') $url['sort'] = base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5).'/reverse/'.$this->uri->segment(7);
        else $url['sort'] = base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5).'/standard/'.$this->uri->segment(7);

        switch($search_data['order'])
        {
            case 'date' : $order = 'd_n_t'; break;
            case 'updated' : $order = 't_n_d'; break;
            case 'views'   : $order = 'views'; break;
            default : $order = null;
        }
        switch($search_data['sort'])
        {
            case 'latest'   : $sort = 'DESC'; break;
            case 'standard' : $sort = 'DESC'; break;
            case 'oldest'   : $sort = 'ASC'; break;
            case 'reverse'  : $sort = 'ASC'; break;
            default         : $sort = 'DESC';
        }

        if(is_array($search_data['search']) and $search_data['search-type'] != 'fulltext')
        {
            foreach ($search_data['search'] as $db_column => $search_value)
            {
                if(!$search_value or $search_value=='datasearch') continue;
                $search_string[] =  "`$db_column` LIKE '$search_value'";
            }
        }
        elseif(is_array($search_data['search']) and $search_data['search-type'] == 'fulltext')
        {
            foreach ($search_data['search'] as $db_column => $search_value)
            {
                if(!$search_value or $search_value=='datasearch') continue;
                $search_string[] =  "`$db_column`";
            }
        }
        if(!empty($search_string)) $searchSQL = "WHERE MATCH (".implode(',',$search_string).") AGAINST ('$search_value' {$search_data['fulltext-mode']})";
        else $searchSQL = null;

        if(is_array($search_data['restrict']) and $search_data['restrict'] != null)
        {
            foreach($search_data['restrict'] as $id_column)
            {
                foreach($id_column as $directive_key => $directive_val)
                {
                    $id_sql[] = '`'.$directive_key.'` '.$directive_val[key($directive_val)]." '".key($directive_val)."'";
                }
            }
            $id_restriction = implode(' AND ',$id_sql);
        }
        else $id_restriction = null;

        if($searchSQL and $id_restriction) $searchSQL = $searchSQL." AND $id_restriction";
        elseif(!$searchSQL and $id_restriction) $searchSQL = "WHERE $id_restriction";

        $sql_result_count   = "SELECT null FROM `{$search_data['database-table']}` $searchSQL";

        $showFrom   = ($search_data['current-page'] * $this->config->item('perpage'))-$this->config->item('perpage');
        $limits     = "LIMIT $showFrom,{$this->config->item('perpage')}";
        if($order) $order = "ORDER BY `$order` $sort";

        if(!$search_data['select']) $selection = '*';
        else
        {
            $selection = explode(',',$search_data['select']);
            $selection = '`'.implode('`,`',$selection).'`';
        }
        $sql = "SELECT $selection FROM `{$search_data['database-table']}` $searchSQL $order $limits";
        return array('rows'=>$this->db->query($sql)->result_array(),'results'=>$this->db->query($sql_result_count)->num_rows(),'url'=>$url);
    }
    public function generate_html($page_id)
    {
        if(!$page_id)
        {
            $page_count = $this->db->query("SELECT null FROM `{$this->config->item('DBTPage')}` WHERE `status` = 5")->num_rows();
            if($page_count ===0) die
            (
                '<div style="font-family: Arial; text-align: center; padding-top: 50px;"><img src="'.base_url().'images/blogger-text.svg" style="max-width: 360px;"><h1 style="text-align: center;">Welcome to Articlar</h1>'.
                '<p>Let\'s start making something beautiful</p>'
            );
        }
        $settings = $this->db->query("SELECT `name`,`email`,`address`,`site-logo`,`default-image` FROM `{$this->config->item('DBTSettings')}` WHERE `settings-id` = '1'")->row_array();
        $template = $this->db->select('theme-id,template,template-blog,back,backward,first,next,forward,last,seprator')->get_where($this->config->item('DBTTheme'),array('status'=>'active'))->row_array();
        if(!$template) die
        (
            '<div style="font-family: Arial; text-align: center; padding-top: 50px;"><img src="'.base_url().'images/blogger-text.svg" style="max-width: 360px;"><h1 style="text-align: center;">Welcome to Articlar</h1>'.
            '<p>OOPS! You forgot to install a theme or maybe forgot to activate it.</p>'
        );
        if($page_id == 'results')
        {
            $values = $this->db->query("SELECT * FROM {$this->config->item('DBTPage')} WHERE `keywords` = '[art-results]' AND `status` > '3'")->row_array();
            if(!$values) $values =
                [
                    'keywords'    => 'search results',
                    'description' => 'Search results',
                    'title'       => 'Search Results',
                    'header'      => null,
                    'footer'      => null,
                    'body'        => '<div class="container py-5">[art-blog]</div>',
                    'status'      => 4,
                ];
        }
        else
        {
            $values = $this->db->get_where($this->config->item('DBTPage'),['page-id'=>$page_id]);
            $values = $values->row_array();
            if(!$values) return false;
        }

        $values['body'] = str_replace(['[art-404]','[art-noresult]','[art-results]','<p>[deco-border-end]</p>','[deco-border-end]','<p>[column-end]</p>','[column-end]','<p>[container-end]</p>','[container-end]','[add-columns-here]','<p>[jumbotron-end]</p>','[jumbotron-end]'],'',$values['body']);
        if(strpos($values['body'],'[art-blog]')!==false)
        {
            $values['body'] = str_replace('[art-blog]','<div class="row">[art-blog]</div>',$values['body']);
            if($this->uri->segment(1)) $url = uri_string();
            else $url = base_url().slug($values['title']).'/'.$page_id.'/datasearch/1';

            list($segments,$parameters) = explode($page_id,$url);
            $xurl = substr($parameters,1,strlen($parameters));
            if(substr_count($parameters,'/')==1)
            {
                list($search) = explode('/',$xurl);
                $current_page = 1;
            }
            elseif($xurl===false)
            {
                $search = 'datasearch';
                $current_page = 1;
            }
            else
            {
                $search = $this->uri->segment(3);
                $current_page = $this->uri->segment(4);
            }

            if(!$search) $search = 'datasearch';
            if(!$current_page) $current_page = 1;

            $search = slug($search);
            $search_data = array
            (
                'database-table' => $this->config->item('DBTPage'),
                'restrict' => [['status'=>['4'=>'=']],['keywords'=>['[art-404]'=>'!=']],['keywords'=>['[art-results]'=>'!=']],['keywords'=>['[art-noresults]'=>'!=']]],
                'select' => 'author-id,page-id,status,title,image,description,d_n_t,t_n_d',
                'search-type' => 'fulltext',
                'fulltext-mode' => 'IN BOOLEAN MODE',
                'search' => array('title'=>$search,'keywords'=>$search),
                'order' => 'd_n_t',
                'sort' => 'desc',
                'current-page' => $current_page
            );
            $data = $this->search_model($search_data);
            if($data['results']!==0 and count($data['rows'])!==0)
            {
                $rcount = 1000;
                foreach($data['rows'] as $row)
                {
                    $blog_template = $template['template-blog'];
                    if(!$row['image']) $row['image'] = $settings['default-image'];
                    $blog_data = array
                    (
                        'title' => $row['title'],
                        'description' => $row['description'],
                        'image' => base_url().$this->config->item('uploadir').'page-title-pictures/'.$row['image'],
                        'thumb' => base_url().$this->config->item('uploadir').'page-title-pictures/tmb_'.$row['image'],
                        'tiny' => base_url().$this->config->item('uploadir').'page-title-pictures/tiny_'.$row['image'],
                        'link' => base_url().slug($row['title']).'/'.$row['page-id'],
                        'created' => dateFormat($row['d_n_t'],'d M Y'),
                        'updated' => dateFormat($row['t_n_d'],'d M Y'),
                        'count' => $rcount
                    );
                    foreach ($blog_data as $key => $value)
                    {
                        $blog_template = str_replace('[art-row-'.$key.']',$value,$blog_template);
                    }
                    $blog_row[] = $blog_template;
                    $rcount+=100;
                }

                if(stripos($segments,base_url())!==false) $pagi_url = $segments.$page_id.'/'.$search;
                else $pagi_url = base_url().$segments.$page_id.'/'.$search;

                $pagination = array
                (
                    'url'       => $pagi_url,
                    'results'   => $data['results'],
                    'current'   => $current_page,
                    'perpage'   => $this->config->item('perpage'),
                    'segsize'   => $this->config->item('segsize'),
                    'back'      => $template['back'],
                    'backward'  => $template['backward'],
                    'first'     => $template['first'],
                    'next'      => $template['next'],
                    'forward'   => $template['forward'],
                    'last'      => $template['last'],
                    'seprator'  => $template['seprator'],
                    'class'     => 'art-pagi-link-highlight',
                    'return'    => true
                );

                $articles = implode('',$blog_row);
                $articles .= '<div class="col-12"><div class="art-pagination">'.pagi($pagination).'</div></div>';
            }
            else
            {
                $noresult = $this->db->query("SELECT `body` FROM `{$this->config->item('DBTPage')}` WHERE `keywords` = '[art-noresults]' AND `status` >= '4'")->row_array();
                if($noresult) $articles = str_replace(['[art-404]','[art-noresult]','[art-results]','<p>[deco-border-end]</p>','[column-end]','<p>[container-end]</p>','[add-columns-here]','<p>[jumbotron-end]</p>'],'',$noresult['body']);
                else $articles = '<div style="text-align:center; min-width: 100%;"><h3>Sorry! No Articles Found</h3><hr/><p style="text-align: center;">Sorry! Your search did not fetch any result. Please try again with different search criteria.<h4>Thank You!</h4></p></div>';
            }
            $values['body'] = str_replace('[art-blog]',$articles,$values['body']);
        }

        $replace = array
        (
            'keywords'    => $values['keywords'],
            'description' => $values['description'],
            'title'       => $values['title'],
            'header'      => $values['header'],
            'footer'      => $values['footer'],
            'contents'    => $values['body'],
            'path'        => base_url('themes/'.$template['theme-id'].'/'),
            'home'        => base_url(),
            'search'      => base_url().'search/results/',
            'address'     => $settings['address'],
            'sitelogo'    => base_url().$this->config->item('uploadir').'page-title-pictures/'.$settings['site-logo'],
            'facebook'    => 'https://www.facebook.com/sharer/sharer.php?u='.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
            'twitter'     => 'https://twitter.com/intent/tweet?url='.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'&text='.urlencode($values['description']),
            'linkedin'    => 'https://www.linkedin.com/shareArticle?mini=true&url='.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'&title='.urlencode($values['title']).'&summary='.urlencode($values['description'])
        );
        /* injecting search javascript function */
        $search_javascript = '<script>function artSearch(e,i){var a=document.getElementById(i).value.toString().toLowerCase().replace(/\s+/g,"-").replace(/[^\w\-]+/g,"").replace(/\-\-+/g,"-").replace(/^-+/,"").replace(/-+$/,"");if(""==a)return!1;window.location.href=e+a}</script></head>';
        $template['template'] = str_replace('</head>',$search_javascript,$template['template']);

        $body = $template['template'];
        foreach ($replace as $tag=>$value) $body = str_replace("[art-$tag]",$value,$body);
        $body = str_replace('\]',']',$body);

        $body = str_replace('../../../../',base_url(),$body);
        return array('body'=>$body,'status'=>true,'title'=>$values['title'],'page-status'=>$values['status']);
    }
    public function page_delete_model($page_id)
    {
        if(!$page_id) return '{"status":"fail","title":"Error","message":"Page ID is missing in request. Provide page id to complete the process."}';
        $files = $this->db->query("SELECT `page-id`,`image`,`title` FROM {$this->config->item('DBTPage')} WHERE `page-id` = '$page_id'")->row_array();

        # image file delete code
        $location = $this->config->item('uploadir').'page-title-pictures/';
        $image_files = array($location.$files['image'], $location.'tmb_'.$files['image'],$location.'tiny_'.$files['image']);

        foreach($image_files as $image_file)
        {
            if(is_file($image_file) and file_exists($image_file))
            {
                $unlink = unlink($image_file);
                if(!$unlink) return '{"status":"fail","title":"Error","message":"Unable to delete the page title image file. Please try again."}';
            }
        }

        $del_page = $this->db->delete($this->config->item('DBTPage'), array('page-id'=>$page_id));
        $del_log  = $this->db->delete($this->config->item('DBTLog'),  array('page-id'=>$page_id));

        if($del_page and $del_log) return '{"status":"success","title":"null","message":"null"}';
        else return '{"status":"fail","title":"Error","message":"Unable to complete the request due to a database error. Please try again."}';
    }
    public function page_edit_model($page_id,$mod=null)
    {
        if(ptrig('trigger','gobaby'))
        {
            if(!$this->input->post('page_headings')) return false;
            if($this->input->post('status')==5)
            {
                $this->db->set(['status' => '4']);
                $this->db->where(['status'=>'5']);
                $this->db->update($this->config->item('DBTPage'));
            }
            $update = array
            (
                'title' => trim($this->input->post('page_headings')),
                'keywords' => $this->input->post('page_seo_keywords'),
                'header' => $this->input->post('page_header_contents'),
                'description' => $this->input->post('page_details'),
                'body' => $this->input->post('page_contents'),
                'footer' => $this->input->post('page_footer_contents'),
                'status' => $this->input->post('status'),
            );
            if(is_uploaded_file($_FILES['page_title_picture']['tmp_name']))
            {
                $uploadpath = $this->image_upload_settings['upload_path'];

                $image = $uploadpath . '/' . $this->input->post('current_title_picture');
                $thumb = $uploadpath . '/tmb_' . $this->input->post('current_title_picture');
                $tiny  = $uploadpath . '/tiny_' . $this->input->post('current_title_picture');

                if(file_exists($image)) unlink($image);
                if(file_exists($thumb)) unlink($thumb);
                if(file_exists($tiny)) unlink($tiny);

                $this->image_upload_settings['file_name'] = $page_id;
                $update['image'] = $this->image_uploader($this->image_upload_settings);

                $upload_error = $this->upload->display_errors();
                if($upload_error) return array
                (
                    'status'    => false,
                    'title'     => 'Unable to upload',
                    'message'   => $upload_error,
                    'action'    => '<input type="submit" class="button" value="Retry" onclick="go(\''.$_SERVER['REQUEST_URI'].'\')">'.
                        '<input type="submit" class="button" value="Dashboard" onclick="go(\'/editor/admin/\')">'
                );
            }
            $this->db->set($update);
            $this->db->where(array('page-id'=>$page_id));

            if($this->db->update($this->config->item('DBTPage')))
            {
                return array
                (
                    'status'    => true,
                    'title'     => 'Article Updated',
                    'message'   => 'The article has been successfully updated.',
                    'action'	=> null
                );
            }
        }

        $page = $this->db->query("SELECT * FROM {$this->config->item('DBTPage')} WHERE `page-id` = '$page_id'")->row_array();
        if(!$page) return false;

        if($mod == 'author' and $page['status'] > 2) return
            [
                'status'    => false,
                'title'     => 'Article Locked!',
                'message'   => 'Published, under review or homepage articles cannot be edited by the author.',
                'action'	=> null
            ];


        $theme_data = $this->db->select('theme-id,styles')->get_where($this->config->item('DBTTheme'), array('status' => 'active'))->row_array();
        $css_files = explode(',',$theme_data['styles']);
        foreach($css_files as $css_file)
        {
            $file[] = base_url().'themes/'.$theme_data['theme-id'].'/'.trim($css_file);
        }
        return array('row'=>$page,'content_css'=>implode("','",$file));
    }
    public function page_preview_model($page_id,$mod,$backbutton)
    {
        $page = $this->generate_html($page_id);
        if($page===false) return false;
        if(!$backbutton) $goback = '<i class="art-icon hover" onclick="goBack();">arrow_back</i>';
        else $goback = null;
        if($mod == 'author')
        {
            switch($page['status'])
            {
                case 1 : $delete_status = $page_id; break;
                case 2 : $delete_status = $page_id; break;
                case 3 : $delete_status = 'locked'; break;
                case 4 : $delete_status = 'locked'; break;
                case 5 : $delete_status = 'locked'; break;
            }
        }
        if($mod == 'editor')
        {
            switch($page['status'])
            {
                case 1 : $delete_status = $page_id; break;
                case 2 : $delete_status = $page_id; break;
                case 3 : $delete_status = $page_id; break;
                case 4 : $delete_status = $page_id; break;
                case 5 : $delete_status = 'locked'; break;
            }
        }
        $page = str_replace
        (
            '<body>',
            '
            </style>
            <link href="'.base_url('include/articlar-styles.css').'" rel="stylesheet" type="text/css">
            <link href="'.base_url('include/article-display-preview.css').'" rel="stylesheet" type="text/css">
            <script type="text/javascript" src="'.base_url().'include/javascripts.js'.'"></script>
            <body>
            <div class="display-page-floating-panel">
            <i class="art-icon hover" onclick="go(\''.base_url().$mod.'/admin/\');">dashboard</i>
            <i class="art-icon hover" onclick="go(\''.base_url().$mod.'/admin/edit-article/data/'.$page_id.'\');">edit</i>
            <i class="art-icon hover" onclick="deleteArticle(\''.$delete_status.'\',\''.base_url().$mod.'/admin/delete-article/\',\''.base_url().$mod.'/admin/\');">delete</i>'
            .$goback.
            '</div><div style="pointer-events: none;">',str_replace('</body>','</div></body>',$page['body']));
        return $page;
    }
    public function author_edit_model($author_id)
    {
        if (ptrig('trigger', 'gobaby'))
        {
            $row = $this->db->query("SELECT `picture`,`email` FROM `{$this->config->item('DBTAuthor')}` WHERE `author-id` = '$author_id'")->row_array();
            $update = array
            (
                'name'  => $this->input->post('author_profile_name'),
                'email' => $this->input->post('author_profile_email'),
                'about' => $this->input->post('author_profile_about'),
            );
            if(is_uploaded_file($_FILES['author_profile_picture']['tmp_name']))
            {
                if($row['picture'])
                {
                    $picture = $this->config->item('uploadir')."authors/$author_id/".$row['picture'];
                    $thumb   = $this->config->item('uploadir')."authors/$author_id/tmb_".$row['picture'];
                    $tiny    = $this->config->item('uploadir')."authors/$author_id/tiny_".$row['picture'];

                    if(file_exists($picture) and $row['picture']) unlink($picture);
                    if(file_exists($thumb) and $row['picture']) unlink($thumb);
                    if(file_exists($tiny) and $row['picture']) unlink($tiny);
                }

                $this->image_upload_settings['file_name'] = $author_id;
                $this->image_upload_settings['field'] = 'author_profile_picture';
                $this->image_upload_settings['upload_path'] = $this->config->item('uploadir')."authors/$author_id";

                $update['picture'] = $this->common_model->image_uploader($this->image_upload_settings);

                $upload_error = $this->upload->display_errors();
                if($upload_error) return array
                (
                    'status'    => false,
                    'title'     => 'Unable to upload',
                    'message'   => $upload_error,
                    'action'    =>  '<input type="submit" class="button" value="Re-try" onclick="go(\''.$_SERVER['REQUEST_URI'].'\')">'.
                                    '<input type="submit" class="button" value="Dashboard" onclick="go(\'/editor/admin/\')">'
                );
            }

            $login = md5(strtolower($this->input->post('author_profile_email')));

            # if login email already exists abondon further proceedings
            $login_chk = $this->db->query("SELECT null FROM `{$this->config->item('DBTAuthorLogin')}` WHERE `login` = '$login' AND `author-id` != '$author_id'")->num_rows();
            if($login_chk)
            {
                return
                    [
                        'status' => false,
                        'title' => 'Email Already Exists',
                        'message' => 'The email you have provided already associated with another account. Please use another email.',
                        'action' => '<input type="submit" class="button" value="Dashboard" onclick="go(\'' . base_url() . 'editor/admin/\');"><input type="submit" class="button" value="Back" onclick="goBack();">'
                    ];
            }

            $update_password = array
            (
                'login' => md5(trim(strtolower($this->input->post('author_profile_email')))),
                'hash'  => password_hash(trim($this->input->post('author_profile_pass')), PASSWORD_BCRYPT)
            );

            $this->db->set($update_password);
            $this->db->where('author-id',$author_id);
            $status['password'] = $this->db->update($this->config->item('DBTAuthorLogin'));

            $this->db->set($update);
            $this->db->where('author-id', $author_id);
            $status['profile'] = $this->db->update($this->config->item('DBTAuthor'));

            if($status['password'] and $status['profile']) return array
            (
                'status'    => true,
                'title'     => null,
                'message'   => null,
                'action'    => null
            );
            else return false;
        }
        $data = $this->db->get_where($this->config->item('DBTAuthor'), array('author-id' => $author_id))->row_array();
        if(!$data) return false;

        if($data['picture']) $data['picture'] = base_url().$this->config->item('uploadir').'authors/'.$data['author-id'].'/'.$data['picture'];
        else $data['picture'] = base_url().'images/person.svg';

        return array('row'=>$data);
    }
    public function filebrowser_model($author_id)
    {
        if(ptrig('picture'))
        {
            if
            (
                unlink($_POST['picture'])
                and unlink(str_replace('tmb_','',$_POST['picture']))
                and unlink(str_replace('tmb_','tiny_',$_POST['picture']))
            )
            die('true');
            else die('false');
        }
        if(ptrig('trigger','gobaby'))
        {
            if(is_uploaded_file($_FILES['gallery']['tmp_name'][0]))
            {
                $uploads = count($_FILES['gallery']['tmp_name'])-1;
                for($i=0;$i<=$uploads;$i++)
                {
                    $formfield = 'picture';
                    $_FILES[$formfield] = array
                    (
                        'name'      => $_FILES['gallery']['name'][$i],
                        'type'      => $_FILES['gallery']['type'][$i],
                        'tmp_name' 	=> $_FILES['gallery']['tmp_name'][$i],
                        'error'     => $_FILES['gallery']['error'][$i],
                        'size'      => $_FILES['gallery']['size'][$i]
                    );
                    $this->image_upload_settings['field'] = $formfield;
                    $this->image_upload_settings['upload_path'] = $this->config->item('uploadir').'authors/'.$author_id;
                    $this->image_upload_settings['file_name'] = ranum(16);
                    $this->common_model->image_uploader($this->image_upload_settings);
                }
            }
        }

        $pages = $this->db->query('SELECT `page-id`,`title` FROM `'.$this->config->item('DBTPage').'` WHERE `status` >= 4 ORDER BY `d_n_t` DESC');
        return array
        (
            'rows'  => $pages->result_array(),
        );
    }
    public function image_editor_model($author_id,$image)
    {
        $file = $this->config->item('uploadir').'authors/'.$author_id.'/'.$image;
        $tumb = $this->config->item('uploadir').'authors/'.$author_id.'/tmb_'.$image;
        $mime = image_type_to_mime_type(exif_imagetype($file));

        if(ptrig('trigger','goBabySaveImage'))
        {
            $data = base64_decode(str_replace("data:$mime;base64,",'',$_POST['image_carrier']));
            file_put_contents($file,$data);
            resizeGD($file,$tumb,$this->image_upload_settings['thumb_width'],$this->image_upload_settings['thumb_quality']);
            return array('status'=>true);
        }
        return array('status'=>null,'mime'=>$mime);

    }
    public function image_replace_model($author_id,$image)
    {
        $mime = image_type_to_mime_type(exif_imagetype($this->config->item('uploadir').'authors/'.$author_id.'/'.$image));
        if(ptrig('trigger','goBabyReplaceImage'))
        {
            $this->image_upload_settings['field'] = 'image_field';
            $this->image_upload_settings['overwrite'] = true;
            $this->image_upload_settings['upload_path'] = $this->config->item('uploadir').'authors/'.$author_id.'/';
            $this->image_upload_settings['file_name'] = $image;

            $this->common_model->image_uploader($this->image_upload_settings);
            $this->filebrowser_model($author_id);
            return array('status'=>true,'mime'=>$mime);
        }
        return array('status'=>null,'mime'=>$mime);

    }
    public function image_uploader($upload)
    {
        # IMAGE UPLOAD CODE
        $this->load->library('upload', $upload);
        $this->upload->do_upload($upload['field']);
        $upload_error = $this->upload->display_errors();

        if($upload_error) return array('status'=>false,'error'=>$upload_error);
        $file = $this->upload->data();

        # IMAGE RESIZE CODE
        $pinfo = pathinfo($file['file_name']);
        if($file['file_name'] and ($upload['thumb_width'] or $upload['image_width']))
        {
            $src_image = $upload['upload_path'].'/'.$file['file_name'];

            if(isset($upload['thumb_prefix'])) $tmb_image = $upload['upload_path'].'/'.$upload['thumb_prefix'].$file['file_name'];
            if(isset($upload['tiny_prefix'])) $tiny_image = $upload['upload_path'].'/'.$upload['tiny_prefix'].$file['file_name'];

            # SVG FILES DO NOT NEED RESIZING
            if($pinfo['extension'] != 'svg')
            {
                if (isset($upload['tiny_width'])) resizeGD($src_image, $tiny_image, $upload['tiny_width'], $upload['tiny_quality'],'image/jpeg');
                if (isset($upload['thumb_width'])) resizeGD($src_image, $tmb_image, $upload['thumb_width'], $upload['thumb_quality'],'image/jpeg');
                if (isset($upload['image_width'])) resizeGD($src_image, $src_image, $upload['image_width'], $upload['image_quality']);
            }
            # DUPLICATING SVG FILE AS THMB
            # TO KEEP CONSISTENCY WITH OTHER
            # FORMATS. THUMB FILES ARE NEEDED
            # TO MANAGE FILES PROPERLY
            elseif($pinfo['extension'] == 'svg' and $upload['thumb_width'])
            {
                copy($src_image,$tmb_image);
                copy($src_image,$tiny_image);
            }
        }
        return $file['file_name'];
    }
    public function sendmail($data)
    {
        $settings = $this->db->query("SELECT `name`,`email`,`address`,`site-logo` FROM `{$this->config->item('DBTSettings')}` WHERE `settings-id` = '1'")->row_array();
        $email = '<table align="center" cellpadding="15px" style="font-family: Arial; font-size: 14px; width: 640px;">
                                <tr style="text-align: center">
                                    <td>
                                    <p><img src="'.base_url().$this->config->item('uploadir').'page-title-pictures'.'/'.$settings['site-logo'].'" style="max-width:260px;"></p>
                                    <h3 style="text-align: center">'.$settings['name'].'</h3><hr/>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"><div style="min-height: 360px;">'.$data['message'].'</div></td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;"><hr/>'.$settings['address'].'</td>
                                </tr>
                            </table>';

        $this->load->library('email');
        $email_config =
            [
                'charset' => 'utf-8',
                'wordwrap' => TRUE,
                'mailtype' => 'html',
            ];
        $this->email->initialize($email_config);

        $this->email->from($data['from'], $settings['name']);
        $this->email->to($data['to']);
        $this->email->subject($data['subject']);
        $this->email->message($email);
        $status = @$this->email->send();

        if(!$status)
        {
            return
                [
                    'status'    => false,
                    'title'     => 'Mail Server Failed',
                    'message'   => 'Send email failed due to mail server or config error. Please try again.',
                    'action'    => null
                ];
        }
        else return
            [
                'status'    => true,
                'title'     => 'Email Sent',
                'message'   => 'The email has been sent successfully.',
                'action'    => null
            ];

    }
    public function message($data)
    {
        $this->load->view('header-blank',$data);
        $this->load->view('message');
        $this->load->view('footer-blank');
    }

}
?>