<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Socially Distant Pub Quiz</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-161589071-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-161589071-1');
        </script>

    </head>
    <body>
        <div class="container">
           <?php include("header.php") ; ?>

            <hr>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <h3>About</h3>
                    <p>This idea is the work of Alex Light (<a href="https://twitter.com/Electricloo">@ElectricBloo</a>) and Dave Harrison <a href="https://twitter.com/davidharrison92">@davidharrison92</a>), who started to get a little stir crazy as a result of social distancing. </p>
                    <p>We run quizzes every Friday night (with some special one!). Follow us on Twitter (<a href="https://twitter.com/PubQuizStreams">@PubQuizStreams</a>) so you don't miss one.</p>

                    <h3>How do I play?</h3>
                    <p>We've tried to keep this as similar to a pub quiz in the real world. It's all live, and you've got to turn up to play. Even if you're at home</p>

                    <div class="embed-responsive embed-responsive-16by9 col-xs-12 text-center">
                    	<iframe width="560" height="315" src="https://www.youtube.com/embed/4tJLu9ME4IQ" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>

                    <p>Get together with whoever you're isolated with, or use FaceTime / Skype / Hangouts to get together with other lonely folk</p>
                    <ul>
                        <li>Teams of up to <strong>four people</strong>. </li>
                        <li>One person should be responsible for submitting the answers for each round<br>
                             <em>(They can be team captain, if they want to feel extra special)</em></li>
                        <li><strong>We trust you not to cheat.</strong> That means no Google! We know you can. But why would you?</li>
                    </ul>

                    <h3>Does this cost money? / Can I donate?</h3>
                    <p>Yes it does. Servers cost money to run! Currently it costs us around £20 per quiz in domain, database and hosting fees. Our time is given freely.</p>
                    <p>You're completely welcome to donate! One day we might even be able to give out prizes.</p>

                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post">

                        <!-- Identify your business so that you can collect the payments. -->
                        <input type="hidden" name="business"
                            value="david.harrison1992@googlemail.com">

                        <!-- Specify a Donate button. -->
                        <input type="hidden" name="cmd" value="_donations">

                        <!-- Specify details about the contribution -->
                        <input type="hidden" name="item_name" value="Pub Quiz donation">
                        <input type="hidden" name="item_number" value="We are eternally grateful.">
                        <input type="hidden" name="amount" value="5.00">
                        <input type="hidden" name="currency_code" value="GBP">

                        <!-- Display the payment button. -->
                        <input type="image" name="submit"
                        src="https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_donate_92x26.png"
                        alt="Buy me beer">
                        <img alt="" width="1" height="1"
                        src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >

                    </form>

                    <h3>It's broken!!!!</h3>
                    <p>This was built in a hurry! It's all a bit of an experiment. Sorry!</p>
                    <p>For technical questions - speak to <a href="https://twitter.com/davidharrison92">Dave (@davidharrison92 on Twitter)</a>.</p>

                    <hr>

                    <h3>The legal stuff</h3>
                    <h4>Do you collect any data?</h4>
                    <p>We collect the data you provide to us:</p>
                    <ul>
                        <li>Any names entered</li>
                        <li>An email address per team</li>
                        <li>Your answers</li>
                    </ul>
                    <p>We may use your answers and (hopefully hilarious) team names in promotional material, including (but not limited to) tweets and instagram posts. If you don't want this to be the case, then please <a href="mailto:hello@sociallydistant.pub">contact us</a>.</p>
                    
                    <p>We use Google Analytics to measure website use and performance. We do not allow Google to use this information for remarketing, or cross site tracking. This data is retained for a maximum of 14 months.</p>
                    <p>The data we collect is in compliance with EU GDPR laws. <strong>We will never sell or share personally identifiable data with third parties</strong></p>

                    <p><strong>If you buy raffle tickets, or donate through our <a href="https://sociallydistant.pub/store/">online store</a>, an additional <a href="https://sociallydistant.pub/store/?page_id=3">privacy policy</a> applies</strong></p>

                    <hr>

                    <h3>Who built this?</h3>
                    <p>Credit goes to the following:</p>
                    <ul>
                        <li><strong><a href="https://twitter.com/davidharrison92">David Harrison</a></strong> designed, built and maintained this website.</li>
                        <li><strong><a href="https://twitter.com/volgriff">Mark Christian</a></strong> created the magnificent <a href="thepanickedshopper_full_res.jpg">Pub Sign image</a>.</li>
                        <li>Website style and layout by <a href="https://getbootstrap.com/docs/3.3">Bootstrap</a>, used under license <a href="https://creativecommons.org/licenses/by/3.0/">CC BY 3.0</a></li>
                        <li><strong>George Walker</strong>, <strong>Gary Christian</strong>, <strong>Luke Ferris</strong>, and <strong>Alex Sim</strong> for their contributions and support with website development.
                    </ul>
                </div><!--/content-->
            </div><!--/row-->
        </div><!--/container-->
    </body>
</html>