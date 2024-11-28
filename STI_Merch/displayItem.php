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
        <link rel="stylesheet" href="displayItem.css">
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

        <div class="top-container">
            <h1 class="user">Hello, <?php echo $_SESSION["fName"] . " " . $_SESSION["lName"]; ?></h1>
        
            <div class="browseSelection">
                <form method="post">
                    <button   button name="browseSelection">Return to Browse Selection</button>
                </form>
            </div>
        </div>
        
        <div class="item-container">
        <h2 class="itemName"><?php echo $_GET["itemName"]; ?></h2>
        <div class="itemInfo">
        <?php
            $displayItemQuery = "SELECT * FROM Items WHERE itemName = '" . $_GET["itemName"] . "'";
            $result = mysqli_query($_SESSION['db'], $displayItemQuery);
    
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='itemImage'> <img id='itemPic' src='itemsImage/" . $row['imageName'] . "' alt='" . $row["itemName"] . "'> </div>";
                    echo "<div class='itemDesc'>";
                    echo "<div id='itemName'>" . $row["itemName"] . "</div><br>";
                    echo $row["itemDesc"] . "<br><br>";
                    echo "₱" . $row["price"] . "<br><br>";
                    echo "Stocks: " . $row["stocks"] . "<br><br>";
                    echo "On Cart: " . getCartQuantity($row["itemName"]);
                    echo "</div>";
                }
            }
        ?>
        </div>
        </div>
        
        <div class="addToCart">
            <form method="post">
                <button name="addToCart">Add to Cart</button>
            </form>
        </div>
        <?php
            function alert($msg) {
                echo "<script type='text/javascript'>alert('$msg');</script>";
            }
            function getStock($itemName){
                $getStock = $_SESSION['db']->prepare("SELECT stocks FROM Items WHERE itemName = ?");
                $getStock->bind_param("s", $itemName);
                $getStock->execute();
                $result = $getStock->get_result();
                $stocks;
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $stocks = $row["stocks"];
                    }
                }
                return $stocks;
            }
            function getCartQuantity($itemName){
                $query = "SELECT addedToCart FROM " . $_SESSION["cart"] . " WHERE itemName = ?";
                $getCartQuantity = $_SESSION['db']->prepare($query);
                $getCartQuantity->bind_param("s", $itemName);
                $getCartQuantity->execute();
                $result = $getCartQuantity->get_result();
                $cartQuantity;
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $cartQuantity = $row["addedToCart"];
                    }
                } else {
                    $cartQuantity = 0;
                }
                return $cartQuantity;
            }
            function addToCart($itemName){
                $addToCartQueryString = "INSERT IGNORE INTO " . $_SESSION["cart"] . " (itemName, addedToCart)
                                        VALUES (?, 0)";
                $addToCartQuery = $_SESSION['db']->prepare($addToCartQueryString);
                $addToCartQuery->bind_param("s", $itemName);
                $addToCartQuery->execute();
                $addToCartQuery->close();
                if (getCartQuantity($itemName) < getStock($itemName)){
                    $query = "UPDATE " . $_SESSION["cart"] . "
                                SET addedToCart = addedToCart + 1
                                WHERE itemName = '" . $itemName . "';";
                    $add1ToItem = $_SESSION['db']->prepare($query);
                    $add1ToItem->execute();
                    $add1ToItem->close();
                    header("Refresh: 0");
                } else {
                    alert("Exceeded item stock");
                }
            }
            function logout(){
                session_destroy();
                header("Location: welcome.php");
            }
            if(isset($_POST["browseSelection"])) {
                header("Location: main.php");
            }
            if(isset($_POST["logout"])) {
                logout();
            }
            if(isset($_POST["addToCart"])) {
                addToCart($_GET["itemName"]);
            }
        ?>
    </body>
</html>