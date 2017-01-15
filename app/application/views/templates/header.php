<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?= $title; ?> | Microblog</title>
		<link rel="stylesheet" href="<?= base_url(); ?>/css/framework.css">
		<link rel="stylesheet" href="<?= base_url(); ?>/css/style.css">
		<link rel="stylesheet" href="<?= base_url(); ?>/css/lightbox.css">
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&amp;subset=latin-ext" rel="stylesheet">
	</head>
	<body>
		<div class="grid">
			<!-- Če je uporabnik prijavljen, pokaži orodno vrstico.-->
			<?php if ($loggedIn) : ?>

				<!-- Profile Info -->
				<div class="row logged-in-toolbar">
					<div class="small-profile-image">
						<?php 
							$profile_picture = (!empty($user["profile_picture"])) ? $user["profile_picture"] : "blank-profile-thumb.png";
						?>
						<img src="<?= base_url(); ?>uploads/<?= $profile_picture; ?>" alt="<?= $user["username"]; ?>">
					</div>
					<!-- Za hamburger menu na manjših zaslonih -->
					<label for="menu-toggle" class="menu-toggle-label">&#9776;</label>
					<input type="checkbox" id="menu-toggle">
					<!-- End -->
					<ul id="logged-in-nav">
						<li><?= $user["username"]; ?></li>
						<li><a href="<?= base_url(); ?>timeline"><?= $timeline; ?></a></li>
						<li><a href="<?= base_url(); ?>profile/<?= $userId; ?>"><?= $view_my_profile; ?></a></li>
						<li><a href="<?= base_url(); ?>timeline/favorited-posts/<?= $userId; ?>"><?= $view_favorited_posts; ?></a></li>
						<li><a href="<?= base_url(); ?>timeline/shared-posts/<?= $userId; ?>"><?= $view_shared_posts; ?></a></li>
						<li><a href="<?= site_url('loginRegister/logout');?>"><?= $logout; ?></a></li>
						
						<!-- Slovenski jezik -->
						<li class="languages"><a href="<?= site_url('timeline/slovenian_language');?>">
							<img src="<?= base_url(); ?>img/sl.png" alt="Slovenian" class="language-icon">
						</a></li>

						<!-- Angleški jezik -->
						<li class="languages"><a href="<?= site_url('timeline/english_language');?>">
							<img src="<?= base_url(); ?>img/en.png" alt="English" class="language-icon">
						</a></li>
					</ul>
				</div>
			<?php endif; ?>