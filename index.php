<?php

	define('SAFELY_LOADED', TRUE);
	ob_start();

	$site = array(
		'title' => 'CrowdmapID',
		'tagline' => 'Create or manage your CrowdmapID account.',
		'url' => 'http://random.local'
	);

	$page = array(
		'title' => 'Account Management',
		'id' => NULL,
		'errors' => array()
	);

	require('app/inc.config.php');
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
		//Controllers::Register();
	} elseif($p == 'reset') {
		//Controllers::Reset();
	} else {

		if($p == 'logout') {
			Controllers::Logout();
		} elseif($p == 'accounts') {
			Controllers::Accounts();
		} elseif($p == 'security') {
			Controllers::Security();
		} elseif($p == 'home') {
			Controllers::Home();
		}

	}

	header("Status: 404 Not Found");
	exit;
