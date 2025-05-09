<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "<?php echo get_http_or_https(); ?>://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

		<title>Roller Derby Test O'Matic.fr</title>

		<?php
		echo get_CSS_embed();
		echo get_CSS_embed("print");
		?>

        <link rel="icon" href="<?php echo get_site_URL(true); ?>images/favicon.gif" type="image/gif">
        <link rel="apple-touch-icon-precomposed" href="<?php echo get_site_URL(true); ?>images/RDTOM_touch_icon.png">
		<link href="https://fonts.googleapis.com/css?family=Carrois+Gothic" rel="stylesheet">
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
		<meta name="viewport" content="width=device-width" >

		<meta property="og:title" content="Roller Derby Test O'Matic" >
		<meta property="og:description" content="<?php echo get_page_description(); ?>" >
		<meta property="og:image" content="<?php echo get_site_URL(true); ?>images/RDTOM_touch_icon.png" >

		<meta name="Description" content="<?php echo get_page_description(); ?>">

		<meta property="fb:admins" content="100000500702240"/>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js" type="text/javascript"></script>
    
    <script defer data-domain="rollerderbytestomatic.fr" src="https://plausible.io/js/script.js"></script>
    <script>window.plausible = window.plausible || function() { (window.plausible.q = window.plausible.q || []).push(arguments) }</script>

	</head>

	<body>

	<div id="fb-root"></div>
	<script async defer crossorigin="anonymous" src="https://connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v6.0"></script>

	<h1><i class="custom-left-finger" aria-hidden="true"></i> <a href="<?php echo get_site_URL(); ?>">rollerderbytestomatic.fr</a></h1>
		<p><font color="grey">En cas d'erreur sur une question, ne pas hésiter à signaler cette question</font>

<?php

// if error
if ($error_string)
{
	echo "<p class=\"error_string\">" . $error_string . "</p>";
}
?>
