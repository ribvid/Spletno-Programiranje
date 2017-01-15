<div class="content">

<?php
	// Število vseh objav
	$numberOfPosts = count($posts);

	// Če ni nobene objave, to sporoči uporabniku.
	if ($numberOfPosts == 0) :
		echo '<div class="row"><div class="col-12 post"><p class="text">No posts yet.</p></div></div>';

	// V nasprotnem primeru izpiši objave.
	else :
		foreach ($posts as $i => $post) :
			
			// CSS razred za posamezno objavo.
			$colClass = "col-12 post";

			// Če je število objav sodo, prikaži dve objavi v vsaki vrsti,
			// tako da popraviš CSS razred posamezne objave ($colClass).
			// Če je objava fotografija (tip objave == 1), naj ima temno ozadje.
			if ( $numberOfPosts % 2 == 0 ) :
				$colClass = ($post['type'] == 1 ? "col-6 image-post post-6 dark" : "col-6 post post-6 ");

				// Če je to prva objava v vrsti (leva), pred objavo ustvari novo vrsto.
				// Če je to objava na desni strani, ji dodaj CSS razred 'right-side'.
				if ( $i % 2 == 0 ) {
					echo '<div class="row">';
				} else {
					$colClass = ($post['type'] == 1 ? "col-6 image-post post-6 right-side dark" : "col-6 post post-6 right-side");
				}

			// Če pa je število objav liho, naj prva objava zaseda celo vrstico.
			// Tudi tukaj dodaj dodatne CSS razrede, če je objava fotografija.
			else:
				if ( $i == 0 ) {
					echo '<div class="row">';
					if ( $post['type'] == 1 ) $colClass = "col-12 image-post dark";
				}

				// Vse nadaljne objave pa naj bodo polovično dolge. 
				// Če je objava na levi strani, ustvari novo vrstico.
				// Če je na desni, objavi dodaj CSS razred right-side.
				// Za fotografijo dodaj potrebne CSS razrede (image-post in dark).
				if ( $i > 0 && $i % 2 == 1 ) {
					echo '<div class="row">';
					$colClass = ($post['type'] == 1 ? "col-6 image-post post-6 dark" : "col-6 post post-6");
				} else if($i > 0 && $i % 2 == 0) {
					$colClass = ($post['type'] == 1 ? "col-6 image-post post-6 right-side dark" : "col-6 post post-6 right-side");
				}

			endif; ?>

			<!-- IZPIS OBJAVE -->
			<div class="<?= $colClass; ?>">
				
			<?php 
				// Ce je objava fotografija, naj bo tekst njen opis 
				if ($post['type'] == 1) echo '<div class="row"><div class="image-description">'; ?>

			<?php
				// Poišči vse značke (tage), ki so v objavah in jih shrani v spremenljivko $tags.
				// Nato vsaki znački dodaj povezavo do vseh objav, ki imajo tako značko, in 
				// ustrezno popravi vsebino.
				preg_match_all("/#\w*/", $post["content"], $tags);
				foreach ($tags[0] as $tag) {
					$tagUrl = base_url().'tag/'.trim($tag, "#");
					$post["content"] = str_replace($tag, '<a href="'.$tagUrl.'">'.$tag.'</a>', $post["content"]);
				}
			?>

				<!-- Izpiši vsebino objave -->
				<p class="text"><?= $post["content"]; ?></p>
			
				<!-- Izpiši meta podatke objave -->
				<ul class="meta">
					<!-- Če avtor profila ni enak kot avtor objave, dodaj podatek o avtorju.  -->
					<?php if ($author["id"] != $post["author_id"]) : ?>
						<li><a href="<?= base_url(); ?>profile/<?= $post["author_id"] ?>"><?= findUser($post["author_id"], $users); ?></a></li>
					<?php endif; ?>

					<!-- Izpiši datum objave -->
					<li>
						<?php 
							if ($language == 'sl') {
								setlocale(LC_ALL, 'sl_SI'); 
								echo strftime("%e. %B %Y ob %H:%M", strtotime($post["pub_date"]));
							} else {
								echo date('jS F Y \a\t g A', strtotime($post["pub_date"])); 
							}
						?>
					</li>
					
					<!-- Če ne gre za tvoje lastne objave, prikaži gumba za všečkanje in deljenje na lastnem profilu. -->
					<?php if ($post["author_id"] != $user["id"] && $loggedIn) : ?>
					
						<!-- Gumb za deljenje -->
						<li>
							<?php
								// Ugotovi, ali je bila objava že deljena.
								$isShared = isShared($post["id"], $user["id"], $sharedPosts);
								
								// Dodaj ustrezno povezavo in CSS razred, glede na to, ali je bila
								// objava že deljena ali še ne.
								$shareUrl = ($isShared) ? 'post/undo_share' : 'post/share';
								$shareClass = ($isShared) ? 'isreposted' : 'repost';
							?>
							<a href="<?= site_url($shareUrl.'/'.$post["id"]); ?>">
								<span class="<?= $shareClass; ?> tooltip"><span class="tooltiptext">
									<?php echo $post["shares"]; ?>
								</span></span>
							</a>
						</li>

						<!-- Gumb za všečkanje -->
						<li>
							<?php 
								// Ugotovi, ali je bila objava že všečkana.
								$isFavorited = isFavorited($post["id"], $user["id"], $favoritedPosts);
								
								// Dodaj ustrezno povezavo in CSS razred, glede na to, ali je bila
								// objava že všečkana ali še ne.
								$favoriteUrl = ($isFavorited) ? 'post/undo_favorite' : 'post/favorite';
								$favoriteClass = ($isFavorited) ? 'isfavorited' : 'favorite';
							?>
							<a href="<?= site_url($favoriteUrl.'/'.$post["id"]); ?>">
								<span class="<?= $favoriteClass; ?> tooltip"><span class="tooltiptext">
									<?php echo $post["favorites"]; ?>
								</span></span>
							</a>
						</li>

					<?php endif; ?>
				</ul>
			
				<!-- Če je objava fotografija (tip objave == 1), potem jo dodaj k besedilu. -->
				<?php if ($post['type'] == 1) : ?>
					</div> <!-- Zapri div, ki označuje, da je vsebina objave opis slike. -->
					<div class="image">
						<img id="<?php echo 'image-'.$i; ?>" 
							src="<?php echo base_url().'uploads/'.$post["photo"]; ?>" 
							alt="<?php echo strip_tags($post["content"]); ?>" 
							class="img-responsive" 
							onclick="showImage('full-size-image-<?= $i; ?>', 'image-<?= $i; ?>', 'big-image-<?= $i; ?>', 'caption-image-<?= $i; ?>', 'close-image-<?= $i; ?>')"
						>
					
						<!-- Slika v polni velikosti -->
						<div id="full-size-image-<?= $i; ?>" class="modal-image">
							<span class="close-modal" id="close-image-<?= $i; ?>" onclick="document.getElementById('full-size-image').style.display='none'">&times;</span>
							<img src="#" class="modal-image-content" id="big-image-<?= $i; ?>" alt="">
							<div class="modal-image-caption" id="caption-image-<?= $i; ?>"></div>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
		
		<?php
			// Če je to druga objava v vrsti ali prva objava pri lihem številu objav, zapri vrstico.
			if ( ( $numberOfPosts % 2 == 0 && $i % 2 == 1 ) OR ( $numberOfPosts % 2 != 0 && $i % 2 == 0 ) ) :
				echo '</div>';
			endif;
		?>

		<?php endforeach; ?> 
	<?php endif; ?>

</div>

<?php
	// Funkcija, ki pove ali je uporabnik z id-jem $userId že všečkal objavo
	// z id-jem $postId, tako da pogleda vse objave, ki jih je všečkal ($favoritedPosts).
	function isFavorited($postId, $userId, $favoritedPosts) { 
		foreach ($favoritedPosts as $post) {
			if ($post["post_id"] == $postId && $post["user_id"] == $userId) return true;
		}
		return false;
	}

	// Funkcija, ki pove ali je uporabnik z id-jem $userId že delil objavo
	// z id-jem $postId, tako da pogleda vse objave, ki jih je delil ($sharedPosts).
	function isShared($postId, $userId, $sharedPosts) { 
		foreach ($sharedPosts as $post) {
			if ($post["post_id"] == $postId && $post["user_id"] == $userId) return true;
		}
		return false;
	}

	// Funkcija, ki pove, kakšno uporabniško ime ima uporabnik z id-jem $id,
	// tako da pregleda seznam vseh uporabnikov ($users).
	function findUser($id, $users) {
		foreach($users as $user) if($user["id"] == $id) return $user["username"];
		return "";
	}
?>