<?php 

include ("db/db_config.php");
session_start();

if (!array_key_exists("admin_user",$_SESSION) and (array_key_exists("adminpass", $_POST))){

        $secret = mysqli_real_escape_string($conn,$_POST["adminpass"]);

        $pwdqry = "SELECT COUNT(*) as 'Count' from admin_password where password = '$secret'";
        $checkpwd = $conn->query($pwdqry);
        $pwd_res = $checkpwd->fetch_assoc();
               
        if (($pwd_res["Count"] * 1) >= 1) {
            // admin user is logged in. Set session.
            $_SESSION["admin_user"] = "Administrator";
        } else {
            // kill the session
            unset($_SESSION["admin_user"]);
            $failedlogin = 1;

            $error = 1;
            $errormsg = "Invalid admin password. Idiot.";
        }
}

//var_dump($to_mark_teams_list);

// fetch the teams who have submitted, but not marked.

// something like select distinct teamID from submitted_answers with a missing round_scores value.
?>

<html>
    <head>
        <title>Socially Distant Pub Quiz | Admin</title>
        <?php include("htmlheader.php"); ?>
    </head>

    <body>

        <div class="container">

            <?php

            if (array_key_exists("admin_user", $_SESSION)){
                include("db/db_wordpress.php");

                $orders_qry = 'SELECT order_id, first_name, last_name, hide_email, entries FROM raffle_ticket_draw';

                $tickets_res = mysqli_query($WPconn,$orders_qry);
                while ($row = $tickets_res->fetch_assoc()){
                    $raffle_tickets_data[] = $row;
                }


                foreach($raffle_tickets_data as $rtd){
                    for ($i =1; $i <= $rtd["entries"]; $i++){
                        // ticket per entry
                         $tickets[] = $rtd;   
                        }
                    } ?>
                

                <div class="jumbotron">
                  <h1 id="winnername">Dodgy Dave's Quizzy Raffle!</h1>
                  <p class="hidden" id="windetails">Order Number: #<span id="orderno"></span> (<span id="orderemail"></span>)</p>
                  <p><a class="btn btn-primary btn-lg" id="PickWinner" role="button">Pick a winner!</a></p>
                </div>

                <script>
                    $('#PickWinner').click(function(){
                        console.log('Clicked Button');
                        var tickets = <?php echo json_encode($tickets); ?> ;
                        var winTicket = tickets[Math.floor(Math.random() * tickets.length)];

                        var winName = winTicket["first_name"].concat(' ', winTicket["last_name"]);
                        var winEmail = winTicket["hide_email"];
                        var winOrder = winTicket["order_id"];

                        $('#winnername').text(winName);
                        $('#orderno').text(winOrder);
                        $('#orderemail').text(winEmail);

                        $('#windetails').removeClass('hidden');






                    });

                </script>




                


        <?php 
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