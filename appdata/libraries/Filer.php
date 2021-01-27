<?php

####	Filer	v.1.0	########################################################################	20.04.09
####
####	DESCRIPTION:
####	performs various disk operating tasks
####
####	VISIBLE METHODS:
####	$filer->path_format('path');
####	replace backslashes with forwardslashes and add forwardslahes at the end.
####	
####	$filer->file_format('filename');
####	make file name more web friendly.
####	
####	$filer->get_dir('dirname');
####	get the tree of subdirectories and files, and present them in multidimensional array
####	i.	)	structure: complete tree of files and subfolders in the way they are enlisted.
####	ii.	)	directory: complete tree of all subfolders only.
####	iii.) files    : only files of all subfolders.
####	
####	$filer->remove('dirname');
####	totally delete the specified directory. unlike rmdir it can delete loaded folders.
####	NOTE : it does not respect windows built-in filelocking system so becareful.
####	
####	$filer->copier('source','destination');
####	copy's source directory to destination. if source is single file only one file will be
####	copied to destination. if source is a directory then all of it's subdirectoris and files
####	will be copied to destination.
####	 
####	$filer->get_bytes('size string');
####	convert KB'S, MB's and GB's into bytes representation 
####	i.e echo Filer::get_bytes('1.5MB'); // outputs 1572864
####	
####	By Shakeel Ahmed	#################################################### LAST UPDATED XX.XX.2018
    
