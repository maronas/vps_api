<?php
session_start();
require_once('../Api.php');
require_once('../Order.php');

$api = new Api();
$order = new Order();

if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

?>

<!doctype html>
<html lang="en">
<?php
include('../components/head.php');
?>
<body class="d-flex flex-column justify-content-center">
<?php
include('../components/header.php');
?>
<div class="content align-content-between">
    <form method="post" action="" class="d-flex flex-wrap justify-content-center w-100">
        <?php
        if (isset($_SESSION['credentials'])) {
            if(!isset($_POST['order_product'])){
                if (isset($_POST['productid'])) {
                    $order->displaySingleProduct($_POST['productid']);
                } elseif (isset($_POST['categoryid'])) {
                    echo "<h1 class='text-light w-100'>Pasirinkite produktą</h1>";
                    $order->displayProducts($_POST['categoryid']);
                } elseif (!isset($_POST['productid']) && !isset($_POST['categoryid'])) {
                    echo "<h1 class='text-light w-100'>Pasirinkite kategorija ( OS )</h1>";
                    $order->displayCategories();
                }
            }else{
                $order->orderProduct();
            }
        } else {
            ?>
            <p>Esate neprisijunges, sukurkite <a href="register.php">naują paskyrą</a>, arba <a href="login.php">prisijunkite</a>.
            </p>
            <?php
        }
        ?>
    </form>
</div>
<?php
include('../components/footer.php');
?>
</body>
</html>


