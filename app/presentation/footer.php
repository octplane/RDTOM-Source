<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.8";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


		<div class="footer">
			<?php
				if (is_question())
				{
					?>
					<p id="remembered_string_p">
						<span id="remembered_string"><?php echo get_remembered_string(); ?></span>
					</p>
					<?php
				}
			?>

			<div class="footer_block">

				<?php
				if (is_question())
				{
					?>
					<p><a class="report_link" onclick="allow_keypress = false;$('#hidden_report_form').show();"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Signaler cette question</a>
					</p>
					<?php
				}
				else
				{
					?>
					<p>
						<a href="<?php echo get_site_URL(); ?>">Répondre à une nouvelle question</a>
					</p>
					<?php
				}

				if (is_logged_in())
				{
					?>
					<p>
						Connecté en tant que <strong><?php echo htmlspecialchars(stripslashes($user->get_Name()))?></strong>, <i class="fa fa-user-circle" aria-hidden="true"></i>
 <a href="<?php echo get_site_URL(); ?>profile">voir votre profil</a>.

					</p>
					<?php
				}
				else
				{
					?>
					<p>
						<a href="<?php echo get_site_URL(); ?>profile">S'enregistrer ou se connecter</a> pour suivre ses statistiques.
					</p>
					<?php
				}
				?>

				<p>
					<i class="fa fa-list-ul" aria-hidden="true"></i> <a href="<?php echo get_site_URL(); ?>test/">Générer un test</a> — <a href="<?php echo get_site_URL(); ?>test/builder">Construire son propre test</a>
                </p>
			</div>

			<div class="footer_block">
				<p><a href="<?php echo get_site_URL(); ?>about">À propos</a></p>
				<!-- <p><a href="<?php echo get_site_URL(); ?>search">Search</a></p> -->
				<p>Contact : <a href="mailto:contact@rollerderbytestomatic.fr">contact@rollerderbytestomatic.fr</a></p>
				<p><a href="https://www.facebook.com/RDTOMfr/" target="_blank">RDTOMfr sur</a> <i class="fa fa-facebook-official" aria-hidden="true"></i>

			</div>

			<div class="footer_block">
			</div>


            <p style="text-align:center;">
                Site inspiré par celui de <a href="http://jkershaw.com/" target="_blank">John Kershaw</a> <a href="http://www.rollerderbytestomatic.com/" target= "_blank">www.rollerderbytestomatic.com</a>.
            </p>

			<?php if (is_admin())
			{
				?>
					<p style="text-align:right;">
						<a href="<?php echo get_site_URL(); ?>admin/">Admin<?php echo get_open_report_count_string(); ?></a>,
						<a href="<?php echo get_site_URL(); ?>stats/">Stats</a><?php
						if (is_question())
						{
							?>, <a href="<?php echo get_site_URL(); ?>admin/edit/<?php echo $question->get_ID(); ?>#edit_question">Editer la question</a><?php
						}
						?>
					</p>
				<?php
			}
			?>


		</div>
<hr>
<p style="text-align:left;">Liens intéressants : <img src="https://yt3.ggpht.com/-d7RVj-U4WD8/AAAAAAAAAAI/AAAAAAAAAAA/HCKjq9EYTms/s88-c-k-no-mo-rj-c0xffffff/photo.jpg" width="20" height="20"><a target="_blank" href="https://www.youtube.com/channel/UCMC5UcEHWAsM75-2ErVh25A">Nullius In verba</a>
	— <img src="http://5seconds.fr/favicon.ico" width="20" height="20"><a target="_blank" href="https://www.youtube.com/channel/UCMC5UcEHWAsM75-2ErVh25A"> <a href="http://5seconds.fr/" target="_blank">5seconds</a>


<div class="fb-like" data-href="https://www.facebook.com/RDTOMfr/?ref=bookmarks" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
		<div class="print_footer">
			<p>Page genérée par le Roller Derby Test O'Matic</p>
		</div>
		<?php echo get_google_chart_script(); ?>
		<script type="text/javascript">

			function ajax_update_forum_thread()
			{

				$.post("<?php echo get_site_URL(true); ?>ajax.php", {
					call: "latest_forum_thread"},
					function(data)
					{
						// Show the data
						$("#footer_forum_thread").hide().html(data).fadeIn('slow');
					}
				);
			}

			ajax_update_forum_thread();

		</script>
	</body>
</html>
