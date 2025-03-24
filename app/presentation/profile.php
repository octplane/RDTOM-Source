<?php 
		
// process the range of forms which may be submitted
if ($_POST['loginform'] == "yes")
{
	try 
	{
		user_log_in($_POST['name'], $_POST['password'], $_POST['remember']=="Yes");
		global $user;
	}
	catch (Exception $e) 
	{
		$error_string = $e->getMessage();
	}
}
elseif (($_POST['logoutform'] == "yes") && is_logged_in())
{
	user_log_out();
	$profile_message = "Vous vous êtes déconnectés, à bientôt !";
}
elseif ($_POST['signupform'] == "yes")
{
	try 
	{
		user_sign_up($_POST['name'], $_POST['password'], $_POST['email']);
		user_log_in($_POST['name'], $_POST['password'], false);
		global $user;
		$profile_message = "Votre compte vient d'être créé. Il ne vous reste plus vous connecter pour répondre à des questions.";
	}
	catch (Exception $e) 
	{
		$error_string = $e->getMessage();
		$sign_up_error = true;
	}
}
elseif ($_POST['disassociateform'] == "yes")
{
	$mydb->responses_disassociate($user->get_ID());
	$profile_message = "Historique effacé.";
	
}
elseif ($_POST['formpasswordupdate'] == "yes")
{
	try 
	{
		user_update_password($_POST['oldpassword'], $_POST['newpassword']);
		$profile_message = "Votre mot de passe a été modifié.";
	}
	catch (Exception $e) 
	{
		$profile_message = $e->getMessage();
	}
	
}
elseif ($_POST['formnameupdate'] == "yes")
{
	try 
	{
		user_update_name($_POST['name']);
		$profile_message = "Votre identifiant a été modifié.";
	}
	catch (Exception $e) 
	{
		$profile_message = $e->getMessage();
	}
	
}
elseif ($_POST['formemailupdate'] == "Oui")
{
	try 
	{
		user_update_email($_POST['email']);
		$profile_message = "Votre e-amail a été modifié.";
	}
	catch (Exception $e) 
	{
		$profile_message = $e->getMessage();
	}
	
}

// show the page
set_page_subtitle("Turn left and view your profile.");
include("header.php"); 


if ($profile_message)
{
	?>
<p><?php echo $profile_message; ?></p>
	<?php 
}

