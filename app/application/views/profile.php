<!-- Header -->
<div class="header <?php if($loggedIn) echo 'logged-in';?>">

	<div class="row profile-image">
		<?php 
			$profile_picture = (!empty($author["profile_picture"])) ? $author["profile_picture"] : "blank-profile.png";
		?>
		<img src="<?php echo base_url(); ?>uploads/<?= $profile_picture; ?>" alt="<?= $author["username"]; ?>">
	</div>

	<div class="user-info">
		<div class="row">
			<h1>
				<?= $author["username"]; ?>
				<?php if($loggedIn) : ?>
					<?php if($isFollowing) : ?>
						<a href="<?= site_url('profile/unfollow/'.$author["id"]); ?>" class="following">FOLLOWING</a>
					<?php else : ?>
						<a href="<?= site_url('profile/follow/'.$author["id"]); ?>" class="follow">FOLLOW</a>
					<?php endif; ?>		
				<?php endif; ?>
			</h1>
		</div>

		<div class="row">
			<h2><?= $author["bio"]; ?></h2>
		</div>
	</div>

</div>