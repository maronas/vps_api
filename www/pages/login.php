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
    <h2 class="text-light">Prisijunkite</h2>
    <?php
    $auth = new Auth();
    if(!isset($_SESSION['credentials'])){
        if (isset($_POST['email'])) {
            $auth->tryLogin();
        }
        include('../components/login_form.php');
    }
    ?>
</div>
<?php
include('../components/footer.php');
?>
</body>
</html>

<script>
    let storage = "<?php echo $_SESSION['credentials'];?>";
    if(storage.length > 0){
        window.location.replace("/");
    }
</script>