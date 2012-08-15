<?php

	define('SAFELY_LOADED', TRUE);
	ob_start();

	require('app/inc.config.php');

	$site = array(
		'title'   => CFG_SITE_NAME,
		'tagline' => CFG_SITE_DESCRIPTION,
		'url'     => CFG_SITE_URL
	);

	$page = array(
		'title'  => 'Account Management',
		'id'     => NULL,
		'errors' => array()
	);

	require('app/inc.helpers.php');
	require('app/class.views.php');
	require('app/class.localize.php');

	require('app/class.service.php');
	require('app/class.sessions.php');

	require('app/class.controllers.php');

	Breadcrumbs::Eat();
	$p = Breadcrumbs::Crumb(0);

	Sessions::Check();

	if($p == 'login') {
		if(Sessions::$loggedin) {
			Views::Redirect('home');
		} else {
			Controllers::Login();
		}
	} elseif($p == 'register') {
		if(Sessions::$loggedin) {
			Views::Redirect('home');
		} else {
			Controllers::Register();
		}
	/*} elseif($p == 'recovery') {
		if(Sessions::$loggedin) {
			Views::Redirect('home');
		} else {
			Controllers::Recovery();
		}*/
	} else {

		if($p == 'logout') {
			Controllers::Logout();
		} elseif($p == 'accounts') {
			Controllers::Accounts();
		} elseif($p == 'security') {
			Controllers::Security();
		} elseif($p == 'phone') {
			Controllers::Phone();
		} elseif($p == 'home') {
			Controllers::Home();
		}

	}

	header("Status: 404 Not Found");
	exit;
