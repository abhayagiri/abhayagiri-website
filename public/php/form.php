<?php

require_once __DIR__.'/../www-bootstrap.php';

switch ($_POST['page']) {
    case "contact":
        echo mailContact();
        break;
    case "request":
        echo mailBookRequest();
        $_SESSION['books'] = array();
        break;
    default:
        echo 0;
        break;
}

function mailContact()
{
    $name = array_get($_POST, 'name', '');
    $email = array_get($_POST, 'email', '');
    $content = array_get($_POST, 'message', '');

    if (!spamcheck($email)) {
        return 0;
    }

    Mail::send(['text' => 'mail.contact'], ['content' => $content],
               function ($message) use ($name, $email) {
        $message->from(Config::get('abhayagiri.mail.contact_from'));
        $message->replyTo($email, $name);
        $message->to(Config::get('abhayagiri.mail.contact_to'));
        $message->subject("Message from $name");
    });

    return 1;
}

function mailBookRequest()
{
    $form = array_only($_POST, [
        'title', 'author', 'quantity',
        'fname', 'lname', 'email',
        'address', 'city', 'state', 'zip', 'country',
        'comments',
    ]);

    $name = "{$form['fname']} {$form['lname']}";
    $email = $form['email'];

    Mail::send(['text' => 'mail.book_request'], $form,
               function ($message) use ($email, $name) {
        $message->from(Config::get('abhayagiri.mail.book_request_from'));
        $message->replyTo($email, $name);
        $message->to(Config::get('abhayagiri.mail.book_request_to'));
        $message->subject("Book Request from $name");
    });

    return 1;
}

function spamcheck($field) {
    $field = filter_var($field, FILTER_SANITIZE_EMAIL);
    if (filter_var($field, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}
