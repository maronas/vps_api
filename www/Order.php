<?php

require_once('Api.php');
require_once('DbSingleton.php');

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
                $stmt = DbSingleton::getConnection()->prepare("INSERT INTO `orders` (user_id ,order_number, order_id, invoice_id, product_id, service_type, service_name, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iiiiissd", $_SESSION['user_id'], $response['order_number'], $response['order_id'], $response['invoice_id'], $response['product_id'], $response['service_type'], $response['service_name'], $response['total_price']);
                if ($stmt->execute()) {
                    $this->displayInfo($order_success);
                    unset($_SESSION['token']);
                    $stmt->close();
                } else {
                    $this->displayInfo($order_error);
                }
            }
        }
    }

    function fetchOrderHistory(): void
    {
        $api = new Api();
        $stmt = DbSingleton::getConnection()->prepare("SELECT * FROM `orders` WHERE user_id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $meta = $stmt->result_metadata();
        while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
        }
        call_user_func_array(array($stmt, 'bind_result'), $params);
        while ($stmt->fetch()) {
            echo "<tr>";
            $response = $api->orderHistory($row['product_id'], $row['order_id']);
            echo "<td>" . $row['service_type'] . "</td>";
            echo "<td>";
            print_r($response['config']);
            echo "</td>";
            echo "<td>" . $response['date_created'] . "</td>";
            echo "</tr>";
        }
        $stmt->free_result();
        $stmt->close();
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