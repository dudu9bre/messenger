<?php 
    require_once 'check.php';

    if (isset($_GET['id']) && $_GET['id'] > 0) {
        $user_id = $_GET['id'];

        $get_user = $con -> prepare("SELECT username FROM user WHERE (id LIKE ?) LIMIT 1");
        $get_user -> bind_param("i", $user_id);
        $get_user -> execute();
        
        $user = $get_user -> get_result() -> fetch_assoc();

?>
        <div class="top_menu">
            <img src="img/close.png" onclick="chat()">
            <p class="title"><?= $user['username'] ?></p>
        </div>

        <div class="inner_container"></div>
        <form method="post" enctype="multipart/form-data" id="send_message">
            <input type="number" name="id" value="<?= $user_id ?>">
            <input type="text" name="message" maxlength="500" placeholder="Mensagem" id="message_input">
            <input type="file" name="image" accept="image/x-png, image/jpeg" id="send_image" hidden>
            <label for="send_image">
                <img src="img/image.png">
            </label>
        </form>

        <script>
            function sendMessage() {
                let formData = new FormData($("#send_message")[0])
                
                $.ajax({
                    type: "post"
                    , url: "process/send.php"
                    , data: formData
                    , cache: false
                    , contentType: false
                    , processData: false
                    , success: function() {
                        $("#send_message")[0].reset()
                    }
                    , error: function(error) {
                        Swal.fire({
                            title: "Falha ao enviar mensagem"
                            , text: error.statusText
                            , icon: "error"
                            , confirmButtonText: "Tentar novamente"
                        })
                    }
                })
            }

            $("#message_input").on("keyup", function(e) {
                if (e.keyCode === 13 && ($("#message_input").val().length > 0)) {
                    sendMessage()
                }
            })

            $("#send_image").change(function() {
                sendMessage()
            })
        </script>
<?php 
    } else {
?>
        <div class="empty">
            <img src="img/empty-chat.png">
            <p>Escolha uma conversa</p>
        </div>
<?php
    }
?>