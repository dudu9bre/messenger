<?php 
    require_once "connection/connect.php";

    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['cpassword'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $cpassword = $_POST['cpassword'];

        if ($username == "" || $email == "" || $password == "" || $cpassword == "") {
            die(header("HTTP/1.0 401 Preencha todos os campos do formulário"));
        }

        $check_username = $con -> prepare("SELECT id FROM user WHERE username = ?");
        $check_username -> bind_param("s", $username);
        $check_username -> execute();

        $c = $check_username -> get_result() -> num_rows;

        if ($c > 0) {
            die(header("HTTP/1.0 401 Esse user já existe"));
        }

        $check_email = $con -> prepare("SELECT id FROM user WHERE email = ?");
        $check_email -> bind_param("s", $email);
        $check_email -> execute();

        $c = $check_email -> get_result() -> num_rows;

        if ($c > 0) {
            die(header("HTTP/1.0 401 Email já cadastrado"));
        } 

        if ($password != $cpassword) {
            die(header("HTTP/1.0 401 Senha incorreta"));
        }

        $password = password_hash($password, PASSWORD_DEFAULT);

        $token = bin2hex(openssl_random_pseudo_bytes((20)));
        $secure = rand(1000000, 99999999999);

        $stmt = $con -> prepare("INSERT INTO user (username, email, password, online, token, secure, creation) VALUES (?, ?, ?, now(), ?, ?, now())");
        $stmt -> bind_param("ssssi", $username, $email, $password, $token, $secure);
        $stmt -> execute();

        $get_user = $con -> prepare("SELECT id, token, secure FROM user WHERE email = ?");

        $get_user -> bind_param("s", $email);
        $get_user -> execute();
        $user = $get_user -> get_result() -> fetch_assoc();

        if ($stmt && $user) {
            setcookie("ID", $user['id'], time() + (10 * 365 * 24 * 60 * 60));
            setcookie("TOKEN", $user['token'], time() + (10 * 365 * 24 * 60 * 60));
            setcookie("SECURE", $user['secure'], time() + (10 * 365 * 24 * 60 * 60));
        } else {
            die(header("HTTP/1.0 401 Ocorreu um erro no banco de dados"));
        }
    } else {
        die(header("HTTP/1.0 401 Formulário inválido"));
    }
?>