<?php if (!defined('FW')) die('Forbidden');

$manifest = array();

$manifest['name'] = __('Mailer', 'fw');
$manifest['description'] = __('This extension will let you set some global email options and it is used by other extensions (like Forms) to send emails.', 'fw');
$manifest['version'] = '1.1.1';
$manifest['standalone'] = false;
$manifest['display'] = false;
$manifest['github_update'] = 'ThemeFuse/Unyson-Mailer-Extension';