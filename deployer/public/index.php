<?php

require_once __DIR__.'/../lib.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Staging Auto-Deployer</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body><div class="container">
<h1>Staging Auto-Deployer</h1>
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

}

?>
</div></body>
</html>
