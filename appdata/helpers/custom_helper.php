<?php defined('BASEPATH') OR exit('No direct script access allowed');

######################################################################################### 1
####	ranum() v.1.0 | General Application	###############################################
####
####	EXPLAINATION :
####	Generate random numbers of any length without computer bits issue    
####
####	By Shakeel Ahmed	#################################################################

		function ranum($count)
			{
				if(!$count or !is_numeric($count)) return false;
				$str = '1234567890';
				// just making sure first number is not 0
				$ranum[] = rand(1,9);
				$i	 = 1;
				while($i<=($count-1))
					{
						$ranum[] = $str[rand(0,9)];
						$i++;
					}
				return implode($ranum);
			}

######################################################################################### 2
####	ranum() v.1.0 | General Application	###############################################
####
####	EXPLAINATION :
####	Convert string into array based on provided delimiter and trim each array value
####	from unwanted spaced or characters. PHP explode and trim combined togather.   
####
####	By Shakeel Ahmed	#################################################################

		function cleaner($list,$delimiter=',')
			{
				if(!is_string($list)) return false;
				$list = explode($delimiter,$list);
				foreach($list as $valExt)
					{
						$chkList[]=trim($valExt);
					}
				return $chkList;
			}

######################################################################################### 3
####	totalWords() v.1.0 | General Application	#########################################
####
####	EXPLAINATION :
####	Cut short the length of string based on provided words count instead of characters  
####
####	By Shakeel Ahmed	#################################################################

	function totalWords($string,$words=10,$suffix='',$first=NULL,$last=NULL)
		{
			if(!$string or !is_string($string) or !is_numeric($words))
				{
					trigger_error('<b>Function totalWords:</b> No string defined, string parameter is blank',E_USER_WARNING );
					return false;
				}
			$string = cleaner($string,' ');
			$scount = count($string);
			if($scount<$words) 
				{
					$words = $scount;
					$suffix = NULL;
				}
			if($last) $string = array_reverse($string);
			if(is_int($first)) $a = $first; else $a = 0;
			for($i=$a;$i<=($words-1);$i++)
				{
					$word[] = $string[$i];
				}
			if(!$word) return NULL;
			if($last) $word = array_reverse($word);
			return implode(' ',$word).' '.$suffix;
		}

#########################################################################################		22	####
####	strFchop() v.1.0 | General Application	#############################################	13.08.09 #
####
####	SYNTAX:	strchop(string, int chop)
####
####	EXPLAINATION :
####	remove defined numbers of characters from the begning of given string
####
####	RETURNING VALUES:
####	on succes return choped string else boolean false
####
####	VARIABLES	:
####
####	N/A
####
####	EXAMPLE
####	N/A
####
####	NOTE:
####	N/A
####
####	By Jibran Kassim	################################################################### PHP 5.3

function strFchop($string,$chop=1)
{
	if(!$string) return false;
	$string = substr($string,$chop,strlen($string));
	return $string;
}

########################################################################################### 4
####	ptrig() v1.0	| gtrig() v1.0 ########################################################	
####	
####	EXPLAINATION :
####	Check $_POST variable is set with or without required values  
####
####	By Shakeel Ahmed	###################################################################

	function ptrig($triger,$trigVal='')
		{
		if($trigVal)
			{
				if(isset($_POST[$triger]) && $_POST[$triger]==$trigVal) return true;
				else return false;
			}
		else
			{
				if(isset($_POST[$triger]) && $_POST[$triger]!='') return true;
				else return false;
			}
		}
		function gtrig($triger,$trigVal='')
		{
			if($trigVal)
			{
				if(isset($_GET[$triger]) && $_GET[$triger]==$trigVal) return true;
				else return false;
			}
			else
			{
				if(isset($_GET[$triger]) && $_GET[$triger]!='') return true;
				else return false;
			}
		}


########################################################################################### 5
####	dateDiff() v1.0	| General Application ###############################################	
####	
####	EXPLAINATION :
####	Provide the date and time difference between two dates in year, months, days
####	hours and seconds 
####
####	By Shakeel Ahmed	#################################################################

	function dateDiff($currentDate=NULL,$expiryDate=NULL,$format=false)
		{
			$currentDate = new DateTime($currentDate);
			$expiryDate= new DateTime($expiryDate);
			$diff = $currentDate->diff($expiryDate);
			
			if(!$format) 
				{
					return array(
					"years"		=>$diff->format('%Y'),
					"months"	=>$diff->format('%M'),
					"days"		=>$diff->format('%D'),
					"hours"		=>$diff->format('%H'),
					"minutes"	=>$diff->format('%I'),
					"seconds"	=>$diff->format('%S'),
					"rem"	=>$diff->format('%R%'),
					"dif"	=>$diff->format('%a')
					);
			}
			else return $diff->format($format);
		}
	// for quick date format

