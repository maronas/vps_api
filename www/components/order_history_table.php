<?php
include_once('../Order.php');

$_SESSION['user_id'] = $_GET['user_id'];
$_SESSION['credentials'] = $_GET['credentials'];

if (isset($_GET['user_id'])){
    $order = new Order();
    echo $order->fetchOrderHistory($_GET['user_id']);
}
?>
