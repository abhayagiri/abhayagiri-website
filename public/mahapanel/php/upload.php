<?php

$dir = $_REQUEST['dir'];
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
    move_uploaded_file($file, "/home/abhayagiri$dir/$name");
}
echo "{";
echo "error: '" . $error . "',\n";
echo "msg: '/home/abhayagiri$dir/$name'\n";
echo "}";
?>