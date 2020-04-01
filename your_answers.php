<html>
	<!-- This is just a standalone page to hold the reportcard.php elements (when not on index.php) -->
	<head>

	    <!-- Global site tag (gtag.js) - Google Analytics -->
	    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-161589071-1"></script>
	    <script>
	        window.dataLayer = window.dataLayer || [];
	        function gtag(){dataLayer.push(arguments);}
	        gtag('js', new Date());

	        gtag('config', 'UA-161589071-1');
	    </script>

	    <script
	        src="https://code.jquery.com/jquery-3.4.1.min.js"
	        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
	        crossorigin="anonymous"></script>

	    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
	    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
	    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
	    <link rel="manifest" href="favicon/site.webmanifest">

	    <title>Socially Distant Pub Quiz - Your Answers</title>
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	    <link rel="stylesheet" href="vidcontrols.css" >


	</head>

	<body>
		<div class="container">


		<?php
			include("reportcard.php"); 
		?>

		</div>
	</body>
</html>
