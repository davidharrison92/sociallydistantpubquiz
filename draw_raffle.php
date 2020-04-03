<?php 

include ("db/db_config.php");
session_start();




//var_dump($to_mark_teams_list);

// fetch the teams who have submitted, but not marked.

// something like select distinct teamID from submitted_answers with a missing round_scores value.
?>

<html>
    <head>
        <title>Socially Distant Pub Quiz | Admin</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


        <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
        <link rel="manifest" href="favicon/site.webmanifest">

    </head>

    <body>

        <div class="container">
            <h3>GRAND RAFFLE DRAW EXTRAVAGANZA:</h3>

            <?php

            if (array_key_exists("admin_user", $_SESSION)){
                include("db/db_wordpress.php");

                $orders_qry = 'SELECT order_id, first_name, last_name, hide_email, entries FROM raffle_ticket_draw';

                $tickets_res = mysqli_query($WPconn,$orders_qry);
                while ($row = $tickets_res->fetch_assoc()){
                    $raffle_tickets_data[] = $row;
                }

                var_dump($raffle_tickets_data);


                foreach($raffle_tickets_data as $rtd){
                    for ($i =1; $i <= $rtd["entries"]; $i++){
                        // ticket per entry
                        $tickets[] = $rtd;    
                    }
                }



            } else { // NOT "admin_user", $_SESSION
                // Admin User is NOT logged in.
            ?>
                <form class="form-inline" action="draw_raffle.php" method="post">
                    <div class="form-group">
                        <label for="adminpass">Admin Password</label>
                        <input type="text" class="form-control" id="adminpass" name="adminpass" placeholder="sssh" required="required">
                    </div>
                    <input type="hidden" name="loginonly" value="yes">
                    <button type="submit" class="btn btn-default">Log In</button>
                </form>


            <?php
            } // end of Session IF.





                mysqli_close($conn);
                ?>

        </div><!-- /container -->
    </body>
</html>