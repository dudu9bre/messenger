<?php 
    require_once "./check.php";

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        die(header("HTTP/1.0 401 Erro!"));
    }

    if ($id == 0) {
        $id = $uid;
        $user_creation = date("d/m/Y", strtotime($user_creation));
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
        $stmt = $con -> prepare("SELECT id, username, picture, online, creation FROM user WHERE (id = ?)");
        $stmt -> bind_param("i", $id);
        $stmt -> execute();
        $user = $stmt -> get_result() -> fetch_assoc();

        if (!$user) {
            die(header("HTTP/1.0 401 Erro"));
        } else {
            $username = $user['username'];
            $user_picture = $user['picture'];
            $user_online = strtotime($user['online']);
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
        <p class="row">Online <?= timing($user_online) ?></p>
        <p class="row">Membro desde <?= $user_creation ?></p>

<script>
    function previewUpload(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader()
            reader.onload = function(e) {
                $("#user_img").attr("src", e.target.result)
                let formData = new FormData($("#upload_picture")[0])
                $.ajax({
                    type: "post"
                    , url: "process/update_profile.php"
                    , data: formData
                    , cache: false
                    , contentType: false
                    , processData: false
                    , error: function(error) {
                        Swal.fire({
                            title: "Imagem inválida"
                            , text: error.statusText
                            , icon: "error"
                            , confirmButtonText: "Tentar novamente"
                        })
                    }
                })
            }
            reader.readAsDataURL(input.files[0])
        }
    }
    $("#img_inp").change(function() {
        previewUpload(this)
    })
</script>