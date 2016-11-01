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

		<meta name="viewport" content="width=device-width" >

		<meta property="og:title" content="Roller Derby Test O'Matic" >
		<meta property="og:description" content="<?php echo get_page_description(); ?>" >
		<meta property="og:image" content="<?php echo get_site_URL(true); ?>images/RDTOM_touch_icon.png" >

		<meta name="Description" content="<?php echo get_page_description(); ?>">

		<script src="<?php echo get_site_URL(true); ?>js/jquery_1_8_3.min.js" type="text/javascript"></script>
   		<script src="<?php echo get_site_URL(true); ?>js/jquery-ui_1_10_3.js" type="text/javascript"></script>

	</head>

	<body>

	<h1><a href="<?php echo get_site_URL(); ?>">rollerderbytestomatic.fr</a></h1>
	<h2><?php echo get_page_subtitle(); ?></h2>

<?php

// if error
if ($error_string)
{
	echo "<p class=\"error_string\">" . $error_string . "</p>";
}
?>
