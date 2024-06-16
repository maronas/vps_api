<?php session_start()?>
<!doctype html>
<html lang="en">
<?php include('components/head.php'); ?>
<body class="d-flex flex-column justify-content-center">
    <?php
    include('components/header.php');
    ?>
    <div class="content">
        <?php
        if (isset($_SESSION['credentials'])) {
            include "components/home_user.php";
        } else {
            include "components/home_guest.php";
        }
        ?>
    </div>
    <?php
    include('components/footer.php');
    ?>
</body>
</html>
