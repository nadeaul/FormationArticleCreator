<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Articles</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Free HTML5 Template by FREEHTML5.CO" />
	<meta name="keywords" content="free html5, free template, free bootstrap, html5, css3, mobile first, responsive" />
	<meta name="author" content="FREEHTML5.CO" />

  <!--
	//////////////////////////////////////////////////////

	FREE HTML5 TEMPLATE
	DESIGNED & DEVELOPED by FREEHTML5.CO

	Website: 		http://freehtml5.co/
	Email: 			info@freehtml5.co
	Twitter: 		http://twitter.com/fh5co
	Facebook: 		https://www.facebook.com/fh5co

	//////////////////////////////////////////////////////
	 -->

  	<!-- Facebook and Twitter integration -->
	<meta property="og:title" content=""/>
	<meta property="og:image" content=""/>
	<meta property="og:url" content=""/>
	<meta property="og:site_name" content=""/>
	<meta property="og:description" content=""/>
	<meta name="twitter:title" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:url" content="" />
	<meta name="twitter:card" content="" />

	<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
	<link rel="shortcut icon" href="favicon.ico">

	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- Animate.css -->
	<link rel="stylesheet" href="css/animate.css">
	<!-- Icomoon Icon Fonts-->
	<link rel="stylesheet" href="css/icomoon.css">
	<!-- Bootstrap  -->
	<link rel="stylesheet" href="css/bootstrap.css">
	<!-- Owl Carousel -->
	<link rel="stylesheet" href="css/owl.carousel.min.css">
	<link rel="stylesheet" href="css/owl.theme.default.min.css">

	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="../css/app.css">


	<!-- Modernizr JS -->
	<script src="js/modernizr-2.6.2.min.js"></script>
	<!-- FOR IE9 below -->
	<!--[if lt IE 9]>
	<script src="js/respond.min.js"></script>
	<![endif]-->

	</head>
	<body>
	<div class="box-wrap">
		<header role="banner" id="fh5co-header">
			<div class="container">
				<nav class="navbar navbar-default">
					<div class="row">
						<div class="col-md-3">
							<div class="fh5co-navbar-brand">
								<a class="fh5co-logo" href="#">Article Créateur</a>
							</div>
						</div>
						<div class="col-md-9 main-nav">
							<ul class="nav text-right">
								<li class="active"><a href="#"><span>Articles</span></a></li>
								<li class=""><a href="../dashboard.php"><span>Générateur d'article</span></a></li>
							</ul>
						</div>
					</div>
				</nav>
		  </div>
		</header>
		<?php
		require '../connexion.php';

		function sluggify($url)
		{
		    # Prep string with some basic normalization
		    $url = strtolower($url);
		    $url = strip_tags($url);
		    $url = stripslashes($url);
		    $url = html_entity_decode($url);

		    # Remove quotes (can't, etc.)
		    $url = str_replace('\'', '', $url);

		    # Replace non-alpha numeric with hyphens
		    $match = '/[^a-z0-9]+/';
		    $replace = '-';
		    $url = preg_replace($match, $replace, $url);

		    $url = trim($url, '-');

		    return $url;
		}

		function wd_remove_accents($str, $charset='utf-8')
		{
		    $str = htmlentities($str, ENT_NOQUOTES, $charset);
		    $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
		    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
		    $str = preg_replace('/[ ?!:\\\'"]/', '-', $str); // supprime les autres caractères
			$str = str_replace('\'', '', $str);

		    return sluggify($str);
		}

		$req = $pdo->query('SELECT * FROM articles WHERE has_data=1 AND is_published=1 ORDER BY date DESC'); // Rajouter la publication d'article.
		$article = $req->fetchAll(PDO::FETCH_OBJ);
		$i = 0;
		foreach ($article as $key => $value) {
			$url = 'article-' . $value->id . '-' . wd_remove_accents($value->title) . '.html';
			if ($i > 0)
			{
				echo '<hr>';
			}
			$date = new DateTime($value->date);
		?>
		<div class="article-view">
			<div class="row">
				<div class="col-sm-4 col-md-3 col-lg-2">
					<img src="../<?php echo $value->image; ?>" alt="<?php echo $value->alt_image; ?>" class="full-width-img">
				</div>
				<div class='col-sm-8 col-md-9 col-lg-10'>
					<span class="date-span pull-right"><?php echo $date->format('d/m/Y H:m:i'); ?></span>
					<h2><?php echo $value->title; ?></h2>
					<hr>
					<p><?php echo $value->description; ?></p>
					<div class="pull-right">
						<a href="<?php echo $url; ?>"><span><i class="fa fa-plus-circle"></i> En savoir plus</span></a>
					</div>
				</div>
			</div>
		</div>
		<?php
		$i++;
		}
		?>
	</div>
	<!-- END: box-wrap -->

	<!-- jQuery -->
	<script src="js/jquery.min.js"></script>
	<!-- jQuery Easing -->
	<script src="js/jquery.easing.1.3.js"></script>
	<!-- Bootstrap -->
	<script src="js/bootstrap.min.js"></script>
	<!-- Owl carousel -->
		<script src="js/owl.carousel.min.js"></script>
	<!-- Waypoints -->
	<script src="js/jquery.waypoints.min.js"></script>
	<!-- Parallax Stellar -->
	<script src="js/jquery.stellar.min.js"></script>

	<!-- Main JS (Do not remove) -->
	<script src="js/main.js"></script>

	</body>
</html>