// is the user logged in?
if (is_logged_in())
{
	?>
<div class="question-content">
<div class="question-box">
	<p>
		<a class="question-block" onclick="show_page_stats();">Voir vos statistiques</a>
		<a class="question-block" onclick="show_page_profile();">Mettre à jour son profil</a>
		<a class="question-block" onClick="document.formlogout.submit()">Se déconnecter</a>
	</p>
	
	<form method="post" action="<?php echo get_site_URL(); ?>profile" name="formlogout">
		<input type="hidden" name="logoutform" id="logoutform" value="yes" ></input>
	</form>
	
	<div class="layout_box" id="layout_box_stats">
	
		<?php echo return_stats_user_totals() ?>
		
		<?php echo return_stats_user_section_totals() ?>
		
		<?php echo return_stats_user_progress() ?>
		
		<?php echo get_recent_wrong_questions() ?>
		
		<?php echo get_recent_questions() ?>
	</div>
	
	<div class="layout_box" id="layout_box_profile" style="display:none;">
		<h3>Mettre à jour son mot de passe</h3>
		<form method="post" action="<?php echo get_site_URL(); ?>profile#update" name="formpasswordupdate">
			<input type="hidden" name="formpasswordupdate" id="formpasswordupdate" value="yes" ></input>
			<p>
				Ancien mot de passe : <br />
				<input class="input_text" type="password" name="oldpassword" id="oldpassword"></input>
			</p>
			<p>
				Nouveau mort de passe (8 charactères minimum) : <br />
				<input class="input_text" type="password" name="newpassword" id="newpassword"></input>
			</p>
			<p>
				<a class="button" onClick="document.formpasswordupdate.submit()">Mettre à jour son mot de passe</a>
			</p>
		</form>
		
		<h3>Mettre à jour son e-mail</h3>
		
		<form method="post" action="<?php echo get_site_URL(); ?>profile#update" name="formemailupdate">
			<input type="hidden" name="formemailupdate" id="formemailupdate" value="yes" ></input>
			<p>
				Nouvelle adresse e-mail : <br />
				<input class="input_text" type="text" name="email" id="email" value="<?php echo htmlspecialchars(stripslashes($user->get_Email())); ?>"></input>


			</p>
			<p>
				<a class="button" onClick="document.formemailupdate.submit()">Mettre à jour son e-mail</a>
			</p>
		</form>
		
		<h3>Mettre à jour son identifiant</h3>
		
		<form method="post" action="<?php echo get_site_URL(); ?>profile#update" name="formnameupdate">
			<input type="hidden" name="formnameupdate" id="formnameupdate" value="yes" ></input>
			<p>
				Nouvel identifiant : <br />
				<input class="input_text" type="text" name="name" id="name" value="<?php echo htmlspecialchars(stripslashes($user->get_Name())); ?>"></input>


			</p>
			<p>
				<a class="button" onClick="document.formnameupdate.submit()">Mettre à jour son identifiant</a>
			</p>
		</form>
		
		<h3>Effacer son historique</h3>
		<p>Si vous voulez effacez l'ensemble des questions auxquelles vous avez répondu, il faut cliquer sur ce bouton. Cette décision est irrévocable.</p>
		
		<form method="post" action="<?php echo get_site_URL(); ?>profile#update" name="disassociateform">
			<p>
				<input type="hidden" name="disassociateform"  id="disassociateform" value="yes" ></input>
				<a class="button" onClick="if (confirm('Are you sure you want the site to forget every answer you have given? This CAN NOT be undone.')){ document.disassociateform.submit() };">Effacer son historique</a>
			</p>
		</form>
		
		<p>Si vous voulez que votre compte soit effacé, envoyez nous un e-mail : <a href="mailto:contact@rollerderbytestomatic.fr ?Subject=Roller%20Derby%20Test%20O'Matic">contact@rollerderbytestomatic.fr</a>.</p>
	</div>
</div>
</div>
	<script type="text/javascript">
	    if (location.href.indexOf("#") != -1) 
		{
	        // Your code in here accessing the string like this
	        if (location.href.substr(location.href.indexOf("#")) == "#stats")
	        {
	        	show_page_stats();
	        }
	        if (location.href.substr(location.href.indexOf("#")) == "#update")
	        {
	        	show_page_profile();
	        }
	    }
	
	    function show_page_stats()
	    {
	    	$('#layout_box_profile').hide();
	    	$('#layout_box_stats').fadeIn();

	    	if (typeof(google) != "undefined")
	    	{
	    		var chart_user_section_totals = new google.visualization.ColumnChart(document.getElementById('chart_section_breakdown'));
	    		if (typeof(data_user_section_totals) != "undefined")
	    		{
	    			chart_user_section_totals.draw(data_user_section_totals, options_user_section_totals);	
	    		}
	    		
	    	    var chart_stats_user_progress = new google.visualization.LineChart(document.getElementById('chart_progress'));
	    	    if (typeof(data_stats_user_progress) != "undefined")
	    		{
	    	    	chart_stats_user_progress.draw(data_stats_user_progress, options_stats_user_progress);	
	    		}
	    	}
	    	
	    	window.location.hash='#stats';
	    }
	
	    function show_page_profile()
	    {
	    	$('#layout_box_stats').hide();
	    	$('#layout_box_profile').fadeIn();
	    	window.location.hash='#update';
	    }
	</script>
	<?php 
}
else
{
	// only show this page if we're on SSL
	force_secure();
	
	?>
	<div id="form_login" <?php if ($sign_up_error) { echo "style=\"display: none;\""; }?>>
		<h3>Se connecter</h3>
		
		<form method="post" action="<?php echo get_site_URL(true); ?>profile" name="formlogin">
		<input type="hidden"  name="loginform" id="loginform" value="yes"></input>
		<p>
			Identifiant :<br />
			<input class="input_text" type="text" id="name" name="name" />
		</p>
		<p>
			Mot de passe :<br />
			<input class="input_text" type="password" id="password" name="password" />
		</p>
		<p class="small_p">	
			<input type="checkbox" name="remember" id="remember" value="Yes" /> se souvenir de moi (ne pas choisir cette option si vous êtes sur un ordinateur public)
		</p>
		<p>
			<a class="button" onclick="document.formlogin.submit()">Connexion</a>
		</p>
		</form>
		
		<p>
			Si vous n'avez pas de compte <a onclick="$('#form_login').hide();$('#form_signup').show();">ya plus qu'à </a>!
		</p>
	</div>
	
	<div id="form_signup" <?php if (!$sign_up_error) { echo "style=\"display: none;\""; }?>>
		<h3>Création de compte</h3>
		<form method="post" action="<?php echo get_site_URL(true); ?>profile" name="formsignup">
			<input type="hidden" id="signupform" name="signupform"  value="yes"></input>
		<p>		
			Identifiant : <br />
			<input class="input_text" type="text" id="signup_name" name = "name" />
		</p>
		<p>		
			Mot de passe : <br />
			<input class="input_text" type="password" id="signup_password" name = "password" /> <span id="password_extra"></span>
		</p>
		<p>		
			Email : <br />
			<input class="input_text" type="text" id="signup_email" name = "email"> <span style="font-style:italic; color:#777">Optionnel</span>
		</p>
		<p>
			<a class="button" onclick="document.formsignup.submit()">Sign up</a>
		</p>
		</form>
		<p>
			Si vous avez déjà un compte, <a onclick="$('#form_signup').hide();$('#form_login').show();">connectez-vous</a>.
		</p>
	</div>
	<p><a href="<?php echo get_site_URL(); ?>passwordreset">Vous avez oublié votre mot de passe ?</a></p>
	
	<script type="text/javascript">
	    if (location.href.indexOf("#") != -1) {
	        // Your code in here accessing the string like this
	        if (location.href.substr(location.href.indexOf("#")) == "#signup")
	        {
	        	$('#form_login').hide();
	        	$('#form_signup').show();
	        }
	    }

	    $('#signup_password').on('input', function() {
	    	 if ( $('#signup_password').val().length < 8)
	    	 {
	    		 $('#password_extra').html("<span style='color: red;'>Must be at least 8 characters</span>");
	    	 }
	    	 else
	    	 {
	    		 $('#password_extra').html("");
	    	 }
	    });
	</script>
	<?php 
}

include("footer.php"); 
?>

