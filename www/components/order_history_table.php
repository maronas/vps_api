<?php
include_once('../Order.php');
$order = new Order();
?>
<div class="table-responsive">
    <table class="table table-striped table-dark">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Serverio tipas</th>
            <th scope="col">Serverio konfigūracija</th>
            <th scope="col">Užsakymo data</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $order->fetchOrderHistory();
        ?>
        </tbody>
    </table>
</div>