<?php

?>
<!doctype html>
<html >
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<div class="header_section">
<ul class="menu">
	<li class="list-posts">List Posts</li>
	<li>Contact Us</li>
</ul >
</div class="header_section">
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<div id="content" class="site-content">
		<div id="primary" class="content-area">
			<main id="main" class="site-main">