class Filer
    {
        function cleaner($list,$gluChr=',')
            {
              // EXPLODE AND TRIM COMBINED TOGATHER
                $list = explode($gluChr,$list);
                $chkList = array();
                foreach($list as $valExt)
                    {
                        $chkList[]=trim($valExt);
                    }
                return $chkList;
            }
        function fcleaner($list,$gluChr=',')
            {
              // COMBINATION OF Filer::cleaner() AND Filer::strForm()
                $list = explode($gluChr,$list);
                foreach($list as $valExt)
                    {
                        $chkList[] = Filer::strForm($valExt);
                    }
                return $chkList;
            }
        function strForm($string,$small=true)
            {
              // PERFORM MANY CLEANING OPERATIONS
                if(!$string) return false;
                $illegal = array('!','@','#','%','$','%','^','&','*','(',')','~','{','}','\\','/','?','<','>','|','+','=',',','"','\'','`');
                $string = trim($string);
                $string = str_replace($illegal,'-',$string);
                $string = str_replace(' ','_',$string);
                if($small) $string = strtolower($string);
                return $string;
            }			
        public function path_format($path)
            {
              //	FORMAT THE PATH ACCORDING TO WEB STANDARDS
                $path = str_replace('\\','/',$path);
                if(substr($path,-1)!='/') $path = $path.'/';
                return $path;
            }
        public function file_format($filename)
            {
              //	FORMAT THE FILE ACCODRDING TO WEBSTANDARDS
                $pathinfo = pathinfo($filename);
                if($pathinfo['dirname']!='.')
                    {
                        $pathinfo['dirname'] = $this->path_format($pathinfo['dirname']);
                        return $pathinfo['dirname'].$pathinfo['filename'].'.'.$pathinfo['extension'];
                    }	else return $pathinfo['filename'].'.'.strtolower($pathinfo['extension']);
            }
        public function dash_url($string)
            {
                if(!$string) return false;
                return preg_replace("/[^a-zA-Z0-9]+/", "-", strtolower($string));
            }
        public function webwise($filename)
            {
              // WEBWISE NOT PRODUCTION READY METHOD
                $maxlen		= 64;
                $pathinfo = pathinfo($filename);
                if(!$pathinfo['extension']) $pathinfo['extension'] = null;
                $pathinfo['filename'] = strtolower($pathinfo['filename']);
                $pathinfo['filename'] = str_replace(' ','-',$pathinfo['filename']);
                if(strlen($pathinfo['filename']) > $maxlen) $pathinfo['filename'] = substr($pathinfo['filename'],0,$maxlen);
                if($pathinfo['dirname']!='.')
                    {
                        $pathinfo['dirname'] = $this->path_format($pathinfo['dirname']);
                        return $pathinfo['dirname'].$pathinfo['filename'].'.'.@$pathinfo['extension'];
                    }	else return $pathinfo['filename'].'.'.strtolower($pathinfo['extension']);
            }
        public function get_bytes($size)
            {
              //	CONVERT KB,MB,GB AND TB INTO BYTES
              //	1KB WILL RETURN 1024

                $excess = array('k','m','g','t','b');
                switch($size)
                    {
                        case(stripos($size,'k')==true) : 
                            {
                                $size = str_replace($excess,"",$size);
                                $size = round($size*1024);
                            }	break;
                        case(stripos($size,'m')==true) :
                            {
                                $size = str_replace($excess,"",$size);
                                $size = round($size*pow(1024,2));
                            }	break;
                        case(stripos($size,'g')==true) : 
                            {
                                $size = str_replace($excess,"",$size);
                                $size = round($size*pow(1024,3));
                            }	break;
                        case(stripos($size,'t')==true) : 
                            {
                                $size = str_replace($excess,"",$size);
                                $size = round($size*pow(1024,4));
                            }	break;
                    }
                return $size;
            }
        private function generate_dir($rootDir)
            {
              //	GENRATE DIRECTORY AND FILE LISTING
              if(!is_dir($rootDir))
                    {
                        $this->listing['str'] = false;
                        $this->listing['dir'] = false;
                        $this->listing['file']= false;
                        return array(	  $this->listing['str'],
                                        $this->listing['dir'],
                                        $this->listing['file']);
                    }
                $rootDir = $this->path_format($rootDir);
                $this->listing['dir'][]=$rootDir;
                $this->listing['str'][]=$rootDir;
                $listing = scandir($rootDir);
                foreach($listing as $file)//list(,$file) = each($listing))
                {
                  if($file != '.' and $file != '..')
                  {
                    $file = $rootDir.$file;
                    if(is_dir($file)) $this->generate_dir($file);
                    else{
                      $this->listing['file'][] = $file;
                      $this->listing['str'][]  = $file;
                    }
                  }
                }
                return $this->listing;
            }
        public function get_dir($rootDir="")
            {
              //	WRAPPER TO `generate_dir`
              //	NOTE: `generate_dir` SHOULD ALWAYS BE TRIGGERED BY `get_dir`
                unset($this->listing);
                return $this->generate_dir($rootDir);
            }
        public function replicate_dir($srcDir,$dstDir)
            {
              //	REPLICATE SOURCE DIRECTORY STRUCTURE
              //	TO DESTINATION DIRECTORY
              //	NOTE: IT ONLY REPLICATE DIRECTORY STRUCTURE
              //	IT DOES NOT COPY ANY FILES TO THEM. USE COPIER
              //	FOR THIS PURPOSE.............
                if(is_file($srcDir)) return false;
                if(!file_exists($srcDir) || !file_exists($dstDir)) return false;
                $srcDir = $this->path_format($srcDir);
                $dstDir = $this->path_format($dstDir);
                $dirStr = $this->generate_dir($srcDir);
                foreach($dirStr['dir'] as $cdir)
                    {
                    if($cdir!==$srcDir)
                        {
                            @mkdir($dstDir.str_replace($srcDir,'',$cdir));
                        }
                    }
                return true;
            }
        private function chop_path($pathData,$chopFB=0,$chopFL=0,$chopFile=false)
            {
                $file			= basename($pathData);
                $pathData = dirname($pathData);
                $pathData = explode('/',substr($this->path_format($pathData),0,-1));
                $totalDirs	= count($pathData)-1;
                $countStart	= $chopFB;
                $countEnd		= $totalDirs-$chopFL;
                if(($chopFB+$chopFL) > $totalDirs) return $file;
                while($countStart<=$countEnd)
                    {
                        $output[] = $pathData[$countStart];
                        $countStart++;
                    }
                if($chopFile===true) return implode('/',$output);
                else return implode('/',$output).'/'.$file;
            }
        public function chop_paths($pathData,$chopFB=0,$chopFL=0,$chopFile=false,$delimiter="\n")
            {
              //	REMOVE SPECIFIED NUMBER OF DIRECTORIES/FOLDERS FROM
              //	THE BEGNING AND END FROM MULTIPLE ENTRIES
              //	SUCH AS FROM FILE
              //
              //	$pathData = path string
              //	$chopFB		= number of folders/dirs to remove from upperlevels (or from begning)
              //	$chopFL		= number of folders/dirs to remove from sub-levels (or from last)
              //	$chopFile	= file to be removed from returned formated path string or not.
              //							default is to preserve file name
              //	$delimiter= seprator string default is newline
              //	$filer->chop_path(path string [ int 0, int 0, boolean false, string newline ])
                if(!$pathData) return false;
                $pathData = $this->cleaner($pathData,$delimiter);
                foreach($pathData as $outputPath)
                    {
                        $path[] = $this->chop_path($outputPath,$chopFB,$chopFL,$chopFile);
                    }
                return $path;
            }
        public function mkc_dir($dirs)
            {
              //	CREATE MULTIPLE CONSUCTIVE MULTILEVEL SUBDIRECTORIES
              $dir=null;
              $dirs = explode('/',substr($this->path_format($dirs),0,-1));
                foreach($dirs as $path)
                    {
                        $dir.= $path;
                        if(!file_exists($dir))
                            {
                                if(!mkdir($dir)) return false;
                            }
                        $dir.='/';
                    }
                    RETURN TRUE;
            }
        public function mkm_dir($baseDir="",$dirs)
            {
              //	CREATE MULTIPLE SINGLE LEVEL SUBDIRECTORIES
              //	BASE DIRECTORY WILL BE POPULATED BY DIRECTORIES
              //	NAME GIVEN IN PARAMETER 2
              //	NOTE:	DIRECTORIES NAME SHOULD BE COMMA SEPRATED
                $count = 0;
                $baseDir = $this->path_format($baseDir);
                if(!file_exists($baseDir)) return false;
                $dirs = $this->cleaner(substr($this->path_format($dirs),0,-1));
                foreach($dirs as $dir)
                    {
                        if(mkdir($baseDir.$dir)) $count++;
                    }
                if($count==NULL) $count=false;
                return $count;
            }
        public function copyone($source,$dest,$safemode='OVERWRITE')
            {
              // COPY ALL THE FILES IN FOLDER TO DESTINATION
              // ALL SUBFOLDER WILL BE TOTALLY IGNORED
              // USE COPIER METHOD TO COPY ALL SUBLEVEL
              // SUBFOLDERS AND THEIR FILES
              // MAX EXECUTION TIME PHP.INI DIRECTIVE
              // AFFECTS ITS OUTCOME
              //
              // SAFEMODE DIRECTIVES
              // ON        : 	SKIP COPYING IF FILE EXIST WITH SAME NAME
              // OVERWRITE : 	OVERWRITE EXISTING FILE
              // DUPLICATE : 	PRESERVE OLD FILE COPY NEW FILE WITH
              //							ADDED SUFFIX
              // BACKUP    :	RENAME OLD FILE AND COPY NEW FILE WITH
              //							ORIGINAL NAME
              // DEFAULT   :	OVERWRITE
              // NOTE      :	OVERWRITE DOES NOT RECOGNIZE WINDOWS FILE
              //              LOCKING SYSTEM, LOCKED FILES WILL BE
              //							OVERWRITTEN TOO
                $source = $this->path_format($source);
                $dest = $this->path_format($dest);
                if(!file_exists($source)) return false;
                $files = $this->get_dir($source);
                foreach($files['file'] as $srcFile)
                    {
                        $destFile = str_replace($source,$dest,$srcFile);
                        switch($safemode)
                            {
                                case 'ON'        : continue; break;
                                case 'OVERWRITE' : if(!copy($srcFile,$destFile)) return false; break;
                                case 'DUPLICATE' : 
                                    {
                                        $pin = pathinfo($destFile);
                                        if(file_exists($destFile)) $destFile = dirname($destFile).'/'.$pin['filename'].'_'.@date('Hms').'.'.$pin['extension'];
                                        if(!copy($srcFile,$destFile)) return false;
                                    } break;
                                case 'BACKUP':
                                    {
                                        if(file_exists($destFile)) $this->add_suffix_file($destFile,'_BAK_'.@date('Hms'));
                                        if(!copy($srcFile,$destFile)) return false;
                                    } break;
                            }							
                    }
                return true;
            }
        public function copier($source,$dest,$safemode='OVERWRITE')
            {
              // COPY SOURCE TO DESTINATION EVEN IF SOURCE IS POPULATED DIRECTORY
              // MAX EXECUTION TIME AFFECT IT'S OUTCOME
                $source=$this->path_format($source);
                $dest  =$this->path_format($dest);
                if(!file_exists($dest))$this->mkc_dir($dest);
                $this->replicate_dir($source,$dest);
                $dirs = $this->get_dir($source);
                foreach($dirs['dir'] as $srcDir)
                    {
                        $destDir = str_replace($source,$dest,$srcDir);
                        if(!$this->copyone($srcDir,$destDir,$safemode)) return false;
                    }
                return true;
            }
        public function move($source,$moveTo)
            {
                if($this->copier($source,$moveTo))
                    {
                        if($this->remove($source)) return true;
                        else return false;
                    }
                else return false;
            }
        public function rename($old,$new)
            {
                return rename($old,$new);
            }
        public function read($file)
            {
                return file_get_contents($file);
            }
        public function remove($dir,$leave_base=null)
            {
              // DELETE FILE AND POPULATED DIRECTORY
              // SET $leave_base PARAMETER IF YOU
              // WANT TO RETAIN BASE DIRECTORY
                $dir = $this->path_format($dir);
                if(!file_exists($dir))return false;
                if(is_file($dir)) unlink($dir);
                else {
                        $structure = $this->get_dir($dir);
                        foreach($structure['file'] as $file) unlink($file);
                        if(!$leave_base) $structure['dir'] = array_reverse($structure['dir']);
                        foreach($structure['dir'] as $dir) @rmdir($dir);
                     }
                return true;
            }
        public function delete($file)
          {
            $file = $this->path_format($file);
            if(!file_exists($file))return false;
            return unlink($file);
          }
        private function copy_file_engine($srcDir,$dstDir,$ext="")
            {
                if(!file_exists($srcDir) || !file_exists($dstDir)) return false;
                $srcDir = $this->path_format($srcDir);
                $dstDir = $this->path_format($dstDir);
                if(is_file(substr($srcDir,0,strlen($srcDir)-1)))
                    {
                        $file  = substr($srcDir,0,strlen($srcDir)-1);
                        $pinfo = pathinfo($file);
                        if(!copy($file,$dstDir.$pinfo['basename'])) return false; else return true;
                    }
                $files  = $this->generate_dir($srcDir);
                if(is_file($srcDir))
                    {
                        if(copy($srcDir,$dstDir)) return true; else return false;
                    }
                foreach($files['file'] as $file)
                    {
                        $path = pathinfo($file);
                        switch($ext)
                            {
                                case ($ext==false) : if(!copy($file,$dstDir.$path['basename'])) return false; break;
                                case ($path['extension']==$ext) : if(!copy($file,$dstDir.$path['basename']))return false; break;
                            }
                    }
                reset($files['file']);
                return $files['file'];	
            }
        private function safecopy_file_engine($srcDir,$dstDir,$ext="")
            {
                if(!file_exists($srcDir) || !file_exists($dstDir)) return false;
                $srcDir = $this->path_format($srcDir);
                $srcDir = substr($srcDir,0,strlen($srcDir)-1);
                $dstDir = $this->path_format($dstDir);
                if(is_file($srcDir))
                    {
                        $pinfo	 = pathinfo($srcDir);
                        $dstFile = $dstDir.$pinfo['basename'];
                        if(!file_exists($dstFile))
                            {
                                if(!copy($srcDir,$dstFile)) return false; else return true;
                            } 
                    }
                $files = $this->generate_dir($srcDir);
                if(is_file($srcDir))
                    {
                        if(copy($srcDir,$dstDir.basename($srcDir))) return true; else return false;
                    }
                foreach($files['file'] as $file)
                    {
                        $path = pathinfo($file);
                        switch($ext)
                            {
                                case ($ext==false and file_exists($dstDir.$path['basename'])==false):
                                    {
                                        if(!copy($file,$dstDir.$path['basename']))return false;
                                    }	break;
                                case ($path['extension']==$ext and file_exists($dstDir.$path['basename'])==false):
                                    {
                                        if(!copy($file,$dstDir.$path['basename']))return false;
                                    }	break;
                            }
                    }
                reset($files['file']);
                return $files['file'];
            }
        public function copy_files($srcDir,$dstDir,$ext="")
            {
              //	SEARCH AND COPY FILES OF SPECIFIED EXTENSION FROM SOURCE TO DESTINATION
              //	ANY MATCHING FILENAME IN DESTINATION WILL BE OVERWRITTEN
                if(!$ext)
                    {
                        if(!$this->copy_file_engine($srcDir,$dstDir)) return false; else return true;
                    }
                $ext = $this->cleaner($ext);
                foreach($ext as $cExt)
                    {
                        if(!$this->copy_file_engine($srcDir,$dstDir,$cExt)) return false;
                    }
                return true;
            }
        public function safecopy_files($srcDir,$dstDir,$ext="")
            {
              //	SAME AS `copy_files` BUT FILES WITH SAME FILENAME WILL NOT BE OVERWRITTEN
                if(!$ext)
                    {
                        if(!$this->safecopy_file_engine($srcDir,$dstDir)) return false; else return true;
                    }
                $ext = $this->cleaner($ext);
                foreach($ext as $cExt)
                    {
                        if(!$this->safecopy_file_engine($srcDir,$dstDir,$cExt)) return false;
                    }
                return true;
            }
        public function change_ext($baseDir,$old='*',$new)
            {
              //	CHANGE DEFINED FILE EXTENSIONS TO NEW FILE EXTENSION
              //	NOTE:	SEPRATE COMMA SEPRATED VALUES FOR MULTIPLE EXTENSIONS
                $old = str_replace('.','',$old);
                $new = str_replace('.','',$new);
                if($old==$new) return true;
                if(!$old) return false;
                if(!$new) return false;
                $baseDir = $this->path_format($baseDir);
                $old = $this->cleaner($old);
                foreach($old as $cold)
                    {
                    foreach(glob($baseDir."*.$cold") as $files)
                        {
                            $pin = pathinfo($files);
                            if(!rename($files,dirname($files).'/'.$pin['filename'].'.'.$new)) return false;
                        }
                    }
                return true;
            }				
        public function change_ext_dir($baseDir,$old='*',$new)
            {
                $dirs = $this->get_dir($baseDir);
                foreach($dirs['dir'] as $dir)
                    {
                        if($this->change_ext($dir,$old,$new)==false) return false;
                    }
                return true;
            }
        public function add_prefix_file($file,$prefix='')
            {
              //	ATTACH PREFIX STRING TO DEFINED FILE
                if(!$prefix) $prefix = @date('ymdHms').'_';
                if(!rename($file,dirname($file).'/'.$prefix.basename($file))) return false;
                return true;
            }
        public function add_prefix($baseDir,$prefix='',$patern='*.*')
            {
              //	ATTACH PREFIX TO DEFINED FILES OF ALL DIRECTORY LEVELS
              //	NOTE: i.	)	WILD CARDS CAN BE USED TO DEFINE FILES
              //				ii.	)	ALL FILES WILL BE TREATED BY DEFAULT IF NO FILE
              //							DEFINATION IS PROIDED
                $baseDir = $this->path_format($baseDir);
                foreach(glob($baseDir.$patern) as $file)
                    {
                        $this->add_prefix_file($file,$prefix);
                    }
                return true;
            }
        public function add_prefix_dir($baseDir,$prefix='',$patern='*.*')
            {
                $dirs = $this->get_dir($baseDir);
                foreach($dirs['dir'] as $dir)
                    {
                        if($this->add_prefix($dir,$prefix,$patern)==false) return false;
                    }
                return true;
            }			
        public function add_suffix_file($file,$suffix='')
            {
              //	ATTACH SUFFIX TO DEFINED FILE
                if(!$suffix) $suffix = '_'.@date('ymdHms');
                $pin = pathinfo($file);
                if(!rename($file,dirname($file).'/'.$pin['filename'].$suffix.'.'.$pin['extension'])) return false;
                return true;
            }
        public function add_suffix($baseDir,$suffix='',$patern='*.*')
            {
              //	ATTACH SUFFIX TO DEFINED FILES OF FIRST DIRECTORY LEVEL
              //	NOTE: i.	)	WILD CARDS CAN BE USED TO DEFINE FILES
              //				ii.	)	ALL FILES WILL BE TREATED BY DEFAULT IF NO FILE
              //							DEFINATION IS PROIDED

                $baseDir = $this->path_format($baseDir);
                foreach(glob($baseDir.$patern) as $file)
                    {
                        $this->add_suffix_file($file,$suffix);
                    }
                return true;
            }
        public function add_suffix_dir($baseDir,$suffix='',$patern='*.*')
            {
              //	ATTACH SUFFIX TO DEFINED FILES OF ALL DIRECTORY LEVELS
              //	NOTE: i.	)	WILD CARDS CAN BE USED TO DEFINE FILES
              //				ii.	)	ALL FILES WILL BE TREATED BY DEFAULT IF NO FILE
              //							DEFINITION IS PROVIDED
                $dirs = $this->get_dir($baseDir);
                foreach($dirs['dir'] as $dir)
                    {
                        if($this->add_suffix($dir,$suffix,$patern)==false) return false;
                    }
                return true;
            }
        public function replace_file_str($baseDir,$oldStr,$newStr='',$patern='*.*')
            {
                $baseDir = $this->path_format($baseDir);
                foreach(glob($baseDir.$patern) as $files)
                    {
                        //$pin = pathinfo($files);
                        if(!rename($files,str_replace($oldStr,$newStr,$files))) return false;
                    }
                return true;
            }
        public function add_ext($file,$extension)
            {
                if(!$file or !$extension) return false;
                if($extension[0]=='.')$extension = substr($extension,1,strlen($extension));
                $pinfo = pathinfo($file);
                if(strtolower($pinfo['extension']) != strtolower($extension)) $file = $file.'.'.$extension;
                return $file;
            }
        public function zipExtract($zipFile,$extractTo='.')
            {
                $zip = new ZipArchive;
                if ($zip->open($zipFile) === TRUE)
                    {
                        $zip->extractTo($extractTo);
                        $zip->close();
                        return true;
                    }
                else return false;
            }			
        public function zipCreate($zipFile,$source)
            {
                $zipFile = $this->file_format($zipFile);
                $source  = $this->file_format($source);
                
                // REMOVE BELOW LINE: I PUT IT BECAUSE
                // ZIPARCHIVE::CREATE IS NOT WORKING IN
                // MY SYSTEM
                file_put_contents($zipFile,''); #<-REMOVE
                
                $zip = new ZipArchive;
                $zip->open($zipFile,ZIPARCHIVE::CREATE);
                if(file_exists($source))
                {
                if(is_file($source))
                    {
                        if(!$zip->addFile($source,basename($source))) return false;
                    }
                else
                    {
                    $zipData = $this->get_dir($source);
                    foreach($zipData['file'] as $file)
                        {
                            $dest = str_replace($source,'',$file);
                            if($dest[0]=='/') $dest = substr($dest,1,strlen($dest));
                            if(!$zip->addFile($file,$dest)) return false;
                        }
                    }
                }
                else return false;
                return true;
            }
        public function download($file,$uploadir=null)
            {
                if($uploadir) $uploadir = $this->path_format($uploadir);
                else $uploadir = 'uploadir/';
                $file = $uploadir.$file;
                if(file_exists($file))
                    {
                        $finfo = new finfo(FILEINFO_MIME);
                        $type = $finfo->file($file);
                        list($mimetype,$null) = explode(';',$type);
                        list($null,$encoding) = explode('=',$null);
                        header('Content-Description: File Transfer');
                        header('Content-Type:'.trim($mimetype));
                        header('Content-Disposition: attachment; filename='.basename($file));
                        header('Content-Transfer-Encoding:'.trim($encoding));
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Pragma: public');
                        header('Content-Length: '.filesize($file));
                        ob_clean();
                        flush();
                        readfile($file);
                        return true;
                    }
                else return false;
            }
    }