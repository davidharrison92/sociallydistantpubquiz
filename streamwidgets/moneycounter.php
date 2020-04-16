<html>
<head>
        <title>Socially Distant Pub Quiz | Live Total</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


        <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
        <link rel="manifest" href="favicon/site.webmanifest">
        <script
            src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
		<link rel="stylesheet" href="stream.css">


</head>
<body>
	<div class="container">
		<div class="col-xs-12">
			<iframe id="runningtotals" width="100%" height="100%" frameborder="0" src="runningtotals.php"></iframe>
		</div>
	</div>
</body>


<script>
window.setInterval("reloadIFrame();", 30000);
function reloadIFrame() {
 document.getElementById("runningtotals").src="runningtotals.php";
}
</script>

</html>