<?php

require_once __DIR__ . '/../www-bootstrap.php';

$db = Abhayagiri\DB::getDB();

foreach ($_POST as $key => $value) {
    $$key = $value;
}

switch ($page) {
    case "contact":
        $mailcheck = spamcheck($email);
        if ($mailcheck == false) {
            echo 0;
        } else {
            $subject = 'Message from ' . $name . '(' . $email . ')';
            $message = $message;
            $from = $email;
            $headers = "From:" . Config::get('abhayagiri.mail.contact_from') . "\r\n" .
                    'Reply-To:' . $from . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
            mail(Config::get('abhayagiri.mail.contact_to'), $subject, $message, $headers);
            echo 1;
        }
        break;
    case "request":
//post

        $selection = "";
        for ($x = 0; $x < count($title); $x++) {
            $selection.="
                        Title: {$title[$x]}
                        Author: {$author[$x]}
                        Quantity: {$quantity[$x]}
                        ";
        }
        //subject
        $subject = "Book Request from $fname $lname";
        //message
        $message = "
                        SELECTION
                        ----------------------------------------
                        $selection
                        INFORMATION
                        ----------------------------------------
                        Name: $fname $lname

                        Email: $email

                        Address:
                        $address
                        $city $state $zip
                        $country

                        Comments:
                        $comments
                        ";
        //from
        $from = $email;
        //headers
        $headers = "From:" . Config::get('abhayagiri.mail.book_request_from') . "\r\n" .
                'Reply-To:' . $email . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
        mail(Config::get('abhayagiri.mail.book_request_to'), $subject, $message, $headers);
        $_SESSION['books'] = array();
        echo 1;
        break;
}

function spamcheck($field) {
    $field = filter_var($field, FILTER_SANITIZE_EMAIL);
    if (filter_var($field, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

?>
