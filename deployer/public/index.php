<?php

require_once __DIR__.'/../lib.php';

if (!empty($_POST)) {
    $secret = array_get($_POST, 'secret');
    if ($secret === env('DEPLOYER_SECRET')) {
        $button = array_get($_POST, 'submit');
        if ($button === 'Deploy Staging') {
            deployStaging();
            $message = 'Deploying to staging';
        } else if ($button === 'Deploy Production') {
            deployProduction();
            $message = 'Deploying to production';
        } else if ($button === 'Clear Old Builds' ) {
            clearOldBuilds();
            $message = 'Old builds cleared';
        }
    } else {
        $message = 'Invalid secret';
    }
    $redirect = 'Location: http://' . $_SERVER['HTTP_HOST'] . '/?message=' . urlencode($message);
    header($redirect);
    return;
}

$message = array_get($_GET, 'message');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Abhayagiri Website Deployer</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body><div class="container">
<h1>Abhayagiri Website Deployer</h1>
<?php

$build = array_get($_GET, 'build');
if ($build) {

    $data = getBuild($build);

    ?><h2><?= e($build) ?></h2><?php

    if ($data) {

        ?><pre><?= e($data['contents']) ?></pre><?php

    } else {

        ?><div class="alert alert-danger">No such build.</div><?php

    }

    ?><p><a href=".">Back</a></p><?php

} else {

    ?><ul><?php

    foreach (getBuilds() as $data) {

        ?><li>
            <a href=?build=<?= e($data['build']) ?>>
                <?= e($data['build']) ?>
            </a>
        </li><?php
    }

    ?></ul><?php

?>

<hr>
<form action="index.php" method="POST">
<?php if ($message) { ?>
    <div class="alert alert-info"><?= e($message) ?></div>
<?php } ?>
<label for="secret">Secret</label><input name="secret" type="password">
<input type="submit" name="submit" value="Deploy Staging">
<input type="submit" name="submit" value="Deploy Production">
<input type="submit" name="submit" value="Clear Old Builds">
</form>

<?php

}

?>

</div></body>
</html>
