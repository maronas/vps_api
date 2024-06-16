<div class="card p-3">
    <!--    Main info-->
    <div class="bg-light p-3 m-2">
        <p class="fs-5 fw-bolder">Paslaugos kategorija: <span
                    class="text-decoration-underline"><?php echo $category ?></span></p>
        <p class="fs-5 fw-bolder">Paslaugos pavadinimas: <span
                    class="text-decoration-underline"><?php echo $name; ?></span></p>
        <p class="fs-5 fw-bolder">Paslaugos id: <span class="text-decoration-underline"><?php echo $id; ?></span></p>
        <input name="productid" value="<?php echo $id; ?>" hidden="">
        <?php print_r($description); ?>
    </div>
    <?php
    //       Produkto konfiguracija
    require('../components/single_product/card_product_config.php');

    ?>
    <div>
        <button name="order_product" type="submit" class="btn btn-success btn-lg">Pereiti į apmokėjimą</button>
    </div>
</div>