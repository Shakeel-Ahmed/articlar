<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['404_override'] = 'home/page404';

$route['editor'] = 'editor';
$route['editor/(:any)'] = 'editor/$1';
$route['editor/(:any)/(:any)'] = 'editor/$1/$2';
$route['editor/(:any)/(:any)/(:any)'] = 'editor/$1/$2/$3';
$route['editor/(:any)/(:any)/(:any)/(:any)'] = 'editor/$1/$2/$3/$4';

$route['author'] = 'author';
$route['author/(:any)'] = 'author/$1';
$route['author/(:any)/(:any)'] = 'author/$1/$2';
$route['author/(:any)/(:any)/(:any)'] = 'author/$1/$2/$3';
$route['author/(:any)/(:any)/(:any)/(:any)'] = 'author/$1/$2/$3/$4';

$route['author']                = 'author';
$route['(:any)']                = 'home/article/$1';
$route['(:any)/(:any)']         = 'home/article/$1/$2';
$route['(:any)/(:any)/(:any)']  = 'home/article/$1/$2/$3';
$route['(:any)/(:any)/(:any)/(:any)'] = 'home/article/$1/$2/$3/$4';