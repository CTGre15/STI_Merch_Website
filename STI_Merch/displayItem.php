<?php
    session_start();
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbName = "STIMerch";
    $db = new mysqli($servername, $username, $password, $dbName);
    $_SESSION['db'] = $db;
?>
<html>
    <head>
        <link rel="stylesheet" href="main.css">
        <script src="functions.js"></script>
    </head>
    <body>
        <h1>Hello, <?php echo $_SESSION["fName"] . " " . $_SESSION["lName"]; ?></h1>
        <h2><?php echo $_GET["itemName"]; ?>
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