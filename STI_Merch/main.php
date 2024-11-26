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
        <header>
            <div class="logo"> <img src="images/sti-logo.png" alt="STI Logo"> </div>
            <div class="title"> <h1>STI Merch Store</h1> </div>
            <form method="post">
                <button name="logout" class="logout">Log out</button>
            </form>
        </header>

        <h1>Hello, <?php echo $_SESSION["fName"] . " " . $_SESSION["lName"]; ?></h1>

        <h3>Browse our selection</h3>
        <div class="selectionContainerBox">
            <div class="selectionContainer">
                <?php
                    $displayItemQuery = "SELECT * FROM Items";
                    $result = mysqli_query($_SESSION['db'], $displayItemQuery);
            
                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<a class='clickable' href='displayItem.php?itemName=" . $row["itemName"] . "'>";
                            echo "<div> <img id='itemPic' src='itemsImage/" . $row['imageName'] . "' alt='" . $row["itemName"] . "'> </div>";
                            echo "<div id='itemDesc'>";
                            echo $row["itemName"] . "<br>";
                            echo $row["itemDesc"] . "<br>";
                            echo "₱" . $row["price"] . "<br>";
                            echo "Stocks: " . $row["stocks"] . "<br>";
                            echo "</div>";
                            echo "</a>";
                        }
                    }
                ?>
            </div>
        </div>
        <form method="post">
            <button name="viewCart">View My Cart</button>
        </form>
        <?php
            function logout(){
                session_destroy();
                header("Location: welcome.php");
            }
            function viewCart(){
                header("Location: cart.php");
            }
            if(isset($_POST["logout"])) {
                logout();
            }
            if(isset($_POST["viewCart"])) {
                viewCart();
            }
        ?>
    </body>
</html>