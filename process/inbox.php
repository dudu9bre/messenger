<?php 
    require_once "check.php";
    require_once "connection/connect.php";

    $stmt = $con -> prepare("SELECT * FROM conversations WHERE (mainuser = ?) ORDER BY modification DESC");
    $stmt -> bind_param("i", $uid);
    $stmt -> execute();
    $result = $stmt -> get_result();
    $c = $result -> num_rows;

    if ($c < 1) {
        echo "<div class='empty'><p>Comece uma conversa!</p></div>";
    } else {
        while($inbox = $result -> fetch_assoc()) {
            $stmt = $con -> prepare("SELECT id, username, picture FROM user WHERE (id = ?) LIMIT 1");
            $stmt -> bind_param("i", $inbox['otheruser']);
            $stmt -> execute();
            $user = $stmt -> get_result() -> fetch_assoc();

            if ($user) {
?>
                <div class="chat <?php if ($inbox['unread'] == "y") { echo "new"; } ?>" onclick="chat(<?= $user['id'] ?>)">
                    <img src="profilepics/<?= $user['picture'] ?>">
                    <p><?= $user['username'] ?></p>
                </div>
<?php                
            }
        }
    }
?>