########################################################################################### 6
####	dateFormat() v1.0	| General Application #############################################	
####	
####	EXPLAINATION :
####	Format the unix date and time format to desired date and time format
####
####	By Shakeel Ahmed	###################################################################

	function dateFormat($date,$format='m / d / Y')
		{
			if(!$date) 
			{
				trigger_error('<b>WARNING</b>: No date parameter is provided');
				return false;
			}
			$dt = new DateTime($date);
			return $dt->format($format);
		}

###########################################################################################	 XX	 #####
####	resizeGD() v.1.0 | General Application	#############################################	08.01.15 #
####
####	EXPLAINATION :
####	resize images using GD library. it is triggered if the imagick extension is missing
####	it ignores all the parameters of style files but retain construct and styFinalize. 
####
####
####	By Jibran Kassim	################################################################### PHP 5.5 ##

    function resizeGD($sourceImage,$destImage=NULL,$resizeWidth=200,$imageQuality=50,$format=null)
    {
        if(!$sourceImage) trigger_error('No source image is defined for resizing',E_USER_WARNING);
        $iInfo = getimagesize($sourceImage);

        list($originalWidth, $originalHeight) = $iInfo;
        $originalResolution = $originalWidth * $originalHeight;

        $percent		= $resizeWidth/$originalWidth;

        $newWidth		= $originalWidth  * $percent;
        $newHeight	    = $originalHeight * $percent;
        $newResolution  = $newWidth * $newHeight;

        // if resized image is higher than original
        // image size than orginal size will be used
        if($newResolution > $originalResolution)
        {
            $newWidth		= $originalWidth;
            $newHeight	    = $originalHeight;
        }

        $resizedImage  = imagecreatetruecolor($newWidth,$newHeight);

        switch($iInfo['mime'])
        {
            case 'image/jpeg'	:$newImage = imagecreatefromjpeg ($sourceImage);break;
            case 'image/png'	:$newImage = imagecreatefrompng  ($sourceImage);break;
            case 'image/gif'	:$newImage = imagecreatefromgif  ($sourceImage);break;
        }

#        $newImage = imagecreatefromjpeg($sourceImage);

        imagecopyresampled ($resizedImage, $newImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

        if($format) $iInfo['mime'] = $format;
        switch($iInfo['mime'])
        {
            case 'image/jpeg'	:$result = imagejpeg ($resizedImage,$destImage.'_temp',$imageQuality);break;
            case 'image/png'	:$result = imagepng  ($resizedImage,$destImage.'_temp',round(abs(($imageQuality - 100) / 11.111111))); break;
            case 'image/gif'	:$result = imagegif  ($resizedImage,$destImage.'_temp',$imageQuality);break;
        }
        if(filesize($destImage.'_temp') > filesize($sourceImage))
        {
            unlink($destImage.'_temp');
            copy($sourceImage,$destImage);
        }
        else
        {
            copy($destImage.'_temp',$destImage);
            unlink($destImage.'_temp');
        }
        imagedestroy($resizedImage);
        return $result;
    }

########################################################################################### 7
####	sendmail() v1.0	| General Application ###############################################	
####	
####	EXPLAINATION :
####	PHPMailer library wrapper function to send emails
####
####	By Shakeel Ahmed	###################################################################

	function sendmail($config)
		{
			if(!is_array($config)) 
				{
					trigger_error('Unable to initialize email, array is required and '.gettype($config).' is given',E_USER_WARNING);
					return false;
				}
			require_once('phpmailer/autoload.php');
			$mail = new PHPMailer;
			$mail->setFrom('reports@crosspital.com', 'Pathology Department');
			$mail->addAddress($config['recipient'], $config['name']);
			$mail->Subject  = $config['subject'];
			$mail->isHTML(true);
			if($config['attachment']) $mail->addStringAttachment($config['attachment'],$config['filename']);
			$mail->Body = $config['message'];
			
			return array('status'=>$mail->send(),'error'=>$mail->ErrorInfo);
		}


###########################################################################################	 XX	 #####
####	dash_url() v.1.0 | General Application	#############################################	17.02.15 #
/*

			EXPLAINATION :
			format article title in to dashed for codeigniter 3.1.3

*/
####	By Shakeel Ahmed	################################################################ PHP 7.0.02 ##

	function dash_url($string)
    {
        return slug($string);
    }

    function slug($string)
	{
		if(!$string) return false;
        return preg_replace("/[^a-zA-Z0-9]+/", "-", strtolower($string));
	}


###########################################################################################	 XX	 #####
####	clean_html() v.1.0 | General Application	######################################	09.11.18 #
/*

			EXPLAINATION :
			minify html by removing comments, newlines, tabs, double spaces and carriage return

*/
####	By Shakeel Ahmed	################################################################ PHP 7.0.02 ##

function clean_html($data)
{
    return preg_replace(array('/<!--(.*)-->/Uis',"/[[:blank:]]+/"),array('',' '),str_replace(array("\n","\r","\t"),'',$data));
}

###########################################################################################	 XX	 #####
####	pagi() v.1.0 | General Application	#################################################	17.01.16 #
/*

			EXPLAINATION :
			pagination function rewritten for codeigniter 3.1.3
		
*/
####	By Shakeel Ahmed	################################################################ PHP 7.0.02 ##

	function pagi($config)
	{
		$page = $config['url'];

		if($config['results'] > $config['perpage'])
		{
			$resultsInSegment = $config['perpage'] * $config['segsize'];
			$totalSegments = ceil(($config['results'] / $resultsInSegment));
			$currentSegment = ceil(($config['current'] * $config['perpage']) / $resultsInSegment);
			$segmentFirstPage = ($currentSegment * $config['segsize']) - ($config['segsize'] - 1);
			$lastPage = ceil($config['results'] / $config['perpage']);

			$first = '<a href="' . $page . '/1">' . $config['first'] . '</a>';
			$prevseg = '<a href="' . $page . '/' . ($segmentFirstPage - 1) . '">' . $config['backward'] . '</a>';
			$back = '<a href="' . $page . '/' . ($config['current'] - 1) . '">' . $config['back'] . '</a>';
			$next = '<a href="' . $page . '/' . ($config['current'] + 1) . '">' . $config['next'] . '</a>';
			$nextseg = '<a href="' . $page . '/' . ($segmentFirstPage + $config['segsize']) . '">' . $config['forward'] . '</a>';
			$last = '<a href="' . $page . '/' . $lastPage . '">' . $config['last'] . '</a>';

			if ($config['current'] <= $config['segsize']) {
				$prevseg = '<span style="opacity:.4">' . $config['backward'] . '</span>';
			}
			if ($config['current'] == 1) {
				$first = '<span style="opacity:.4">' . $config['first'] . '</span>';
				$back = '<span style="opacity:.4">' . $config['back'] . '</span>';
			}
			if ($currentSegment >= $totalSegments or $config['current'] >= $lastPage) {
				$nextseg = '<span style="opacity:.4">' . $config['forward'] . '</span>';
				/*
				$last = '<span style="opacity:.4">' . $config['last'] . '</span>';
				*/
			}
			if ($config['current'] >= $lastPage) {
				$next = '<span style="opacity:.4">' . $config['next'] . '</span>';
			}
			if($totalSegments < 2)
            {
                $nextseg = null;
                $prevseg = null;
            }

            $count = 0;
			for ($i = 1; $i <= $config['segsize']; $i++) {
				if (($segmentFirstPage + $count) > $lastPage) break;
				if (($segmentFirstPage + $count) == $config['current']) $pages[] = '<span class="' . $config['class'] . '">' . ($segmentFirstPage + $count) . '</span>';
				else $pages[] = '<a href="' . $page . '/' . ($segmentFirstPage + $count) . '">' . ($segmentFirstPage + $count) . '</a>';
				$count++;
			}
			$output = $first . $prevseg . $back . '<span style="vertical-align: middle;">' . implode($config['seprator'], $pages) . '</span>' . $next . $nextseg . $last;
			if(isset($config['return'])) return $output; else echo $output;
		}
	}

###############################################################################################	 XX	 #######
####	zipExtract() v.1.0 | General Application	########################################### Apr 3 2017 #
/*

			SYNTAX: zipExtract(string [zip file to extract], string[location where to unzip]);

*/
####	By Shakeel Ahmed	################################################################ PHP 7.0.02 ####

    function zipExtract($zipFile,$extractTo='.')
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

?>