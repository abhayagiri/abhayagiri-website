SELECTION
----------------------------------------
<?php
    for ($x = 0; $x < count($title); $x++) {
        echo "
Title: {$title[$x]}
Author: {$author[$x]}
Quantity: {$quantity[$x]}
";
    }
?>

INFORMATION
----------------------------------------

Name: <?php echo $fname ?> <?php echo $lname ?>

Email: <?php echo $email ?>


Address:
<?php echo $address ?>

<?php echo $city ?> <?php echo $state ?> <?php echo $zip ?>

<?php echo $country ?>


Comments:
<?php echo $comments ?>
