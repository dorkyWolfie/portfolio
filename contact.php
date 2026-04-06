<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$error = "";
$successMessage = "";

// Sanitize helper
function sanitize($val) {
    return htmlspecialchars(trim($val ?? ""), ENT_QUOTES, 'UTF-8');
}

$fname   = sanitize($_POST["fname"]   ?? "");
$lname   = sanitize($_POST["lname"]   ?? "");
$email   = sanitize($_POST["email"]   ?? "");
$subject = sanitize($_POST["subject"] ?? "");
$content = sanitize($_POST["content"] ?? "");

if ($_POST) {
    if (!$fname)   $error .= "First name is required.<br>";
    if (!$lname)   $error .= "Last name is required.<br>";
    if (!$email)   $error .= "An email address is required.<br>";
    if (!$subject) $error .= "The subject is required.<br>";
    if (!$content) $error .= "The content field is required.<br>";

    if ($email && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $error .= "The email address is invalid.<br>";
    }

    if ($error != "") {
        $error = '<div class="alert alert-danger" role="alert"><p>There were error(s) in your form:</p>' . $error . '</div>';
    } else {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Host = $_ENV['SMTP_HOST'] ?? getenv('SMTP_HOST');
            $mail->Username = $_ENV['SMTP_USER'] ?? getenv('SMTP_USER');
            $mail->Password = $_ENV['SMTP_PASS'] ?? getenv('SMTP_PASS');
            $mail->Port = $_ENV['SMTP_PORT'] ?? getenv('SMTP_PORT');

            $mail->setFrom($_ENV['SMTP_USER'], 'Portfolio Contact Form');
            $mail->addReplyTo($email, "$fname $lname");
            $mail->addAddress($_ENV['SMTP_USER']);
            $mail->Subject = $subject;
            $mail->Body    = $content;

            $mail->send();
            $successMessage = '<div class="alert alert-success" role="alert">Your message was sent — we\'ll get back to you ASAP!</div>';
            // Clear fields on success
            $fname = $lname = $email = $subject = $content = "";
        } catch (Exception $e) {
            $error = '<div class="alert alert-danger" role="alert">Your message couldn\'t be sent: ' . htmlspecialchars($mail->ErrorInfo) . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="image/svg+xml" sizes="40x40" rel="icon" href="./img/icons/developer-icons.svg">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-PMC97SWS');</script>
    <!-- End Google Tag Manager -->
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PMC97SWS"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
        <header>
      <a href="/">Sandra Radeska</a>
      <nav>
        <a href="/#projects">Work</a>
        <a href="/#tools">Tools</a>
        <a href="/contact.php">Contact</a>
        <a download="cvSandraRadeska.pdf" href="./img/cvSandraRadeska.pdf" target="_blank" rel="nofollow"><img src="./img/icons/download.svg" alt="" style="width: 1.1rem; padding-right: .3rem;">CV</a>
        <a href="https://www.linkedin.com/in/sandra-radeska-93aa46243/" target="_blank">
          <img src="./img/icons/linkedin.svg" alt="icon linkedin" class="icon">
        </a>
      </nav>
    </header>
    <main>
    <div class="container">
        <section class="contact-hero">
                <h1>Contact</h1>
                <p>Hey, thanks for visiting. I hope you like some of my work. <br/>Anyways, send me a message so we can talk.</p>
            </section>
        <div id="error"><?php echo $error . $successMessage; ?></div>
        <form method="post">
            <fieldset class="form-group first-label">
                <label for="fname">First Name</label>
                <input type="text" class="form-input" id="fname" name="fname"
                       placeholder="Your First Name" value="<?php echo $fname; ?>">
                <label for="lname">Last Name</label>
                <input type="text" class="form-input" id="lname" name="lname"
                       placeholder="Your Last Name" value="<?php echo $lname; ?>">
            </fieldset>
            <fieldset class="form-group">
                <label for="email">Email Address</label>
                <input type="email" class="form-input" id="email" name="email"
                       placeholder="Your Email" value="<?php echo $email; ?>">
            </fieldset>
            <fieldset class="form-group">
                <label for="subject">Subject</label>
                <input type="text" class="form-input" id="subject" name="subject"
                       value="<?php echo $subject; ?>">
            </fieldset>
            <fieldset class="form-group">
                <label for="content">Message</label>
                <textarea class="form-input" id="content" name="content" rows="3"><?php echo $content; ?></textarea>
            </fieldset>
            <button type="submit" id="submit" class="button-1">Submit</button>
        </form>
    </div>
</main>
<footer>
      <span>© Sandra Radeska 2025</span>
      <a style="display: none;" href="https://github.com/xandemon/developer-icons?tab=MIT-1-ov-files">Icons</a>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $("form").submit(function (e) {
            let error = "";

            if ($("#fname").val().trim() === "")    error += "The first name field is required.<br>";
            if ($("#lname").val().trim() === "")    error += "The last name field is required.<br>";
            if ($("#email").val().trim() === "")    error += "The email field is required.<br>";
            if ($("#subject").val().trim() === "")  error += "The subject field is required.<br>";
            if ($("#content").val().trim() === "")  error += "The content field is required.<br>";

            if (error !== "") {
                $("#error").html('<div class="alert alert-danger" role="alert"><p><strong>There were error(s) in your form:</strong></p>' + error + '</div>');
                return false;
            }
            return true;
        });
    </script>
</body>
</html>