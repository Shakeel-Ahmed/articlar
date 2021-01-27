<?php

	class Author_model extends CI_Model {
		public function __construct()
		{
            $this->page_title_image_upload = array
            (
                'field'             => 'page_title_picture',
                'upload_path'       => $this->config->item('uploadir').'page-title-pictures',
                'allowed_types'     => array('jpeg','jpg','png','gif','svg'),
                'file_ext_tolower'  => TRUE,
                'thumb_width'       => 360,
                'thumb_quality'     => 60,
                'thumb_prefix'      => 'tmb_',
                'tiny_width'       => 80,
                'tiny_quality'     => 60,
                'tiny_prefix'      => 'tiny_',
                'image_width'       => 1280,
                'image_quality'     => 75
            );
		}
		public function home_model()
        {
            $selection  = 'SELECT `page-id`,`title`,`status` FROM `'.$this->config->item('DBTPage').'` WHERE `author-id` = "'.$_SESSION['author-id'].'" ';
            $latest     = $this->db->query($selection.'ORDER BY `d_n_t` DESC LIMIT 0,5')->result_array();
            $unpublish  = $this->db->query($selection.'AND `status` = 2 ORDER BY `d_n_t` DESC LIMIT 0,5')->result_array();
            $review     = $this->db->query($selection.'AND `status` = 3 ORDER BY `d_n_t` DESC LIMIT 0,5')->result_array();
            $publish    = $this->db->query($selection.'AND `status` = 4 ORDER BY `d_n_t` DESC LIMIT 0,5')->result_array();
            return array('latest'=>$latest,'review'=>$review,'publish'=>$publish,'unpublish'=>$unpublish);
        }
        public function page_create_model()
		{
			if(ptrig('trigger','gobaby'))
			{
                $page_id = ranum(12);
				if(!$this->input->post('page_headings')) return false;
                $this->page_title_image_upload['file_name'] = $page_id;
                if (is_uploaded_file($_FILES[$this->page_title_image_upload['field']]['tmp_name']))
                {
                    $filename = $this->common_model->image_uploader($this->page_title_image_upload);
                    // check if there is upload error
                    $upload_error = $this->upload->display_errors();
                    if ($upload_error) return array
                    (
                        'status' => false,
                        'title' => 'Image Upload Error',
                        'message' => $upload_error,
                        'action' => '<input type="submit" class="button" value="Dashboard" onclick="go(\'' . base_url() . 'author/admin/\');"><input type="submit" class="button" value="Back" onclick="goBack();">'
                    );
                }
                else $filename = null;

                $insert = array
				(
					'page-id' => $page_id,
					'author-id' => $_SESSION['author-id'],
					'title' => trim($this->input->post('page_headings')),
					'keywords' => $this->input->post('page_seo_keywords'),
					'description' => $this->input->post('page_details'),
					'header' => $this->input->post('page_header_contents'),
					'body' => str_replace('../../','../../../../',$this->input->post('page_contents')),
					'footer' => $this->input->post('page_footer_contents'),
					'image' => $filename
				);
				if($this->db->insert($this->config->item('DBTPage'),$insert)) return array
                (
                    'status'    => true,
                    'title'		=> 'Article Created',
                    'message'	=> 'The article has been created successfully. You need to send the article to The Editor to publish it.',
                    'action'	=> '<input type="submit" class="button" value="Dashboard" onclick="go(\''.base_url().'author/admin/\');">'.
                                   '<input type="submit" class="button" value="Preview" onclick="go(\''.base_url().'author/admin/view-article/'.slug($insert['title']).'/'.$insert['page-id'].'/noback\');">',
                );
			}
			$styles = $this->db->select('theme-id')->get_where($this->config->item('DBTTheme'), array('status' => 'active'));

            $theme_data = $this->db->select('theme-id,styles')->get_where($this->config->item('DBTTheme'), array('status' => 'active'))->row_array();
            $css_files = explode(',',$theme_data['styles']);
            foreach($css_files as $css_file)
            {
                $file[] = base_url().'themes/'.$theme_data['theme-id'].'/'.trim($css_file);
            }
            return array('styles'=>$styles->row_array(),'status'=>null,'content_css'=>implode("','",$file));
		}
		public function page_publisher_model($page_id)
		{
            $status_sql = "SELECT `status` FROM {$this->config->item('DBTPage')} WHERE `page-id` = '$page_id'";
            $status_qry = $this->db->query($status_sql);
            $status_row = $status_qry->row_array();
            /* send to review if under progress */
            if($status_row['status'] == 1) $update = array('status'=>'3');
            /* send to under progress if under review */
            if($status_row['status'] == 3) $update = array('status'=>'1');
            /* publish if un-published */
		    if($status_row['status'] == 2) $update = array('status'=>'4');
		    /* un-publishing if published */
            if($status_row['status'] == 4) $update = array('status'=>'2');
            /* homepage protection */
            if($status_row['status'] == 5)
            {
                return array
                (
                    'process' => 'success',
                    'status'  => $status_row['status']
                );
            }
            $this->db->set($update);
            $this->db->where('page-id',$page_id);
            if ($this->db->update($this->config->item('DBTPage')))
            {
                return array
                (
                    'process' => 'success',
                    'status'  => $update['status']
                );
            }
            else return false;
		}
		public function page_results_model($search,$order,$sort,$current_page)
		{
            $url['date']   = '/'.$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/created/standard/'.$this->uri->segment(7);
            $url['update'] = '/'.$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/updated/standard/'.$this->uri->segment(7);
            $url['views']  = '/'.$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/views/standard/'.$this->uri->segment(7);

            if($this->uri->segment(6) == 'standard') $url['sort'] = '/'.$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5).'/reverse/'.$this->uri->segment(7);
            else $url['sort'] = '/'.$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5).'/standard/'.$this->uri->segment(7);

            switch($order)
            {
                case 'created' : $order = 'd_n_t'; break;
                case 'updated' : $order = 't_n_d'; break;
                case 'views'   : $order = 'views'; break;
            }
		    switch($sort)
            {
                case 'standard'  : $sort = 'DESC'; break;
                case 'reverse'   : $sort = 'ASC'; break;
                default : $sort = 'DESC';
            }

		    if($search == 'search' or !$search) $searchSQL = null;
            else $searchSQL = " WHERE MATCH (`{$this->config->item('DBTPage')}`.`keywords`) AGAINST ('{$search}' IN BOOLEAN MODE) OR MATCH (`{$this->config->item('DBTPage')}`.`title`) AGAINST ('{$search}' IN BOOLEAN MODE)";

            $sql_result_count   = "SELECT null FROM `{$this->config->item('DBTPage')}` $search";

            $showFrom   = ($current_page * $this->config->item('perpage'))-$this->config->item('perpage');
            $limits     = "LIMIT $showFrom,{$this->config->item('perpage')}";
            if($order) $order = "ORDER BY `$order` $sort";

            $sql = "SELECT `page-id`,`status`,`title`,`image`,`description`,`views`,`d_n_t`,`t_n_d` FROM `{$this->config->item('DBTPage')}` $searchSQL $order $limits";
            return array('rows'=>$this->db->query($sql)->result_array(),'results'=>$this->db->query($sql_result_count)->num_rows(),'url'=>$url);
        }
        public function login_model()
		{
			if(ptrig('trigger','gobaby'))
			{
				$MD5login = $this->db->select('author-id,hash')->get_where($this->config->item('DBTAuthorLogin'),array('login'=>md5(strtolower($this->input->post('author_name')))));
				$login = $MD5login->row_array();
				if(password_verify($this->input->post('author_secret'),$login['hash'])==true)
				{
					$_SESSION['author-id'] = $login['author-id'];
					$_SESSION['password-author'] = $login['hash'];

                    if($this->input->post('remember') == 'true')
                    {
                        $duration = time() + 31556926;
                        setcookie('author-id', $_SESSION['author-id'], $duration, '/');
                        setcookie('password-author', $_SESSION['password-author'], $duration, '/');
                    }
                    header('LOCATION:'.base_url().'author/admin');
				}
				else
				{
					$this->common_model->message(
					array
					(
						'title'		=> 'Access Denied',
						'message'	=> 'Incorrect login details, please check your login details and retry.',
						'action'	=> '<a href="'.base_url().'author/admin/enter"><input type="submit" class="button" value="re-try"></a>'
					));
				}
			}
		}
		public function login_recover_model()
        {
            if(ptrig('trigger','goBabyRecoverPassword'))
            {
                $to = $email = strtolower($this->input->post('recover_email'));
                $tries = $this->db->query('SELECT null FROM `'.$this->config->item('DBTRecover').'` WHERE `email` = "'.$email.'" AND `expiry` >= NOW() - INTERVAL 1 DAY')->num_rows();
                if($tries >= 3) return array
                (
                    'status' => false,
                    'title'=>'Multiple Attempts',
                    'message' => 'Our records show there are multiple failed attempts to recover login details on this account. To protect this account, the system is temporarily freezing password recovery for 24 hours. You may contact The Editor to grant you access.',
                    'button' => null
                );
                $retry_button = '<a href="'.$_SERVER['REQUEST_URI'].'"><input type="submit" class="button" value="Try Again"></a>';

                $author = $this->db->query('SELECT `name` FROM `'.$this->config->item('DBTAuthor').'` WHERE `email` = "'.$email.'"');
                if($author->num_rows() == 0) return array
                (
                    'status' => false,
                    'title'=>'Unable to recover',
                    'message' => 'Sorry! The email you provided does not exist in our database for registered authors. Please check your email and try again.',
                    'button' => $retry_button
                );
                else
                {
                    $token = ranum(32);
                    $minutes = 10;
                    $expiry = new DateTime();
                    $expiry->add(new DateInterval("PT{$minutes}M"));
                    $insert = array
                    (
                        'token' =>  $token,
                        'email' =>  $email,
                        'expiry'=> $expiry->format('Y-m-d H:i:sP')
                    );
                    if($this->db->insert($this->config->item('DBTRecover'),$insert))
                    {


                        $data = $this->db->query('SELECT `name` FROM `'.$this->config->item('DBTSettings').'`');

                        $recovery_message = '<p>Hi '.$author->row_array()['name'].'!</p><p>You have requested for the password recovery for your <strong>'.$data->row_array()['name'].'</strong> account. Please click the following link to reset your password.</p>'.
                                            '<p><a href="'.base_url().'author/admin/reset-password/'.$token.'"><h3 style="text-align: center;">Reset Password</h3></a></p>'.
                                            '<p>Or copy paste the following URL into your browser</p><p>'.base_url().'author/admin/reset-password/'.$token.
                                            '<p>If you did not request for password reset then ignore this email.</p><p>Thank You!</p>';
                        $recovery_email['subject'] = $author->row_array()['name'].' your password reset email for '.$data->row_array()['name'];
                        $recovery_email['message'] = $recovery_message;
                        $recovery_email['from'] = 'noreply@'.$_SERVER['HTTP_HOST'];
                        $recovery_email['to'] = $to;
                        $sent = @$this->common_model->sendmail($recovery_email);


                        $from = 'noreply@'.basename(base_url());
                        $sub  = $author->row_array()['name'].' recover your login through this email.';
                        $msg  = '<p>Hi!</p><p>You have requested for the password recovery for your account. Please click the following link to reset your password.</p><h4 style="text-align: center;"><a href="'.base_url().'author/admin/reset-password/'.$token.'">CLICK TO RESET YOUR PASSWORD</a></h4>'.
                                '<p>If you did not request for a password reset for your account then ignore this email.</p><p>Thank You!</p>';

                        //die($msg);
                        $this->load->library('email');
                        $this->email->from($from);
                        $this->email->to($to);
                        $this->email->subject($sub);
                        $this->email->message($msg);
                        //$email_status = $this->email->send();
                        $email_status = true;
                        if($email_status) return array
                        (
                            'status' => true,
                            'title'=>'Password Recovery Email Sent',
                            'message' => 'Please check your email address <span class="text-color-highlight">'.$email.'</span> inbox to proceed further. Check in your email spam folder in case it is not in the inbox. You have ten minutes to reset your password.',
                            'button' => $retry_button
                        );
                        else return array('status' => false,'email'=>$email,'message'=>'Unable to send the email due to mailserver mulfunction.','button'=>null);
                    }
                }
            }
        }
        public function login_reset_model($token)
        {
            $data = $this->db->query('SELECT * FROM `'.$this->config->item('DBTRecover').'` WHERE `token` = "'.$token.'"');
            if($data->num_rows() === 0) return array
            (
                'status'    => false,
                'title'     => 'Invalid Request',
                'message'   => 'Invalid password reset request. No such request has been made.',
                'button'    => null
            );

            $data = $data->row_array();

            $author = $this->db->query('SELECT `author-id` FROM `'.$this->config->item('DBTAuthor').'` WHERE `email` = "'.$data['email'].'"')->row_array();

            $now = new DateTime();
            $expiry = new DateTime($data['expiry']);

            if($now > $expiry) return array
            (
                'status'    => false,
                'title'     =>'Time Expired',
                'message'   => 'Sorry! You did not reset the password within the time restriction. You may need to re-request for your password recovery.',
                'button'    => '<a href="/author/admin/recover-password"><input type="submit" class="button" value="Request"></a>'
            );

            if(ptrig('trigger','goBabyResetPassword'))
            {
                $update_password = array
                (
                    'hash'  => password_hash($this->input->post('reset_password'), PASSWORD_BCRYPT)
                );
                if($this->db->set($update_password)->where('author-id',$author['author-id'])->update($this->config->item('DBTAuthorLogin')))
                {
                    $this->db->delete($this->config->item('DBTRecover'),array('token' => $token));
                    return
                    [
                        'status'    => true,
                        'title'     => 'Password Reset Success',
                        'message'   => 'Password has been changed.',
                        'button'    => '<a href="/author/admin/enter"><input type="submit" class="button" value="Login"></a>'
                    ];
                }
                else return
                    [
                        'status'    => false,
                        'title'     => 'Password Reset Failed',
                        'message'   => 'Unable to rest your password due to database server error. Please try little later.',
                        'button'    => '<a href="/author/admin/enter"><input type="submit" class="button" value="Login"></a>'
                    ];
            }
        }
        public function authenticate()
		{
            if((!isset($_SESSION['author-id']) or !isset($_SESSION['password-author'])) and (!isset($_COOKIE['author-id']) or !isset($_COOKIE['password-author'])))
            {
                $this->logout();
                header('LOCATION:'.base_url().'author/admin/enter');
            }
		    if(isset($_COOKIE['author-id']) and isset($_COOKIE['password-author']))
            {
                $_SESSION['author-id'] = $_COOKIE['author-id'];
                $_SESSION['password-author'] = $_COOKIE['password-author'];
            }
            $data  = $this->db->select('hash')->get_where($this->config->item('DBTAuthorLogin'),array('author-id'=>$_SESSION['author-id']));
			$login = $data->row_array();
			if(!$login['hash'] or $login['hash']!=$_SESSION['password-author'])
			{
				$this->logout();
				header('LOCATION:'.base_url().'author/admin/enter');
			}
		}
		public function logout()
		{
            unset($_SESSION['author-id'],$_SESSION['password-author']);
            setcookie('author-id',"",time()-3600,'/');
            setcookie('password-author',"",time()-3600,'/','',false,true);
            header('LOCATION:'.base_url());
		}
	}
?>