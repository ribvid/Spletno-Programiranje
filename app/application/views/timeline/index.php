<!-- Vsebina -->
<div class="content logged-in">

	<!-- Validation Errors -->
	<?php if (!empty(validation_errors()) || !empty($upload_errors)) : ?>
		<div class="row">
			<div class="col-12 post">
				<?php if(!empty(validation_errors())) echo validation_errors(); ?>
				<?php if(!empty($upload_errors)) echo $upload_errors; ?>
			</div>
		</div>
	<?php endif; ?>

	<!-- Objava -->
	<div class="row">
		<div class="col-12 post post-6">
			<?= $new_post_title; ?>
			<?php 
				$attributes = array('class' => 'new-post');
				echo form_open('timeline', $attributes); 
			?>
				<textarea maxlength="250" placeholder="<?= $new_post_placeholder; ?>" rows="4" name="text"></textarea>
				<input type="hidden" name="type" value="<?php echo 0; ?>"/>
				<input type="submit" value="<?= $publish; ?>">
			</form>
		</div>

		<div class="col-12 post post-6 right-side">
			<?= $new_image_title; ?>
			<?php 
				$attributes = array('class' => 'new-post');
				echo form_open_multipart('timeline', $attributes); 
			?>
				<p class="add-profile-image"><?= $upload_image; ?> <input type="file" name="image"></p>
				<textarea maxlength="100" placeholder="<?= $new_image_placeholder; ?>" rows="2" name="text"></textarea>
				<input type="hidden" name="type" value="<?php echo 1; ?>"/>
				<input type="submit" value="<?= $publish; ?>">
			</form>
		</div>
	</div>

</div>
