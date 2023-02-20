<?php

// Load DotEnvironment Class
require_once('./classes/env.class.php');
$__DotEnvironment = new DotEnvironment(realpath("./.env"));

require_once "./classes/user.class.php";

$user = new User();

if (isset($_POST['submit'])) {
    if ((!empty($_POST['emails'])) && (!empty($_POST['senderEmail'])) && (!empty($_POST['message'])) && (!empty($_POST['senderName'])) && (!empty($_POST['subject']))) {
        // generate unique id
        $string = rand(); // random string
        bin2hex(random_bytes(32)); //generate randomly unique salt
        $generateCode = hash("sha256", $string . $_POST['senderName']); // adding salt
        $code = substr($generateCode, 0, 8); // select first 8 digits
        // store in database
        $upload_emails = $user->uploadEmail($_POST['emails'], $_POST['senderEmail'], $_POST['senderName'], $_POST['message'], $_POST['subject'], $code);
        if ($upload_emails) {
            // send mails
            $send_email = $user->sendEmail($code);
            if ($send_email) {
                $message = "<div style='width: fit-content; margin: 1.2rem auto; color: 43A047;'>Emails sent successfully!</div>";

                echo "<script type='text/javascript'>";
                echo "setTimeout(function() {
                    window.location.href = 'send-email';";
                echo "}, 3500);</script>";
            }else {

                $message = '<div style="width: fit-content; margin: 1.2rem auto; color: red;">Something went wrong, emails could not be sent. Please contact the admin</div>';
            }
        }else {
            $message = '<div style="width: fit-content; margin: 1.2rem auto; color: red;">Something went wrong, emails could not be uploaded. Please contact the admin</div>';
        }
    }else {
        $message = '<div style="width: fit-content; margin: 1.2rem auto; color: red;">Fields cannot be empty</div>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Bulk Email Service</title>
</head>
<body>
    <div id="loader" style="display: none;">
        <img src="loader.gif" alt="Loading..." />
    </div>
    <div class="container">  
        <form id="contact" action="" method="post">
            <h3>Bulk email sending service</h3>
            <!-- Error -->
            <?php if (isset($message)) echo $message; ?>
            <!-- /Error -->
            <label for="senderName">Organization name:</label>
            <input type="text" name="senderName" required>

            <label for="senderEmail">Email From:</label>
            <input type="email" name="senderEmail" required>

            <label for="emails">Recepients Email Addresses: (seperate mails with a comma)</label>
            <input type="email" name="emails[]" id="emails" multiple required>

            <label for="subject">Email Subject:</label>
            <input type="text" name="subject" required>

            <label for="message">Email Message:</label>
            <textarea placeholder="Type your message here...." name="message" tabindex="5" required></textarea>

            <button type="submit" name="submit">Send Email</button>

            <p class="copyright">Built by <a href="https://wa.me/2348035630576" style="color: #43A047" target="_blank" title="Isaac Ojerumu">Isaac Ojerumu</a></p>
        </form>
    </div>

    <script src="jquery.min.js"></script>
    <script>
        history.replaceState("", "", "send-email");

        $(document).ready(function() {
            // Listen for form submit event
            $('#contact').submit(function() {
                // Show loader image
                $('#loader').show();
            });
            // Hide loader image
            $('#loader').hide();
        });
    </script>
</body>
</html>