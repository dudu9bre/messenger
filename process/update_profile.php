<?php 
    require_once "check.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $image_name = $username . "_" . rand(999, 999999) . $_FILES['img_inp']['name'];
        $image_temp = $_FILES['img_inp']['tmp_name'];
        $image_path = "../profilepics/";

        if (is_uploaded_file($image_temp)) {
            if (move_uploaded_file($image_temp, $image_path . $image_name)) {
                $stmt = $con -> prepare("UPDATE user SET `picture` = ? WHERE id = ?");
                $stmt -> bind_param("si", $image_name, $uid);
                $stmt -> execute();

                if (!$stmt) {
                    die(header("HTTP/1.0 Erro ao carregar imagem no banco de dados"));
                }
            } else {
                die(header("HTTP/1.0 401 Erro ao carregar imagem"));
            }
        } else {
            die(header("HTTP/1.0 401 Falha ao alterar imagem"));
        }
    } else {
        die(header("HTTP/1.0 401 Formulário inválido"));
    }
?>