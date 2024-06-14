<?php 
    require_once "./check.php";


    function timing($time) {
        $time = time() - $time;
        $time = ($time < 1) ? 1 : $time;
        $tokens = [
            31536000 => "ano"
            , 2592000 => "mês"
            , 604800 => "semana"
            , 86400 => "dia"
            , 3600 => "hora"
            , 60 => "minuto"
            , 1 => "segundo"
        ];

        foreach($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $number_of_unities = floor($time / $unit);
            if ($text == "segundo") {
                return "agora mesmo";
            }
            return $number_of_unities . " " . $text . (($number_of_unities > 1) ? "s" : "");
        }
    }

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        die(header("HTTP/1.0 401 Erro!"));
    }

    if ($id == 0) {
        $id = $uid;
?>
        <form method="post" enctype="multipart/form-data" id="upload_picture">
            <input type="file" name="img_inp" accept="image/x-png, image/jpeg" id="img_inp" hidden>
            <div class="picture_container">
                <img id="user_img" src="profilepics/<?= $user_picture ?>">
                <label for="img_inp"></label>
            </div>
        </form>
<?php
    } else {
        $stmt = $con -> prepare("SELECT id, username, picture, online, creation FROM user WHERE (id = ? AND token LIKE ? AND secure = ?)");
        $stmt -> bind_param("isi", $id, $token, $secure);
        $stmt -> execute();
        $user = $stmt -> get_result() -> fetch_assoc();

        if (!$user) {
            die(header("HTTP/1.0 401 Erro"));
        } else {
            $username = $user['username'];
            $user_picture = $user['picture'];
            $user_online = $user['online'];
            $user_creation = date("d/m/Y", strtotime($user['creation']));
        }

?>
        <div class="picture_container">
            <img id="user_img" src="profilepics/<?= $user_picture ?>">
        </div>
<?php
    }
?>
        <p class="name"><?= $username ?></p>
        <p class="row">Online <? timing($user_online) ?></p>
        <p class="row">Membro desde <?= $user_creation ?></p>