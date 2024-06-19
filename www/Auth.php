<?php

use GuzzleHttp\Exception\ClientException;

require_once('Api.php');
require_once('DbSingleton.php');
require_once('PDOSingleton.php');

class Auth
{
    public function tryRegister()
    {
        $api = new Api();
        $success = "Užsiregistravote! Galite <a href='../pages/login.php' class='text-decoration-none text-warning'>prisijungti</a>.";
        $error_email_exists = "Vartotojas su tokių el.paštų jau egzistuoja... Bandykite kitą...";
        $error_email_format = "Blogas el. pašto formatas.";
        $error_429 = "Per daug užklausų atlikote, palaukite 15min...";

        if (!isset($_SESSION['token']) || !($_POST['token'] == $_SESSION['token'])) {
            $this->displaySuccess($success);
            exit();
        }
        $credentials = array(
            "type" => $_POST["type"],
            "email" => $_POST["email"],
            "password" => $_POST["password"],
            "firstname" => $_POST["firstname"],
            "lastname" => $_POST["lastname"],
            "country" => $_POST["country"],
            "address1" => $_POST["address1"],
            "city" => $_POST["city"],
        );
        if ($_POST['type'] == 'company') {
            $credentials[] = array(
                "companyname" => $_POST['companyname'],
                "companyregistrationnumber" => $_POST['companyregistrationnumber'],
                "vateu" => $_POST['vateu'],
            );
        }
        if (isset($_POST['advanced_reg_form'])) {
            $credentials[] = array(
                "emarketing" => $_POST['emarketing'],
                "2faenable" => $_POST['2faenable'],
                "2fasecret" => $_POST['2fasecret'],
                "currency" => $_POST['currency'],
            );
        }

        try {
            $signupresp = $api->signUp($credentials);
        }catch (ClientException $exception){
            if($exception->getResponse()->getStatusCode() === 429){
                $this->displayError($error_429);
            }
        }
        if(isset($signupresp)){
            if (!empty($signupresp['info'])) {
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                $password = $_POST['password'];
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->displayError($error_email_format);
                    exit();
                }
                $stmt = DbSingleton::getConnection()
                    ->prepare("SELECT * FROM `users` WHERE email = '$email'");
                $stmt->execute();
                if ($stmt->get_result()->num_rows > 0) {
                    $this->displayError($error_email_exists);
                }
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = DbSingleton::getConnection()->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $email, $hashedPassword);
                if ($stmt->execute()) {
                    $this->displaySuccess($success);
                } else {
                    $this->displayError("Registration failed: " . $stmt->error);
                }
                $stmt->close();
                unset($_SESSION['token']);
            } elseif($signupresp['error'][0] === "useralreadyexistsemail_error"){
                $this->displayError($error_email_exists);
            } else{
                $this->displayError("Įvyko klaida...");
            }
        }
    }

    public function tryLogin(): void
    {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $stm = PDOSingleton::getConnection()->prepare("SELECT password, id FROM `users` WHERE email = ?");
            $stm->bindParam(1, $email);
            $stm->execute();
            $stm->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stm->fetch();
            print_r($row);
            if (isset($row)) {
                $hashedPassword = $row['password'];
                $user_id = $row['id'];
                $_SESSION['user_id'] = $user_id;
                if (password_verify($password, $hashedPassword)) {
                    $credentials = $email . ":" . $_POST['password'];
                    $_SESSION['credentials'] = base64_encode($credentials);
                } else {
                    $this->displayError("Neteisingas slaptažodis...");
                }
            } else {
                $this->displayError("Nėra vartotojo su tokiu el-paštu...");
            }
        } else {
            $this->displayError("Užpildikite VISUS laukus!");
        }
    }

    public function displaySuccess(string $string): void
    {
        echo
            "
        <div class='d-flex justify-content-center text-success'>
            <ul>
                <li>" . $string . "</li>
            </ul>
        </div>
        ";
    }

    public function displayError(string $string): void
    {
        echo
            "
            <div class='d-flex justify-content-center text-danger'>
                <ul>
                    <li>" . $string . "</li>
                </ul>
            </div>
            ";
    }
}