<?php

class Editor_model extends CI_Model
{

    public function __construct()
    {
        $this->image_upload_settings = array
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
        $selection      = 'SELECT `page-id`,`title`,`views` FROM `'.$this->config->item('DBTPage').'`';
        $art_viewed     = $this->db->query($selection.'WHERE `views` >= 1 ORDER BY `views` DESC LIMIT 0,5 ')->result_array();
        $art_review     = $this->db->query($selection.'WHERE `status` = 3 ORDER BY `d_n_t` DESC LIMIT 0,5')->result_array();

        $stat_total_articles    = $this->db->query('SELECT null FROM `'.$this->config->item('DBTPage').'`')->num_rows();
        $stat_total_authors     = $this->db->query('SELECT null FROM `'.$this->config->item('DBTAuthor').'`')->num_rows();
        $stat_pending_reviews   = $this->db->query('SELECT null FROM `'.$this->config->item('DBTPage').'` WHERE `status` = 3')->num_rows();
        $stat_overall_views     = $this->db->query('SELECT SUM(views) FROM `'.$this->config->item('DBTPage').'`')->row_array();
        $stat_last_article      = $this->db->query('SELECT `d_n_t` FROM `'.$this->config->item('DBTPage').'` ORDER BY `d_n_t` DESC')->row_array();

        $team = $this->db->query('SELECT `author-id`,`name`,`picture` FROM `'.$this->config->item('DBTAuthor').'` ORDER BY `views` DESC LIMIT 0,6')->result_array();

        return array
        (
            'viewed'=>$art_viewed,
            'review'=>$art_review,
            'stat_total_articles'   =>$stat_total_articles,
            'stat_total_authors'    =>$stat_total_authors,
            'stat_pending_reviews'  =>$stat_pending_reviews,
            'stat_overall_views'    =>$stat_overall_views['SUM(views)'],
            'stat_last_article'     =>$stat_last_article['d_n_t'],
            'team'                  =>$team,
        );
    }
    /* -- Authentication --*/
    public function authenticate()
    {
        if((!isset($_SESSION['editor-id']) or !isset($_SESSION['password-editor'])) and (!isset($_COOKIE['editor-id']) or !isset($_COOKIE['password-editor'])))
        {
            $this->logout();
            header('LOCATION:'.base_url().'editor/admin/enter');
        }
        if(isset($_COOKIE['editor-id']) and isset($_COOKIE['password-editor']))
        {
            $_SESSION['editor-id'] = $_COOKIE['editor-id'];
            $_SESSION['password-editor'] = $_COOKIE['password-editor'];
        }
        $data  = $this->db->select('hash')->get_where($this->config->item('DBTEditorLogin'),array('editor-id'=>$_SESSION['editor-id']));
        $login = $data->row_array();
        if(md5($login['hash'])!=$_SESSION['password-editor'])
        {
            $this->logout();
            header('LOCATION:'.base_url().'editor/admin/enter');
        }
    }
    public function login_model()
    {
        if(ptrig('trigger','gobaby'))
        {
            $MD5login = $this->db->select('editor-id,hash')->get_where($this->config->item('DBTEditorLogin'),array('login'=>md5($this->input->post('editor_name'))));
            $login = $MD5login->row_array();
            if(password_verify($this->input->post('editor_secret'),$login['hash'])==true)
            {
                $_SESSION['editor-id'] = $login['editor-id'];
                $_SESSION['password-editor'] = md5($login['hash']);

                if($this->input->post('remember') == 'true')
                {
                    $duration = time()+31556926;
                    setcookie('editor-id',$_SESSION['editor-id'],$duration,'/');
                    setcookie('password-editor',$_SESSION['password-editor'],$duration,'/');
                }

                header('LOCATION:'.base_url().'editor/admin/');
            }
            else
            {
                $this->common_model->message(
                    array
                    (
                        'section'	=> 'writer',
                        'title'		=> 'Access Denied',
                        'message'	=> 'Incorrect login details, please check your login details and retry with correct login.',
                        'action'	=> '<a href="'.base_url().'editor/admin/enter"><input type="submit" class="button" value="re-try"></a>'
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
                'message' => 'Our records show there are multiple failed attempts to recover login details on this account. To protect this account, the system is temporarily freezing password recovery for 24 hours.',
                'button' => null
            );
            $retry_button = '<a href="'.$_SERVER['REQUEST_URI'].'"><input type="submit" class="button" value="Try Again"></a>';

            $editor = $this->db->query('SELECT `name`,`owner` FROM `'.$this->config->item('DBTSettings').'` WHERE `owner-email` = "'.$email.'"');
            if($editor->num_rows() == 0) return array
            (
                'status' => false,
                'title'=>'Unable to Recover',
                'message' => 'Sorry! The email you provided does not exist in our database. Please check your email and try again.',
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

                    $recovery_message = '<p>Hi '.$editor->row_array()['owner'].'!</p><p>You have requested for the password recovery for your <strong>'.$editor->row_array()['name'].'</strong> account. Please click the following link to reset your password.</p>'.
                                        '<p><a href="'.base_url().'editor/admin/reset-password/'.$token.'"><h3 style="text-align: center;">RESET PASSWORD</h3></a></p>'.
                                        '<p>Or copy paste the following URL into your browser</p><p>'.base_url().'editor/admin/reset-password/'.$token.
                                        '<p>If you did not request for password reset then ignore this email.</p><p>Thank You!</p>';
                    $recovery_email['subject'] = $editor->row_array()['owner'].' your password reset email for '.$editor->row_array()['name'];
                    $recovery_email['message'] = $recovery_message;
                    $recovery_email['from'] = 'noreply@'.$_SERVER['HTTP_HOST'];
                    $recovery_email['to'] = $to;
                    $sent = @$this->common_model->sendmail($recovery_email);

                    if(isset($sent['status'])) return array
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
            'status' => false,
            'title' => 'Invalid Request',
            'message' => 'Invalid password reset request.',
            'button' => null
        );

        $data = $data->row_array();

        $now = new DateTime();
        $expiry = new DateTime($data['expiry']);

        if($now > $expiry) return array
        (
            'status' => false,
            'title'=>'Time Expired',
            'message' => 'Sorry! You did not reset the password within the time restriction. You need to re-request for your password recovery.',
            'button' => '<a href="/author/admin/recover-password"><input type="submit" class="button" value="Request"></a>'
        );

        if(ptrig('trigger','goBabyResetPassword'))
        {
            $update_password = array
            (
                'login' => md5($this->input->post('reset_login')),
                'hash'  => password_hash($this->input->post('reset_password'), PASSWORD_BCRYPT)
            );
            $this->db->set($update_password);
            $this->db->where('editor-id','admin');
            if($this->db->update($this->config->item('DBTEditorLogin')))
            {
                $this->db->delete($this->config->item('DBTRecover'),array('token' => $token));
                return array
                (
                    'status' => true,
                    'title' => 'Password Reset Successfull',
                    'message' => 'Your password has been changed successfully.',
                    'button' => '<a href="/editor/admin/enter"><input type="submit" class="button" value="Login"></a>'
                );
            }
        }
    }
    public function logout()
    {
        unset($_SESSION['editor-id'],$_SESSION['password-editor']);
        setcookie('editor-id',"",time()-3600,'/');
        setcookie('password-editor',"",time()-3600,'/');
        header('LOCATION:'.base_url());
    }
    /* -- Author --*/
    public function author_create_model()
    {
        if(ptrig('trigger', 'gobaby')) {

            $login = md5(strtolower($this->input->post('author_profile_email')));

            # if login email already exists abondon further proceedings
            $login_chk = $this->db->query("SELECT null FROM `{$this->config->item('DBTAuthorLogin')}` WHERE `login` = '$login'")->num_rows();
            if($login_chk)
            {
                return
                [
                    'status' => false,
                    'title' => 'The Email Already Exists',
                    'message' => 'The Email you have provided already associated with another account. Please use another email.',
                    'action' => '<input type="submit" class="button" value="Dashboard" onclick="go(\'' . base_url() . 'editor/admin/\');"><input type="submit" class="button" value="Back" onclick="goBack();">'
                ];
            }

            $author_id = ranum(12);
            $author_path = $this->config->item('uploadir').'authors/'.$author_id;
            $this->image_upload_settings['field'] = 'author_profile_picture';

            if (!mkdir($author_path)) return false;

            if (is_uploaded_file($_FILES[$this->image_upload_settings['field']]['tmp_name']))
            {
                $this->image_upload_settings['file_name'] = $author_id;
                $this->image_upload_settings['upload_path'] = $author_path;
                $filename = $this->common_model->image_uploader($this->image_upload_settings);
            }
            else $filename = null;

            $insert = array
            (
                'author-id' => $author_id,
                'name' => $this->input->post('author_profile_name'),
                'email' => $this->input->post('author_profile_email'),
                'about' => $this->input->post('author_profile_about'),
                'picture' => $filename
            );
            $insert_login = array
            (
                'author-id' => $author_id,
                'login' => $login,
                'hash' => password_hash($this->input->post('author_profile_pass'), PASSWORD_BCRYPT)
            );
            if ($this->db->insert($this->config->item('DBTAuthor'), $insert) and $this->db->insert($this->config->item('DBTAuthorLogin'), $insert_login))
            {
                $site_data = $this->db->query('SELECT `name` FROM `'.$this->config->item('DBTSettings').'`');

                $welcome_message = '<p>Welcome to '.$site_data->row_array()['name'].'!</p>'.
                                    '<p>Congratulations! '.$this->input->post('author_profile_name').' You are our valued team member now. Account has been created and and accessible with login details.</p>'.
                                    '<p style="text-align: center;"><a href="'.base_url().'author/admin/enter">Login Details</a></p>'.
                                    '<p style="margin-top: 30px;">LOGIN:<strong><br/>'.$this->input->post('author_profile_email').'</strong></p>'.
                                    '<p style="margin-bottom: 30px;">PASSWORD:<strong><br/>'.$this->input->post('author_profile_pass').'</strong></p>'.
                                    '<p>You can change your password any later time you wish.</p>'.
                                    '<p style="margin-top: 30px;">Thanks for joining our team, we all welcome you full hearty and wish you very good luck.</p>'.
                                    '<p style="text-align: right;">Administration</p>';
                $welcome_email['subject'] = 'Welcome! '.$this->input->post('author_profile_name').' to '.$site_data->row_array()['name'];
                $welcome_email['message'] = $welcome_message;
                $welcome_email['from'] = 'admin@'.$_SERVER['HTTP_HOST'];
                $welcome_email['to'] = $this->input->post('author_profile_email');
                $sent = @$this->common_model->sendmail($welcome_email);
                if(isset($sent['status']) and $sent['status']===false) return
                    [
                        'status' => false,
                        'title' => 'Author Profile Has Been Created',
                        'message' => 'Author profile has been created but unable to notify the author through email due to a mail server error. You may need to notify '.$this->input->post('author_profile_name').' manually.',
                        'action' => '<input type="submit" class="button" value="Dashboard" onclick="go(\'' . base_url() . 'editor/admin/\');">'
                    ];
                else return
                    [
                        'status' => true,
                        'title' => 'Author Profile Has Been Created',
                        'message' => 'The author profile has been created successfully. Notification and login details are sent to the author by email.',
                        'action' => '<input type="submit" class="button" value="Dashboard" onclick="go(\'' . base_url() . 'editor/admin/\');">'
                    ];
            }
            else return
                [
                    'status' => false,
                    'title' => 'Unable to Create Author Profile',
                    'message' => 'Unable to create the author profile due to an error in the database server. Trying again later may resolve the issue.',
                    'action' => '<input type="submit" class="button" value="Dashboard" onclick="go(\'' . base_url() . 'editor/admin/\');"><input type="submit" class="button" value="Back" onclick="goBack();">'
                ];
        }
    }
    public function author_display_model($author_id)
    {
        $data = $this->db->query("SELECT * FROM `{$this->config->item('DBTAuthor')}` WHERE `author-id` = '$author_id'")->row_array();
        if(!$data) return false;

        $views = $this->db->query("SELECT SUM(views) FROM {$this->config->item('DBTPage')} WHERE `author-id` = '$author_id'")->row_array();

        $arow = $this->db->query("SELECT * FROM `{$this->config->item('DBTAuthor')}` WHERE `author-id` = '$author_id'")->row_array();

        if($arow['picture']) $arow['picture'] = base_url().$this->config->item('uploadir').'authors/'.$arow['author-id'].'/'.$arow['picture'];
        else $arow['picture'] = base_url().'images/person.svg';

        return array
        (
            "arow" => $arow,
            'rows' => $this->db->query("SELECT `author-id`,`page-id`,`status`,`title`,`image`,`description`,`views`,`d_n_t`,`t_n_d` FROM `{$this->config->item('DBTPage')}` WHERE `author-id` = '$author_id'")->result_array(),
            'views'=> $views['SUM(views)']
        );
    }
    /* -- Articles -- */
    public function page_home_model($page_id)
    {
        $deactivate = array
        (
            'status' => 'published'
        );
        $this->db->set($deactivate);
        $this->db->where('status','homepage');
        if($this->db->update($this->config->item('DBTPage'))) $status['deactivate'] = true;
        $activate = array
        (
            'status' => 'homepage'
        );
        $this->db->set($activate);
        $this->db->where('page-id',$page_id);
        if($this->db->update($this->config->item('DBTPage'))) $status['activate'] = true;

        $status['publish'] = $this->page_publish_model($page_id);

        if($status['deactivate']===true and $status['activate']===true and $status['publish']===true) die('true');
        else die('false');
    }
    public function page_publish_status_change_model($page_id,$status,$mod)
    {
        $current_status = $this->db->query("SELECT `status` FROM {$this->config->item('DBTPage')} WHERE `page-id` = '$page_id'")->row_array()['status'];
        if($current_status == $status) return 'already';

        if($mod == 'author')
        {
          if($current_status >= 3) return 'denied';
        }
        if($status == 5) $this->db->set(array('status'=>4))->where(array('status'=>5))->update($this->config->item('DBTPage'));
        $change = $this->db->set(array('status'=>$status))->where(array('page-id'=>$page_id))->update($this->config->item('DBTPage'));
        if($change) return 'true';
        else return 'false';
    }
    /* -- Theme --*/
    public function theme_create_model()
    {
        if(ptrig('trigger','gobaby'))
        {

            error_reporting(0);
            $this->load->library('filer');
            $error_heading = 'There are errors in theme installation. Please correct these errors and retry<hr/><p style="text-align: left;">';

            $upload = array
            (
                'allowed_types'     => 'zip|rar',
                'file_ext_tolower'  => TRUE,
                'upload_path'       => 'temp/'
            );

            // 1. fatal error: file upload error
            $this->load->library('upload', $upload);
            if(!$this->upload->do_upload('theme_file'))
            {
                return array('status'=>'error','message'=>$error_heading.'Upload error: <span class="text-color-highlight">'.strip_tags($this->upload->display_errors()).'</span></p>');
            }

            $data_upload = $this->upload->data();
            $tempZipFile = "temp/".$data_upload['file_name'];
            $tempDir = 'temp/';

            $zip = new ZipArchive;

            // 2. fatal error: unrecognized zip format error logging
            if($zip->open($tempZipFile)!==true)
            {
                if(file_exists($tempZipFile)) unlink($tempZipFile);
                return array('status'=>'error','message'=>$error_heading.'1. critical error: <span class="text-color-highlight">Unrecognized zip format.</span></p>');
            }
            $zipInnerRootDir = explode('/',$zip->getNameIndex(0));

            if($this->filer->zipExtract($tempZipFile,$tempDir)!==true)
            {
                $error['status'] = true;
                $error['message'][] = 'Unable to extract the theme zip file.';
                return array('status'=>'error','message'=>$error_heading.'1. critical error: <span class="text-color-highlight">Unable to the extract theme zip file contents.</span></p>');
            }

            // 3. fatal error: missing setting file error logging
            $setting_file = $tempDir.$zipInnerRootDir[0].'/settings.php';
            if(file_exists($setting_file)===true) include($setting_file);
            else
            {
                if(file_exists($tempZipFile)) unlink($tempZipFile);
                $this->filer->remove($tempDir.$zipInnerRootDir[0]);
                return array('status'=>'error','message'=>$error_heading.'1. critical error: <span class="text-color-highlight">The theme settings file does not exist.</span></p>');
            }

            //var_dump($existing);exit;

            // 4. fatal error: missing theme template file error logging
            $template_file = $tempDir.$zipInnerRootDir[0].'/'.$settings['template'];
            if(!file_exists($template_file) or !$settings['template'])
            {
                if(file_exists($tempZipFile)) unlink($tempZipFile);
                $this->filer->remove($tempDir.$zipInnerRootDir[0]);
                return array('status'=>'error','message'=>$error_heading.'1. critical error: <span class="text-color-highlight">The Base Template file does not exist.</span></p>');
            }
            // 5. fatal error: missing theme blog listing template file error logging
            $btemplate_file = $tempDir.$zipInnerRootDir[0].'/'.$settings['template-blog'];
            if(!file_exists($btemplate_file) or !$settings['template-blog'])
            {
                if(file_exists($tempZipFile)) unlink($tempZipFile);
                $this->filer->remove($tempDir.$zipInnerRootDir[0]);
                return array('status'=>'error','message'=>$error_heading.'1. critical error: <span class="text-color-highlight">The Blog Listing theme template file does not exist.</span></p>');
            }

            // 6. parameters error in setting file
            //    theme id validity check
            if(preg_match('/[ ~`!@#$%^&*(){}:"|?><\\\\\/\';\[\]+=,.]/m',$settings['theme-id']))
            {
                $error['status'] = true;
                $error['message'][] = 'Invalid theme id: <span class="text-color-highlight">'.$settings['theme-id'].'</span> is not a valid theme id';
            }
            $parameters = array('theme-id','author','title','image','about','styles','template','template-blog');
            foreach ($parameters as $parameter)
            {
                if(key_exists($parameter,$settings) and $settings[$parameter]) continue;
                $error['status'] = true;
                $error['message'][] = 'missing setting value: <span class="text-color-highlight">'.$parameter.'</span>.';
            }

            // 7. missing tags in theme template file
            $template_base = $tempDir.$zipInnerRootDir[0].'/'.$settings['template'];
            $template = $this->filer->read($template_base);
            $tags = array('[art-title]','[art-home]','[art-path]','[art-header]','[art-footer]','[art-keywords]','[art-description]');
            foreach ($tags as $tag)
            {
                if(substr_count($template,$tag)) continue;
                $error['status'] = true;
                $error['message'][] = "missing tag: <span class=\"text-color-highlight\">$tag</span> from file <span class=\"text-color-highlight\">$template_base</span> .";
            }
            // 8. missing tags in theme blog lisiting file
            $template_blog = $tempDir.$zipInnerRootDir[0].'/'.$settings['template-blog'];
            $btemplate = $this->filer->read($template_blog);
            $btags = array('[art-row-title]','[art-row-link]');
            foreach ($btags as $btag)
            {
                if(substr_count($btemplate,$btag)) continue;
                $error['status'] = true;
                $error['message'][] = "missing tag: <span class=\"text-color-highlight\">$btag</span> from file <span class=\"text-color-highlight\">$template_blog</span>.";
            }

            // compiling error data and cancelling install process
            if(isset($error) and $error['status']===TRUE)
            {
                $i = 1;
                foreach ($error['message'] as $msg)
                {
                    $message[] = "$i. $msg";
                    $i++;
                }
                $message = implode('<br/>',$message);
                if(file_exists($tempZipFile)) unlink($tempZipFile);
                $this->filer->remove($tempDir.$zipInnerRootDir[0]);
                return array('status'=>'error','message'=>$error_heading.$message.'</p>');
            }

            $insert = array
            (
                'theme-id'=>$settings['theme-id'],
                'author'  =>$settings['author'],
                'title'   =>$settings['title'],
                'image'   =>$settings['image'],
                'about'   =>$settings['about'],
                'styles'  =>$settings['styles'],
                'template'=>$template,
                'template-blog'=>$btemplate,
                'back'    => $settings['back'],
                'backward'=> $settings['backward'],
                'first'   => $settings['first'],
                'next'    => $settings['next'],
                'forward' => $settings['forward'],
                'last'    => $settings['last'],
                'seprator'=> $settings['seprator']
            );
            $existing = $this->db->query('SELECT null FROM `'.$this->config->item('DBTTheme').'` WHERE `theme-id`="'.$insert['theme-id'].'"')->row_array();

            // if new install
            if(!$existing)
            {
                if(file_exists($tempZipFile)) unlink($tempZipFile);
                if(file_exists('themes/'.$settings['theme-id'])) $this->filer->remove('themes/'.$settings['theme-id']);
                $this->filer->rename($tempDir.$zipInnerRootDir[0],'themes/'.$settings['theme-id']);

                if($this->db->insert($this->config->item('DBTTheme'),$insert)) return array('status'=>'installed','message'=>'The theme has been successfully installed and ready for use. You must activate it to use.');
                else return array('status'=>'error','message'=>'Unable to install the theme due to a database error. Try again a little later.');
            }
            // if updating the existing installed theme
            else
            {
                $update = array
                (
                    'author'  =>$settings['author'],
                    'title'   =>$settings['title'],
                    'image'   =>$settings['image'],
                    'about'   =>$settings['about'],
                    'styles'  =>$settings['styles'],
                    'template'=>$template,
                    'template-blog'=>$btemplate,
                    'back'    => $settings['back'],
                    'backward'=> $settings['backward'],
                    'first'   => $settings['first'],
                    'next'    => $settings['next'],
                    'forward' => $settings['forward'],
                    'last'    => $settings['last'],
                    'seprator'=> $settings['seprator']
                );
                $this->filer->rename($tempDir.$zipInnerRootDir[0],$tempDir.$settings['theme-id']);
                $this->filer->move($tempDir.$settings['theme-id'],'themes/'.$insert['theme-id']);
                if(file_exists($tempZipFile)) $this->filer->remove($tempZipFile);
                if($this->db->set($update)->where(array('theme-id'=>$settings['theme-id']))->update($this->config->item('DBTTheme')))
                return array('status'=>'updated','message'=>'The theme has been successfully updated.');;
            }
        }
    }
    public function theme_activate_model($theme_id)
    {
        $active = $this->db->query("SELECT `theme-id` FROM `{$this->config->item('DBTTheme')}` WHERE `status`='active'");

        $this->db->set(array('status' => 'inactive'));
        $this->db->where('status','active');
        if($this->db->update($this->config->item('DBTTheme'))) $status['deactivate'] = true;

        $activate = array
        (
            'status' => 'active'
        );
        $this->db->set($activate);
        $this->db->where('theme-id',$theme_id);
        if($this->db->update($this->config->item('DBTTheme'))) $status['activate'] = true;

        if($status['deactivate']===true and $status['activate']===true)
        {
            return array('status'=>'success','current'=>$active->row_array()['theme-id']);
        }
        else  return array('status'=>'fail','current'=>$active->row_array()['theme-id']);
    }
    public function theme_update_template_model($theme_id=null)
    {
        if(ptrig('trigger','goBaby'))
        {
            $template = $this->input->post('template');
            $btemplate = $this->input->post('template-blog');

            $validate = $this->theme_template_validate_model($template,$btemplate);
            if($validate['status'] == 'error')
            {
                return $validate;
            }
            $this->db->set(array('template' => $template,'template-blog'=>$btemplate));
            $this->db->where('theme-id',$theme_id);
            if($this->db->update($this->config->item('DBTTheme'))) return array('status'=>'success','message'=>null);
            else return array('status'=>'error','message'=>'database error: the database has failed to respond');
        }

        $data = $this->db->select('theme-id,template,template-blog')->get_where($this->config->item('DBTTheme'),array('theme-id'=>$theme_id));

        if(!$data->row_array()) return false;

        return array('row'=>$data->row_array());
    }
    public function theme_template_validate_model($template,$btemplate)
    {
        if(!$template) return array('status'=>'error','message'=>'missing data: base template data is missing');
        if(!$btemplate) return array('status'=>'error','message'=>'missing data: blog lisiting template data is missing');

        $tags = array('[art-title]','[art-home]','[art-path]','[art-header]','[art-footer]','[art-keywords]','[art-description]');
        foreach ($tags as $tag)
        {
            if(substr_count($template,$tag)) continue;
            $error['status'] = true;
            $error['message'][] = "Base template missing tag: <span class=\"text-color-highlight\">$tag</span>";
        }
        $btags = array('[art-row-title]','[art-row-link]');
        foreach ($btags as $btag)
        {
            if(substr_count($btemplate,$btag)) continue;
            $error['status'] = true;
            $error['message'][] = "Blog listing template missing tag: <span class=\"text-color-highlight\">$btag</span>";
        }
        // compiling error data and cancelling install process
        if(isset($error) and $error['status']===TRUE)
        {
            $i = 1;
            foreach ($error['message'] as $msg)
            {
                $message[] = "$i. $msg";
                $i++;
            }
            $message = implode('</br>',$message);
            return array('status'=>'error','message'=>'<div style="text-align: left;">'.$message.'</div>');
        }
        else return array('status'=>'success','message'=>'not required');

    }
    public function theme_validator_model()
    {
        if(ptrig('trigger','goBaby'))
        {
            $validate = $this->theme_template_validate_model($this->input->post('template-main'),$this->input->post('template-blog'));
            if($validate['status'] == 'error')
            {
                return $validate;
            }
            return array('status'=>'success','message'=>'Template code is valid and can be used as a theme.');
        }
    }
    public function theme_delete_model($theme_id)
    {
        $this->load->library('filer');
        if($this->filer->remove('themes/'.$theme_id)) $status['dirdelete'] = true;
        if($this->db->delete($this->config->item('DBTTheme'), array('theme-id'=>$theme_id))) $status['dbdelete'] = true;

        if($status['dirdelete']==true and $status['dbdelete'] == true) return true;
        else false;
    }
    public function theme_results_model($search,$order,$sort,$current_page)
    {
        if(!$search) $search = 'datasearch';
        if(!$order) $order = 'title';
        if(!$sort) $sort = 'latest';
        if(!$current_page) $current_page = 1;

        $search = str_replace('-',' ',$search);
        $search_data = array
        (
            // database table to search in
            'database-table' => $this->config->item('DBTTheme'),
            // make it complusry to find certain values in
            // certain database columns like author-id must be xxxx
            'restrict' => null,//array('author-id' => '123456789'),
            // select database columns to retiriev data from.
            // for all use *
            'select' => 'theme-id,status,author,title,about,image,d_n_t',
            // fulltext or null
            'search-type' => 'fulltext',
            // if fulltext then define MYSQL
            // fulltext search modes
            'fulltext-mode' => 'IN BOOLEAN MODE',
            // search in 'database column' => 'search string'
            'search' => array('title'=>$search),
            //'search' => array('columns'=>['title'],'string'=>$search),
            // order by database column
            'order' => $order,
            // sort order standard or reverse
            'sort' => $sort,
            // current serach result page index
            'current-page' => $current_page,
        );

        return $this->common_model->search_model($search_data);
    }
    /* -- Managment -- */
    public function settings_model()
    {
        $data = $this->db->query('SELECT * FROM '.$this->config->item('DBTSettings').' WHERE `settings-id` = 1')->row_array();

        if(ptrig('trigger','goBabySetThingsUp'))
        {
            $update = array
            (
                'name'      =>$this->input->post('site-name'),
                'owner'     =>$this->input->post('site-owner'),
                'owner-email'=>$this->input->post('site-owner-email'),
                'email'     =>$this->input->post('site-email'),
                'address'   =>$this->input->post('site-address'),
            );

            if(is_uploaded_file($_FILES['logo']['tmp_name']))
            {
                $uploadpath = $this->image_upload_settings['upload_path'];

                $image = $uploadpath . '/' . $data['site-logo'];
                $thumb = $uploadpath . '/tmb_' . $data['site-logo'];
                $tiny  = $uploadpath . '/tiny_' . $data['site-logo'];

                if($data['site-logo'] and file_exists($image)) unlink($image);
                if($data['site-logo'] and file_exists($thumb)) unlink($thumb);
                if($data['site-logo'] and file_exists($tiny)) unlink($tiny);

                $this->image_upload_settings['field'] = 'logo';
                $this->image_upload_settings['file_name'] = ranum(16);
                $image_upload = $this->common_model->image_uploader($this->image_upload_settings);

                if(isset($image_upload['status']) and $image_upload['status']===false) return array
                (
                    'status'    => false,
                    'title'     => 'Image Upload Error',
                    'message'   => $image_upload['error'],
                    'action'    => '<input type="submit" class="button" value="Re-try" onclick="go(\''.$_SERVER['REQUEST_URI'].'\')">'.
                                   '<input type="submit" class="button" value="Dashboard" onclick="go(\''.base_url().'editor/admin/\')">'
                );
                else $update['site-logo'] = $image_upload;
            }
            if(is_uploaded_file($_FILES['missing_image']['tmp_name']))
            {
                $uploadpath = $this->image_upload_settings['upload_path'];

                $image = $uploadpath . '/' . $data['default-image'];
                $thumb = $uploadpath . '/tmb_' . $data['default-image'];
                $tiny  = $uploadpath . '/tiny_' . $data['default-image'];

                if($data['default-image'] and file_exists($image)) unlink($image);
                if($data['default-image'] and file_exists($thumb)) unlink($thumb);
                if($data['default-image'] and file_exists($tiny))  unlink($tiny);

                $this->image_upload_settings['field'] = 'missing_image';
                $this->image_upload_settings['file_name'] = ranum(16);

                $image_upload = $this->common_model->image_uploader($this->image_upload_settings);

                if(isset($image_upload['status']) and $image_upload['status']===false) return array
                (
                    'status'    => false,
                    'title'     => 'Image Upload Error',
                    'message'   => $image_upload['error'],
                    'action'    => '<input type="submit" class="button" value="Retry" onclick="go(\''.$_SERVER['REQUEST_URI'].'\')">'.
                                   '<input type="submit" class="button" value="Dashboard" onclick="go(\''.base_url().'editor/admin/\')">'
                );
                else $update['default-image'] = $image_upload;

            }

            $this->db->set($update);
            $this->db->where('settings-id',1);
            if($this->db->update($this->config->item('DBTSettings')))
            {
                return array
                (
                    'status'    => true,
                    'title'     => 'Success',
                    'message'   => 'Settings are updated with imidiate effect.',
                    'action'    => '<input type="submit" class="button" value="Dashboard" onclick="go(\''.base_url().'editor/admin/\')">'
                );
            }
            else return array
            (
                'status'    => false,
                'title'     => 'Fail to Update',
                'message'   => 'Settings are failed to update due to an unknown error. You can retry a little later.',
                'action'    => '<input type="submit" class="button" value="Re-try" onclick="go(\''.$_SERVER['REQUEST_URI'].'\')">'.
                               '<input type="submit" class="button" value="Dashboard" onclick="go(\''.base_url().'editor/admin/\')">'
            );

        }
        if(isset($data['site-logo']) and $data['site-logo']) $data['site-logo'] = base_url().$this->config->item('uploadir').'page-title-pictures/'.$data['site-logo'];
        else $data['site-logo'] = '/images/picture.svg';
        if(isset($data['default-image']) and $data['default-image']) $data['default-image'] = base_url().$this->config->item('uploadir').'page-title-pictures/'.$data['default-image'];
        else $data['default-image'] = '/images/picture.svg';
        return array('row'=>$data);
    }
    public function about_model()
    {
        $data = $this->db->query('SELECT `version`,`lic-key`,`product` FROM `'.$this->config->item('DBTSettings').'` WHERE `settings-id` = "1"')->row_array();
        $version = json_decode(@file_get_contents($this->config->item('api')['version'].'/'.$data['product']),true);

        if(!$version)
        return array
        (
            'status'    => true,
            'title'     => '<span class="text-color-highlight">Unable </span>to Connect',
            'message'   => 'Unable to establish a connection to the licensing server. Please try again a little later.',
            'action'    => '<input type="submit" onclick="go(\''.base_url().'editor/admin\');" class="button" value="Dashboard">'
        );

        /* articlar update code */
        if(ptrig('trigger','goBabyUpdateArticlar'))
        {
            $response = json_decode(file_get_contents($this->config->item('api')['update'].$data['lic-key'].'/'.$_SERVER['HTTP_HOST'].'/'.$data['product']),true);
            if($response['status'] == 'true')
            {
                $temp = 'temp/';
                $file = ranum(16).'.zip';
                $load = file_get_contents($this->config->item('api')['domain'].$response['file']);
                $save = file_put_contents($temp.$file,$load);
                if(!$save) return array();
                if(zipExtract($temp.$file))
                {
                    unlink($temp.$file);
                    $this->db->set(['version'=>$version['current']])->where('settings-id','1')->update($this->config->item('DBTSettings'));
                    return array
                    (
                        'status'    => true,
                        'title'     => '<span class="text-color-highlight">Successfully</span> Updated',
                        'message'   => 'Articlar has been updated to newer version <span class="text-color-highlight">v.'.$version['current'].'</span>.',
                        'action'    => '<input type="submit" onclick="go(\''.base_url().'editor/admin\');" class="button" value="Dashboard">'
                    );
                }
            }
            else return array
            (
                'status'    => false,
                'title'     => $response['title'],
                'message'   => $response['message'],
                'action'    => '<input type="submit" onclick="goBack();" class="button" value="Back">'
            );
        }
        /* articlar update licence key code */
        if(ptrig('key-trigger','goBabyInstallKey'))
        {
            $licinfo = json_decode(file_get_contents($this->config->item('api')['licence'].trim($_POST['licence-ley']).'/'.$_SERVER['HTTP_HOST'].'/'.$data['product']),true);

            if($licinfo['status'] == 'true')
            {
                if($this->db->set(['lic-key'=>trim($_POST['licence-ley'])])->where('settings-id',1)->update($this->config->item('DBTSettings')))
                return array
                (
                    'status'    => true,
                    'title'     => '<span class="text-color-highlight">Successfully</span> Installed',
                    'message'   => 'The license key has been installed successfully. The product is licensed to <span class="text-color-highlight">'.$licinfo['owner'].'</span>. It will expire <span class="text-color-highlight">'.dateFormat($licinfo['expiry']).'</span>.',
                    'action'    => '<input type="submit" onclick="go(\''.base_url().'editor/admin\');" class="button" value="Dashboard">'
                );
            }
            else return $licinfo;
        }


        if($data['lic-key']) $licinfo = json_decode(file_get_contents($this->config->item('api')['licence'].$data['lic-key'].'/'.$_SERVER['HTTP_HOST'].'/'.$data['product']),true);
        else $licinfo = array();
        unset($licinfo['status']);
        return array_merge($data,$version,$licinfo);
    }

}
?>