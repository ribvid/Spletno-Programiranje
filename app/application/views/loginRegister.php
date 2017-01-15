<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Microblog</title>
		<link rel="stylesheet" href="<?php echo base_url(); ?>/css/framework.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>/css/style.css">
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&amp;subset=latin-ext" rel="stylesheet">
	</head>
	<body class="join">
		<div class="grid">

			<!-- Header -->
			<div class="row header">
				<div class="col-12">
					<h1 class="text-center">
						<?= $title; ?> 
						<a href="timeline/english_language"><img src="<?= base_url(); ?>img/en.png"></a>
						<a href="timeline/slovenian_language"><img src="<?= base_url(); ?>img/sl.png"></a>
					</h1>
				</div>
			</div>

			<div class="content">

				<!-- Search -->
				<div class="row">
					<div class="col-12 search">
						<form action="search-results.html" method="GET">
							<input type="text" name="search" placeholder="<?= $search_placeholder; ?>" id="searchInput">
						</form>
						<ul class="search-results" id="results"></ul>
					</div>
				</div>

				<script type="text/javascript" src="<?= base_url(); ?>js/ajaxSearch.js"></script>

				<div class="row">
					<!-- Login -->
					<div class="col-6 post post-6">
						<h2><?= lang('login_heading'); ?></h2>
						<?= form_open("loginRegister", '', array("formType" => "loginForm")); ?>
							<?= form_input($identity, '', array("placeholder" => strip_tags(lang('login_identity_label', 'identity')))); ?>
							<?= form_input($password, '', array("placeholder" => strip_tags(lang('login_password_label', 'password')))); ?>
							<p><?= form_submit('submit', lang('login_submit_btn')); ?></p>
						<?= form_close() ;?>
							
						<?php if ( isset($loginMessage) ) echo $loginMessage; ?>
					</div>

					<!-- Sign up -->
					<div class="col-6 post post-6 right-side">
						<h2><?= lang('create_user_heading');?></h2>
						<?= form_open_multipart("loginRegister", '', array("formType" => "registerForm"));?>
							<?= form_input($identity, '', array("placeholder" => strip_tags(lang('create_user_identity_label', 'identity')))); ?>
							<?= form_input($password, '', array("placeholder" => strip_tags(lang('create_user_password_label', 'password')))); ?>
							<?= form_input($password_confirm, '', array("placeholder" => strip_tags(lang('create_user_password_confirm_label', 'password_confirm')))); ?>
							<?= form_input($email, '', array("placeholder" => strip_tags(lang('create_user_email_label', 'email')))); ?>
							<?= form_input($bio, '', array("placeholder" => strip_tags(lang('create_user_bio_label', 'bio')), "maxlength" => "100")); ?>
							<p class="add-profile-image">
								<?= $profile_picture_label.form_upload($profile_picture, '', array("type" => "file")); ?>
							</p>
							<?= form_submit('submit', lang('create_user_submit_btn')); ?>
						<?= form_close();?>

						<?php if ( isset($registerMessage) ) echo $registerMessage; ?>
						<?php if ( isset($uploadErrors) ) echo $uploadErrors; ?>
					</div>

				</div>
			</div>