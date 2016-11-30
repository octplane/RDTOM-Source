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

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
   		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js" type="text/javascript"></script>

		<!-- Piwik -->
		<script type="text/javascript">
		var _paq = _paq || [];
		_paq.push(['trackPageView']);
		_paq.push(['enableLinkTracking']);
		(function() {
			var u="https://a.zoy.org/";
			_paq.push(['setTrackerUrl', u+'piwik.php']);
			_paq.push(['setSiteId', '1']);
			var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
			g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
		})();
		</script>
		<noscript><p><img src="https://a.zoy.org/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
		<!-- End Piwik Code -->

	</head>

	<body>

	<h1><i class="custom-left-finger" aria-hidden="true"></i> <a href="<?php echo get_site_URL(); ?>">rollerderbytestomatic.fr</a></h1>
		<h2>questions basées sur la 8<sup>e</sup> édition des règles du 1<sup>er</sup> janvier 2015</h2>

<?php

// if error
if ($error_string)
{
	echo "<p class=\"error_string\">" . $error_string . "</p>";
}
?>
