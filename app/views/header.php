<!doctype html>
<!--[if lt IE 7]>      <html class="ie6"> <![endif]-->
<!--[if IE 7]>         <html class="ie7"> <![endif]-->
<!--[if IE 8]>         <html class="ie8"> <![endif]-->
<!--[if IE 9]>         <html class="ie9"> <![endif]-->
<!--[if gt IE 9]><!--> <html>         <!--<![endif]-->
<html>

	<head>

		<meta charset="utf-8">

		<title><?php echo $site['title']; ?></title>
		<meta name="description" content="<?php echo $site['tagline']; ?>" />
		<meta name="author" content="Ushahidi" />

		<meta name="robots" content="noindex,nofollow" />
		<link rel="canonical" href="<?php echo $site['url']; ?>" />

		<meta name="viewport" content="width=980, maximum-scale=1.0" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="<?php echo $site['url']; ?>/css/default.css" />

		<script type="text/javascript" data-main="scripts/core" src="<?php echo $site['url']; ?>/scripts/require.js"></script>

		<meta property="og:title" content="<?php echo $site['title']; ?>" />
		<meta property="og:description" content="<?php echo $site['tagline']; ?>" />
		<meta property="og:image" content="<?php echo $site['url']; ?>/apple-touch-icon-144x144.png" />

		<link rel="icon" type="image/png" href="<?php echo $site['url']; ?>/favicon.png">
		<!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
		<link rel="apple-touch-icon" href="<?php echo $site['url']; ?>/apple-touch-icon.png">
		<!-- For first and second-generation iPad: -->
		<link rel="apple-touch-icon" sizes="72x72" href="<?php echo $site['url']; ?>/apple-touch-icon-72x72.png">
		<!-- For iPhone with high-resolution Retina display: -->
		<link rel="apple-touch-icon" sizes="114x114" href="<?php echo $site['url']; ?>/apple-touch-icon-114x114.png">
		<!-- For third-generation iPad with high-resolution Retina display: -->
		<link rel="apple-touch-icon" sizes="144x144" href="<?php echo $site['url']; ?>/apple-touch-icon-144x144.png">

	</head>

	<body>

		<header class="application">
			<div class="position">
				<h1><a href="/"><span><?php echo(CFG_SITE_NAME); ?></span></a></h1>

				<?php if(Sessions::$loggedin): ?>
				<div class="user-badge">
					<p><span><?php echo($user->emails[0]->email); ?></span><img src="<?php echo($user->avatar); ?>" class="avatar" /></p>
				</div>
				<?php endif; ?>
			</div>
		</header>

		<header class="page">
			<div class="position">
				<h2><span><?php echo $page['title']; ?></span></h2>
			</div>
		</header>

		<section id="page">
			<div class="position">

				<?php if(Sessions::$loggedin): ?>
				<nav class="application">
					<ul>
						<li class="<?php if(Breadcrumbs::Crumb(0) == 'home') echo('active'); ?>"><a href="<?php echo $site['url']; ?>/home">Overview</a></li>

						<li class="grouped <?php if(Breadcrumbs::Crumb(0) == 'accounts') echo('active'); ?>"><a href="<?php echo $site['url']; ?>/accounts">Accounts</a></li>
						<li class="<?php if(Breadcrumbs::Crumb(0) == 'security') echo('active'); ?>"><a href="<?php echo $site['url']; ?>/security">Security</a></li>

						<?php /*
						<li class="<?php if(Breadcrumbs::Crumb(0) == 'badges') echo('active'); ?>"><a href="<?php echo $site['url']; ?>/badges">Badges</a></li>
						<li class="<?php if(Breadcrumbs::Crumb(0) == 'privacy') echo('active'); ?>"><a href="<?php echo $site['url']; ?>/privacy">Privacy</a></li>
						<li class="<?php if(Breadcrumbs::Crumb(0) == 'sessions') echo('active'); ?>"><a href="<?php echo $site['url']; ?>/sessions">Sessions</a></li>
						 */ ?>

					<?php if(isset($user->admin)): ?>
						<li class="grouped <?php if(Breadcrumbs::Crumb(0) == 'admin') echo('active'); ?>"><a href="<?php echo $site['url']; ?>/admin">Administrative</a></li>
						<li><a href="<?php echo $site['url']; ?>/service">Service</a></li>
					<?php endif; ?>

						<?php /*
						<li class="grouped"><a href="<?php echo $site['url']; ?>/support">Support</a></li>
						 */ ?>

						<li class="grouped"><a href="<?php echo $site['url']; ?>/logout">Log Out</a></li>
					</ul>
				</nav>
				<?php else: ?>
				<nav class="application">
					<ul>
						<li class="<?php if(Breadcrumbs::Crumb(0) == 'login') echo('active'); ?>"><a href="<?php echo $site['url']; ?>/login">Log In</a></li>
						<li class="<?php if(Breadcrumbs::Crumb(0) == 'register') echo('active'); ?>"><a href="<?php echo $site['url']; ?>/register">Register</a></li>
						<li class="grouped <?php if(Breadcrumbs::Crumb(0) == 'recovery') echo('active'); ?>"><a href="<?php echo $site['url']; ?>/recovery">Account Recovery</a></li>
					</ul>
				</nav>
				<?php endif; ?>

				<article class="content">

					<section>
