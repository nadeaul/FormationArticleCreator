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

     <?php
     require '../connexion.php';

     if (!isset($_GET['id']) || intval($_GET['id']) <= 0)
     {
         header('location: index.php');
     }

     $req = $pdo->prepare('SELECT * FROM articles WHERE id=:id');
     $req->bindValue(':id', $_GET['id']);
     try
     {
         $req->execute();
         $res = $req->fetch(PDO::FETCH_OBJ);
         if (!$res->is_published || !$res->has_data)
         {
             header('location: index.php');
         }
     }
     catch(Exception $e)
     {
         header('location: index.php');
     }
     $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
     ?>

  	<!-- Facebook and Twitter integration -->
	<meta property="og:title" content="<?php echo htmlentities($res->title); ?>"/>
	<meta property="og:image" content="<?php echo $res->image; ?>"/>
	<meta property="og:url" content="<?php echo $url; ?>"/>
	<meta property="og:site_name" content="Article Createur"/>
	<meta property="og:description" content="<?php echo htmlentities($res->description); ?>"/>
	<meta name="twitter:title" content="<?php echo htmlentities($res->title); ?>" />
	<meta name="twitter:image" content="<?php echo $res->image; ?>" />
	<meta name="twitter:url" content="<?php echo $url; ?>" />
	<meta name="twitter:card" content="summary" />

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
	<link rel="stylesheet" href="../template/main.css">


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
								<a class="fh5co-logo" href="index.php">Article Créateur</a>
							</div>
						</div>
						<div class="col-md-9 main-nav">
							<ul class="nav text-right">
								<li class=""><a href="index.php"><span>Articles</span></a></li>
								<li class=""><a href="../dashboard.php"><span>Générateur d'article</span></a></li>
							</ul>
						</div>
					</div>
				</nav>
		  </div>
		</header>
        <h1 class="ac-title-header"><?php echo $res->title; ?></h1>
        <div class="ac-container">
            <?php echo str_replace('<img src="upload', '<img src="../upload', file_get_contents('../articles/' . $res->id . '.html')); ?>
        </div>
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
