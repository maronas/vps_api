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

    <div id="loading">Loading, please wait...</div>
    <div class="table-responsive">
        <table class="table table-striped table-dark">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Serverio tipas</th>
                <th scope="col">Serverio konfigūracija</th>
                <th scope="col">Užsakymo data</th>
            </tr>
            </thead>
            <tbody id="order-history">
<!--                    --><?php //include('../components/order_history_table.php');?>
            </tbody>
        </table>
    </div>

</div>
<?php
include('../components/footer.php');
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        loadOrderHistory();
    });

    function loadOrderHistory() {
        document.getElementById('loading').style.display = 'block';
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById('loading').style.display = 'none';
                document.getElementById('order-history').innerHTML = xhr.responseText;
            }
        };
        xhr.open('GET', "../components/order_history_table.php?user_id=<?php echo $_SESSION['user_id'];?>&credentials=<?php echo $_SESSION['credentials'];?>", true);
        xhr.send();
    }
</script>
</body>
</html>