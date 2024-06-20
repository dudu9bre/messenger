<?php 
    require_once "check.php";

    if ($_GET['term']) {
        $username = mysqli_real_escape_string($con, $_GET['term']);
        $stmt = $con -> prepare("SELECT id, username, picture FROM user WHERE (username LIKE '%$username%' AND id != '$uid') ORDER BY username DESC LIMIT 20");
        $stmt -> execute();
        $result = $stmt -> get_result();
        $c = $result -> num_rows;

        if ($c < 1) {
            echo "<p class='no_results'>Sem resultados</p>";
        } else {
            while($user = $result -> fetch_assoc()) {
?>
                <div class="row" onclick="$('#search_container').hide(); chat('<?= $user['id'] ?>')" ontouchend="$('#search_container').hide(); chat('<?= $user['id'] ?>')">
                    <img src="profilepics/<?= $user['picture'] ?>">
                    <p><?= $user['username'] ?></p>
                </div>
<?php
            }
        }
    }
?>