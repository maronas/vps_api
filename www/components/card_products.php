<div class="card p-2 fs-4 m-3 d-flex flex-column align-items-center justify-content-around"
     style="width: 14rem; height: fit-content">
    <div class="d-grid">
        <p class="mb-0">Pavadinimas</p>
        <p class="fw-bold"><?php echo $name; ?></p>
    </div>
    <div class="d-grid">
        <p class="mb-0">Kaina nuo</p>
        <p class="fw-bold"><?php echo $price; ?></p>
    </div>
    <button id="productid" name="productid" class="btn btn-success" value="<?php echo $id; ?>">Pasirinkti</button>
</div>