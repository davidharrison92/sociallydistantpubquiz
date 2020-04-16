<?php


include("../db/db_wordpress.php");

$total_qry = "select sum(net_total) from wp_wc_order_stats where status = 'wc-processing'";
$total_res = mysqli_query($WPconn, $total_qry);
$total_res = mysqli_fetch_row($total_res);
$charity_total = $total_res[0];


$qry_last_donation = "select concat(first_name, ' ',  left(last_name,1)) as 'ShortName', net_total  from wp_wc_order_stats ord 
						join wp_wc_customer_lookup cust 
							on ord.customer_id = cust.customer_id
						where status = 'wc-processing'
						order by date_created DESC
						Limit 1";

$res_last_donor = mysqli_query($WPconn, $qry_last_donation);
$res_last_donor = mysqli_fetch_row($res_last_donor);
$last_donor_name = $res_last_donor[0];
$last_donor_amount = $res_last_donor[1];

$qry_top_donors = "select concat(first_name, ' ',  left(last_name,1)) as 'ShortName', net_total  from wp_wc_order_stats ord 
				join wp_wc_customer_lookup cust 
					on ord.customer_id = cust.customer_id
				where status = 'wc-processing'
				order by net_total DESC
				Limit 3 ";

$res_donors = mysqli_query($WPconn,$qry_top_donors);
while ($row = $res_donors->fetch_assoc()){
    $top_donors[] = $row;
}



?>

<?php 
if ($charity_total > 350 and $charity_total <= 1000) { 
	$note = '(£'. number_format(1000 - $charity_total, 2, '.',',') . ' until the big shave)';
} elseif ($charity_total > 1000) {
	$note = '<strong>TARGET REACHED!</strong> Bring on the baldness!';
} else {
	$note = '';
}
?>
<html>
	<head>
		        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		        <link rel="stylesheet" href="stream.css">
	</head>

<body>

	<h1>Current Total: <strong>£<?php echo number_format($charity_total,2,'.',','); ?> </strong> <span class="lead"> <?php echo $note; ?></span> </h1>


			<h3>Last Donation: <?php echo $last_donor_name . ', £' . number_format($last_donor_amount,2,'.',','); ?>!, Thank You!</h3>

			<h4>Biggest Donors so far:</h4>
			<ul>
				<?php foreach($top_donors as $donor){
					echo '<li><strong>'. $donor["ShortName"] . '</strong> - £' . number_format($donor["net_total"],2,'.',',') . '</li>'; 
				}
				?>
			</ul>
	</body>
</html>