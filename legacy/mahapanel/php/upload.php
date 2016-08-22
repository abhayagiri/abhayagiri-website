<?php

require base_path('legacy/bootstrap.php');
require base_path('legacy/mahapanel/php/session.php');

// HACK (2016-08-02): Map the directories to known locations. This also acts as
// a whitelist.
//
// SELECT DISTINCT upload_directory FROM columns WHERE upload_directory != '' ORDER BY upload_directory ASC;

$dirMap = array(
    '/mahapanel/img/mahaguild' => public_path('media/mahaguild'),
    '/www/media/audio' => public_path('media/audio'),
    '/www/media/books' => public_path('media/books'),
    '/www/media/images/books' => public_path('media/images/books'),
    '/www/media/images/residents' => public_path('media/images/residents'),
    '/www/media/images/uploads' => public_path('media/images/uploads'),
);

$dir = $dirMap[$_REQUEST['dir']];

if (!$dir) {
    throw new Exception('Invalid upload directory: ' . $_REQUEST['dir']);
}

$name = $_REQUEST['name'];

$error = "";
$msg = "";
if (!empty($_FILES[$name]['error'])) {
    switch ($_FILES[$name]['error']) {

        case '1':
            $error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
            break;
        case '2':
            $error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
            break;
        case '3':
            $error = 'The uploaded file was only partially uploaded';
            break;
        case '4':
            $error = 'No file was uploaded. File may already exist';
            break;

        case '6':
            $error = 'Missing a temporary folder';
            break;
        case '7':
            $error = 'Failed to write file to disk';
            break;
        case '8':
            $error = 'File upload stopped by extension';
            break;
        case '999':
        default:
            $error = 'No error code avaiable';
    }
} elseif (empty($_FILES[$name]['tmp_name']) || $_FILES[$name]['tmp_name'] == 'none') {
    $error = 'No file was uploaded..';
} else {
    //$msg .= " File Name: " . $_FILES[$name]['name'] . ", ";
    //$msg .= " File Size: " . @filesize($_FILES[$name]['tmp_name']);
    //for security reason, we force to remove all uploaded file
    //@unlink($_FILES[$name]);
    $file = $_FILES[$name]['tmp_name'];
    $name = $_FILES[$name]['name'];
    $name = str_replace("jpg","JPG",$name);
    $file = str_replace("jpg","JPG",$file);

    move_uploaded_file($file, "$dir/$name");
}
echo "{";
echo "error: \"$error\",\n";
echo "msg: \"$dir/$name\"\n";
echo "}";
