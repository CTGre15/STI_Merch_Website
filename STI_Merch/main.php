<?php
    session_start();
?>
<html>
    <head>
        <script src="functions.js"></script>
    </head>
    <body>
        <h1>Hello, <?php echo $_SESSION["fName"] . " " . $_SESSION["lName"]; ?></h1> <br>
        <form method="post">
            <button name="logout">Log out</button>
        </form>
        <?php
            function logout(){
                session_destroy();
                header("Location: welcome.php");
            }
            if(isset($_POST["logout"])) {
                logout();
            }
        ?>
    </body>
</html>