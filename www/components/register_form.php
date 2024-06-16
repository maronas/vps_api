<?php
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
?>

<form method="POST"
      class="form__authorisation ms-auto me-auto mt-3 row g-3">
    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
    <div class="mb-3">
        <select class="form-select" name="type" id="type" required>
            <option>* Paskyros tipas</option>
            <option value="private">Privatus</option>
            <option value="company">Organizacija</option>
        </select>
    </div>
    <div class="companyInput form-floating mb-3 col-md-6">
        <input type="text" class="form-control" name="companyname" placeholder="Organizacijos pavadinimas"/>
        <label for="companyname">Organizacijos pavadinimas</label>
    </div>
    <div class="companyInput form-floating mb-3 col-md-6">
        <input type="text" class="form-control" name="companyregistrationnumber" placeholder="Registravimo numeris"/>
        <label for="companyregistrationnumber">Registravimo numeris</label>
    </div>
    <div class="companyInput form-floating mb-3 col-md-12">
        <input type="text" class="form-control" name="vateu" placeholder="VAT"/>
        <label for="vateu">VAT</label>
    </div>
    <div class="form-floating mb-3 col-md-6">
        <input type="text" class="form-control" name="firstname" placeholder="Vardas" required>
        <label for="firstname">Vardas</label>
    </div>
    <div class="form-floating mb-3 col-md-6">
        <input type="text" class="form-control" name="lastname" placeholder="Pavardė" required>
        <label for="lastname">Pavardė</label>
    </div>
    <div class="form-floating mb-3 col-md-6">
        <input type="email" class="form-control" name="email" placeholder="E-mail" required/>
        <label for="email">El. paštas</label>
    </div>
    <div class="form-floating mb-3 col-md-6">
        <input type="password" class="form-control" name="password" placeholder="Slaptažodis" required>
        <label for="password">Slaptažodis</label>
    </div>
    <div class="form-floating mb-3 col-md-6">
        <input type="text" class="form-control" name="country" placeholder="Šalis" required>
        <label for="country">Šalis</label>
    </div>
    <div class="form-floating mb-3 col-md-6">
        <input type="text" class="form-control" name="city" placeholder="Miestas" required>
        <label for="city">Miestas</label>
    </div>
    <div class="form-floating mb-3">
        <input type="text" class="form-control" name="address1" placeholder="Adresas" required>
        <label for="address1">Adresas</label>
    </div>
    <div class="form-floating mb-3">
        <input type="text" class="form-control" name="postcode" placeholder="Pašto kodas" required>
        <label for="postcode">Pašto kodas</label>
    </div>
    <div class="register__expend form-floating mb-3" style="display: none;">
        <input type="text" class="form-control" name="emarketing" placeholder="emarketing">
        <label for="emarketing">E-marketing</label>
    </div>
    <div class="register__expend form-check mb-3 d-flex align-items-center" style="display: none;">
        <input type="checkbox" class="register__expend form-check-input ms-0" name="2faenable" id="2faenable"
               style="display: none;">
        <label for="2faenable" class="register__expend ms-3 text-light" style="display: none;">Two factory auth.</label>
    </div>
    <div class="register__expend form-floating mb-3" style="display: none;">
        <input type="text" class="form-control" name="2fasecret" id="fasecret" placeholder="Kodas iš 2factory auth.">
        <label for="2fasecret">Kodas iš 2factory auth.</label>
    </div>
    <div class="register__expend mb-3" style="display: none;">
        <select class="form-select" name="currency">
            <option>Pasirinkite valiutą</option>
            <option value="EUR" selected>EUR</option>
        </select>
    </div>
    <div class="mb-3 d-flex flex-row-reverse">
        <input type="checkbox" class="btn-check align-self-end" id="advanced_form" name="advanced_reg_form"
               autocomplete="off">
        <label class="btn btn-outline-primary" for="advanced_form">Advanced</label>
    </div>
    <div class="mb-3">
        <button type="submit" class="btn btn-success btn-lg">Registruotis</button>
    </div>
</form>