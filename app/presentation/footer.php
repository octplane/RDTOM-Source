
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
					<p>
						<a class="report_link" onclick="allow_keypress = false;$('#hidden_report_form').show();">Signaler cette question</a>
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
						Connecté en tant que <strong><?php echo htmlspecialchars(stripslashes($user->get_Name()))?></strong>, <a href="<?php echo get_site_URL(); ?>profile">voir son profil</a>.

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
					<a href="<?php echo get_site_URL(); ?>test/">Créer un test</a>
				</p>

                <p>
                    <a href="<?php echo get_site_URL(); ?>test/builder">Construire son propre test</a>
                </p>
			</div>

			<div class="footer_block">
				<p><a href="<?php echo get_site_URL(); ?>about">A propos</a></p>
				<!-- <p><a href="<?php echo get_site_URL(); ?>search">Search</a></p> -->
				<p><a href="<?php echo get_site_URL(); ?>stats">Stats du site</a></p>

			</div>

			<div class="footer_block">
			</div>


            <p style="text-align:center;">
                Site inspiré par celui de <a href="http://jkershaw.com/">John Kershaw</a> (aka Sausage Roller) <a href="http://www.rollerderbytestomatic.com/" target= "_blank">www.rollerderbytestomatic.com</a>.
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
