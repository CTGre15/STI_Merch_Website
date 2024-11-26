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
        <form method="post">
            <button name="logout">Log out</button>
        </form>
        <h3>Browse our selection</h3>
        <div class="selectionContainer">
            <?php
                $displayItemQuery = "SELECT * FROM Items";
                $result = mysqli_query($_SESSION['db'], $displayItemQuery);
        
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<a class='clickable' href='displayItem.php?itemName=" . $row["itemName"] . "'>";
                        echo $row["itemName"] . "<br>";
                        echo $row["itemDesc"] . "<br>";
                        echo "₱" . $row["price"] . "<br>";
                        echo "Stocks: " . $row["stocks"] . "<br>";
                        echo "</a>";
                    }
                }
            ?>
        </div>
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