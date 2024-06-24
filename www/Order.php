<?php

require_once('Api.php');
require_once('PDOSingleton.php');

class Order
{
    function displayCategories()
    {
        $api = new Api();
        $cats = $api->fetchCategories();
        if (is_array($cats)) {
            $needleSlugs = ['linux-vps', 'windows-vps', 'container-vps', 'storage-vps'];
            $cats = array_filter($cats, fn ($n) => in_array($n['slug'], $needleSlugs));
            foreach ($cats as $ct) {
                if (!empty($ct)) {
                    $name = $ct['name'];
                    $id = $ct['id'];
                    require('../components/card_category.php');
                }
            }
        } else {
            echo
                "
                <div class='d-flex justify-content-center text-danger'>
                    <ul>
                        <li>" . $cats . "</li>
                    </ul>
                </div>
            ";
        }
    }

    function displayProducts($category_id)
    {
        $api = new Api();
        $prods = $api->fetchProducts($category_id);
        $price = '';
        if (is_array($prods)) {
            foreach ($prods as $prod) {
                $name = $prod['name'];
                $id = $prod['id'];
                if (isset($prod['periods'])) {
                    foreach ($prod['periods'] as $prod_period) {
                        if ($prod_period['value'] === 'm') {
                            $price = $prod_period['price'];
                        }
                    }
                }
                require('../components/card_products.php');
            }
        } else {
            echo
                "
                <div class='d-flex justify-content-center text-danger'>
                    <ul>
                        <li>" . $prods . "</li>
                    </ul>
                </div>
            ";
        }
    }

    function displaySingleProduct($product_id): void
    {
        $api = new Api();
        $response_payment_periods = $api->fetchProductInfo($product_id);
        $response_payment_methods = $api->fetchPayments();
        $category = $response_payment_periods['product']['category_name'];
        $name = $response_payment_periods['product']['name'];
        $description = $response_payment_periods['product']['description'];
        $id = $product_id;

        require('components/single_product/card_single_product.php');
    }

    function orderProduct(): void
    {
        $api = new Api();
        $order_success = "<h1 class='text-light w-100'>Užsakymas sėkmingai atliktas, galite pereiti į atliktų užsakymų <a href='../pages/order_history.php' class='text-decoration-none text-warning auth__link'>istorija</a></h1>";
        $order_repeat = "<h1 class='text-light w-100'>Kątik padarete toki užsakymą... Peržiūrėkite užsakymų <a href='../pages/order_history.php' class='text-decoration-none text-warning auth__link'>istorija</a></h1>";
        $order_error = "<h1>Įvyko klaida</h1>";

        if (!isset($_SESSION['token']) || !($_POST['token'] == $_SESSION['token'])) {
            $this->displayInfo($order_repeat);
            exit();
        } else {
            $data = [
                "domain" => $_POST['main_prod_domain'],
                "cycle" => $_POST['main_prod_payment'],
                "pay_method" => $_POST['main_prod_payment_method'],
                "promocode" => $_POST['main_prod_promocode'],
            ];

            $response = $api->orderProduct($data, $_POST['productid']);
            if (isset($response)) {
                $stmt = PDOSingleton::getConnection()->prepare("INSERT INTO `orders` (user_id ,order_number, order_id, invoice_id, product_id, service_type, service_name, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $data_set = array($_SESSION['user_id'], $response['order_number'], $response['order_id'], $response['invoice_id'], $response['product_id'], $response['service_type'], $response['service_name'], $response['total_price']);
                if ($stmt->execute($data_set)) {
                    $this->displayInfo($order_success);
                    unset($_SESSION['token']);
                } else {
                    $this->displayInfo($order_error);
                }
            }
        }
    }

    function fetchOrderHistory(): void
    {
        $api = new Api();
        $stmt = PDOSingleton::getConnection()->prepare("SELECT * FROM `orders` WHERE user_id = ?");
        $stmt->bindParam(1, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $redis = new Redis();
        $redis->connect('redis_server', 6379);
        try{
            $redis_key = 'product_specifications';
            if ($redis->exists($redis_key)) {
                $results_history = json_decode($redis->get($redis_key), true);
            } else {
                $results_history = [];
            }
            while ($row = $stmt->fetch()) {
                if (array_key_exists($row['product_id'], $results_history)) {
                    $this->orderHistoryDisplayRow($row['service_type'], $results_history[$row['product_id']], $api->orderHistoryDateCreated($row['order_id']));
                } else {
                    // Fetch data from the API only if not already in Redis
                    $response_config = $api->orderHistory($row['product_id'], $row['order_id']);
                    $results_history[$row['product_id']] = $response_config['config'];

                    // Update the Redis cache with the new specification
                    $redis->set($redis_key, json_encode($results_history));
                    $this->orderHistoryDisplayRow($row['service_type'], $results_history[$row['product_id']], $api->orderHistoryDateCreated($row['order_id']));
                }
            }
        }catch (Exception $e){
            echo "Error: " . $e->getMessage();
        }
//        BE REDISO
//
//        $results_history = [];
//        while ($row = $stmt->fetch()) {
//            if(array_key_exists($row['product_id'], $results_history)){
//                $this->orderHistoryDisplayRow($row['service_type'], $results_history[$row['product_id']], $api->orderHistoryDateCreated($row['order_id']));
//            }else{
//                $response_config = $api->orderHistory($row['product_id'], $row['order_id']);
//                $results_history += [
//                    $row['product_id'] => $response_config['config'],
//                ];
//                $this->orderHistoryDisplayRow($row['service_type'], $results_history[$row['product_id']], $api->orderHistoryDateCreated($row['order_id']));
//            }
//        }
    }

    function orderHistoryDisplayRow($type, $config, $date){
        echo "<tr>";
        echo "<td>" . $type . "</td>";
        echo "<td>";
        echo $config;
        echo "</td>";
        echo "<td>" . $date . "</td>";
        echo "</tr>";
    }

    function displayInfo($string): void
    {
        echo "<div class='d-flex justify-content-center text-danger'>
                    <ul>
                        <li>" . $string . "</li>
                    </ul>
              </div>";
    }
}

?>