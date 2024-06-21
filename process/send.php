<?php 
    require_once "check.php";

    if (isset($_POST['message']) && isset($_POST['id'])) {
        $user_id = $_POST['id'];
        $message = $_POST['message'];
        $image = "";

        if ($_FILES['image']['error'] <= 0 && $user_id != "") {
            $image = $username . "_MESSAGE_" . rand(999, 999999) . $_FILES['img_inp']['name'];
            $image_temp = $_FILES['img_imp']['tmp_name'];
            $image_path = "../uploads/";

            if (is_uploaded_file($image_temp)) {
                if (move_uploaded_file($image_temp, $image_path, $image)) {
                    echo "ok!";
                } else {
                    die(header("HTTP/1.0 401 Erro ao carregar imagem ao banco de dados"));
                }
            } else {
                die(header("HTTP/1.0 401 Erro ao carregar imagem"));
            }
        } else if ($message == "" && $user_id == "") {
            die(header("Erro"));
        }

        $check_conversation = $con -> prepare("SELECT * FROM conversations WHERE (mainuser = ? AND otheruser = ?)");
        $check_conversation -> bind_param("ii", $uid, $user_id);
        $check_conversation -> execute();
        $c = $check_conversation -> get_result() -> num_rows;

        if ($c < 1) {
            $create_chat = $con -> prepare("INSERT INTO conversations (mainuser, otheruser, unread, creation) VALUES (?, ?, 'n', now())");
            $create_chat -> bind_param("ii", $uid, $user_id);
            $create_chat -> execute();

            $create_chat2 = $con -> prepare("INSERT INTO conversations (mainuser, otheruser, unread, creation) VALUES (?, ?, 'y', now())");
            $create_chat2 -> bind_param("ii", $user_id, $uid);
            $create_chat2 -> execute();
        } else {
            $update = $con ->  prepare("UPDATE conversations SET unread = 'y' WHERE (mainuser = ? AND otheruser = ?)");
            $update -> bind_param("ii", $user_id, $uid);
            $update -> execute();
        }

    } else {
        die(header("HTTP/1.0 401 Erro"));
    }
?>