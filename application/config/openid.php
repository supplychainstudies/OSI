<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['openid_storepath'] = 'tmp';
$config['openid_policy'] = 'users/policy';
$config['openid_required'] = array('nickname','fullname', 'email');
$config['openid_optional'] = array();
$config['openid_request_to'] = 'users/check';

?>
