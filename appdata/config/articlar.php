<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------+
| Articlar Configurations
|--------------------------+
|
| This is custom configuration for site
|
*/
	/*
	 * Database Tables
	 */
    $config['DBTAuthorLogin']   = 'login-writer';
	$config['DBTEditorLogin']   = 'login-editor';
	$config['DBTRecover']       = 'login-recover';
	$config['DBTAuthor']        = 'author';
	$config['DBTTheme']         = 'theme';
	$config['DBTPage']          = 'page';
	$config['DBTLog']           = 'log';
	$config['DBTSettings']      = 'settings';

	/*
	 * Directories
	 */
	$config['uploadir']         = 'uploadir/';

	/*
	 *  API Calls
	 */
	$config['api']['domain']    = 'http://api.articlar.site/';
	$config['api']['version']   = $config['api']['domain'].'version';
	$config['api']['licence']   = $config['api']['domain'].'licence/';
	$config['api']['update']    = $config['api']['domain'].'update/';

	/*
	 * Documentation Domain
	 */

/*--------------------------------------------------*/

    $config['perpage'] = 12;
    $config['segsize'] = 5;