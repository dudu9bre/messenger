<?php 
    require_once "connection/connect.php";

    function timing($time) {
        $time = time() - 10800 - $time;
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
            if ($time < $unit) {
                continue;
            }
            $number_of_unities = floor($time / $unit);
            if ($unit == 1 && $number_of_unities < 60) {
                return "agora mesmo";
            }
            return "há " . $number_of_unities . " " . $text . (($number_of_unities > 1) ? "s" : "");
        }
    }

    if (isset($_COOKIE['ID']) && isset($_COOKIE['TOKEN']) && isset($_COOKIE['SECURE'])) {
        $id = $_COOKIE['ID'];
        $token = $_COOKIE['TOKEN'];
        $secure = $_COOKIE['SECURE'];

        $stmt = $con -> prepare("SELECT id, username, picture, online, creation FROM user WHERE (id = ? AND token LIKE ? AND secure = ?)");
        $stmt -> bind_param("isi", $id, $token, $secure);
        $stmt -> execute();

        $me = $stmt -> get_result() -> fetch_assoc();

        if (!$me) {
            die(header("<script>
                location.href = 'auth.html'
            </script>"));
        } else {
            $uid = $me['id'];
            $username = $me['username'];
            $user_picture = $me['picture'];
            $user_online = strtotime($me['online']);
            $user_creation = date("d/m/Y", strtotime($me['creation']));

            $stmt = $con -> prepare("UPDATE user SET `online` = now() WHERE id = ?");
            $stmt -> bind_param("i", $uid);
            $stmt -> execute();
        }
    } else {
        die("<script>
                location.href = 'auth.html'
            </script>");
    }
?>