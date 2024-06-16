<?php
session_start();
require_once('../Auth.php');

$auth = new Auth();
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
<div class="content">
    <h2 class="text-light">Sukurti naują paskyrą</h2>
    <?php
    if (isset($_POST['type'])) {
        $auth->tryRegister();
    } else {
        $auth->displayError('Pasirinkite paskyros tipą...');
    }
    ?>

    <?php
    include('../components/register_form.php');
    ?>
</div>
<?php
include('../components/footer.php');
?>
</body>
</html>

<script>
    $(document).ready(function () {
        $('#type').on('change', function () {
            var selectedType = $(this).val();

            if (selectedType === "private") {
                $('.companyInput').hide();
                $('.companyInput').prop('required', false);
            } else if (selectedType === "company") {
                $('.companyInput').show();
                $('.companyInput').prop('required', true);
            } else {
                $('.companyInput').hide();
                $('.companyInput').prop('required', false);
            }
        });

        $('#advanced_form').on('click', function () {
            if ($('.register__expend').is(":hidden")) {
                $('.register__expend').show();
                $('.register__expend').prop('required', true);
            } else {
                $('.register__expend').hide();
                $('.register__expend').prop('required', false);
            }
        });
    });
</script>