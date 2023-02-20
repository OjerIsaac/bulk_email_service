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
    <div class="container">  
    <form id="contact" action="" method="post">
        <h3>Bulk email sending service</h3>
        <label for="senderEmail">Email From:</label>
        <input type="email" name="senderEmail" required>

        <label for="emails">Recepients Email Addresses:</label>
        <input type="email" name="emails[]" id="emails" multiple required>

        <textarea placeholder="Type your message here...." tabindex="5" required></textarea>

        <button type="submit">Upload Emails</button>

        <p class="copyright">Built by <a href="https://wa.me/2348035630576" style="color: #43A047" target="_blank" title="Isaac Ojerumu">Isaac Ojerumu</a></p>
    </form>
    </div>
</body>
</html>