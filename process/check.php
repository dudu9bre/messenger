<?php 
    require_once "connection/connect.php";

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