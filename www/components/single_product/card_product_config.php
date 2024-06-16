<div class="bg-light p-3 m-2">
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>">
    <span class="fs-5 fw-bolder">Pasirinkite mokėjimo tarifą:</span>
    <select class="form-select mb-3" name="main_prod_payment">
        <?php
        foreach ($response_payment_periods['product']['config']['product'] as $cycle) {
            foreach ($cycle['items'] as $pay) {
                echo "<option value='" . $pay['value'] . "'>" . $pay['formatted'] . "</option>";
            }
        }
        ?>
    </select>

    <div class="mb-3">
        <span class="fs-5 fw-bolder">Pasirinkite mokėjimo būdą:</span>
        <select class="form-select" name="main_prod_payment_method">
            <?php
            foreach ($response_payment_methods['payments'] as $id => $name) {
                echo "<option value='" . $id . "'>" . $name . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-floating mb-3">
        <input type="text" class="form-control" name="main_prod_promocode" placeholder="Nuolaidos kodas">
        <label for="main_prod_promocode" class="form-label">Nuolaidos kodas</label>
    </div>
    <div class="form-floating mb-3">
        <input type="text" class="form-control" name="main_prod_domain" placeholder="Domenas">
        <label for="main_prod_domain" class="form-label">Domenas</label>
    </div>
</div>