<?php
require_once 'init.php';

// Get the reCAPTCHA response from the form
$recaptchaResponse = $_POST['g-recaptcha-response'];
$recaptchaSecretKey = 'Enter your secert key here'; 

// Verify the reCAPTCHA response
$verificationUrl = 'https://www.google.com/recaptcha/api/siteverify';
$postData = [
    'secret' => $recaptchaSecretKey,
    'response' => $recaptchaResponse,
];

$options = [
    'http' => [
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($postData),
    ],
];

$context = stream_context_create($options);
$response = file_get_contents($verificationUrl, false, $context);
$result = json_decode($response);

if ($result->success) {
    // reCAPTCHA verification was successful, proceed with form processing
    $fullname = $_POST["name"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $content = $_POST["message"];
    $command = 'OpenTicket';
    $values = array(
        'deptid' => '2',
        'name' => $fullname,
        'email' => $email,
        'subject' => "Presales message from $fullname",
        'message' => $content,
        'priority' => 'Medium',
        'userMarkdown' => 'true',
    );
    $results = localAPI($command, $values);
    if ($results['result'] == 'success') {
        echo 'Message sent successfully!';
    } else {
        echo "An Error Occurred: " . $results['message'];
    }
} else {
    // reCAPTCHA verification failed, handle accordingly
    echo "reCAPTCHA verification failed. Please complete the reCAPTCHA challenge.";
}
?>
