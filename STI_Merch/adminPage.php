<?php
    session_start();
?>
<html>
    <head>
        <script src="functions.js"></script>
    </head>
    <body>
        Welcome Admin <br>
        <form method="post">
            <button name="logout">Log out</button>
        </form>
        <form method="post">
            <button name="listItem">List an Item</button>
        </form>
        <?php
            function logout(){
                session_destroy();
                header("Location: welcome.php");
            }
            function listItem(){
                
            }
            if(isset($_POST["logout"])) {
                logout();
            }
            if(isset($_POST["listItem"])) {
                listItem();
            }
        ?>
    </body>
</html>