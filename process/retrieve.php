<?php 
    require_once "check.php";

    if (isset($_GET['id'])) {
        $user_id = $_GET['id'];

        $stmt = $con -> prepare("SELECT `sender`, `message`, `image` FROM chat WHERE (sender = ? AND receiver = ?) OR (receiver = ? AND sender = ?) ORDER BY id");
        $stmt -> bind_param("iiii", $user_id, $uid, $user_id, $uid);
        $stmt -> execute();
        $result = $stmt -> get_result();
        $c = $result -> num_rows;

        $get_user = $con -> prepare("SELECT username FROM user WHERE (id LIKE ?) LIMIT 1");
        $get_user -> bind_param("i", $user_id);
        $get_user -> execute();
        $user = $get_user -> get_result() -> fetch_assoc();

        if ($c < 1) {
            echo "<p class='info'>Envie sua primeira mensagem para " . $user['username'] . "</p>";
        } else {
            
        }
    }
?>