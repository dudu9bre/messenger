<?php 
    require_once "check.php";

    if (isset($_GET['id'])) {
        $user_id = $_GET['id'];

        $update = $con -> prepare("UPDATE `conversations` SET unread = 'n' WHERE (mainuser = ? AND otheruser = ?)");
        $update -> bind_param("ii", $uid, $user_id);
        $update -> execute();

        if (!$update) {
            die(header("HTTP/1.0 401 Erro"));
        }

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
            while($message = $result -> fetch_assoc()) {
                if ($message['sender'] == $uid && $message['image'] != "") {
?>
                    <div class="row sent">
                        <img src="uploads/<?= $message['image'] ?>">
                    </div>
<?php
                } else if ($message['sender'] == $uid) {
?>
                    <div class="row sent">
                        <p><?= $message['message'] ?></p>
                    </div>
<?php
                } else if ($message['image'] != "") {
?>
                    <div class="row received">
                        <img src="uploads/<?= $message['image'] ?>">
                    </div>
<?php
                } else {
?>
                    <div class="row received">
                        <p><?= $message['message'] ?></p>
                    </div>
<?php
                }
            }
        }
    }
?>