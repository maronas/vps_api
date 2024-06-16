<?php
session_start();
require_once('../Auth.php');
?>
<!doctype html>
<html lang="en">
<?php include('../components/head.php'); ?>

<body class="d-flex flex-column justify-content-center">
<?php
include('../components/header.php');
?>
<div class="content">
    <?php
    include('../components/order_history_table.php');
    ?>
</div>
<?php
include('../components/footer.php');
?>
</body>
</html>