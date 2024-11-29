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
        <link rel="stylesheet" href="cart.css">
        <script src="functions.js"></script>
    </head>
    <body>
        <header>
            <div class="logo"> <img src="images/sti-logo.png" alt="STI Logo"> </div>
            <div class="title"> <h1>STI Merch Store</h1> </div>
            <form method="post" class="logout-container">
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

        <div class="big-container">
        <h2>My cart</h2>
        <div class="cartContainer">
            <div><b>Item</b></div>
            <div><b>Price per Item</b></div>
            <div><b>Quantity</b></div>
            <div><b>Price</b></div>
            <?php
                $_SESSION["checkoutPrice"] = 0;
                $displayCartQuery = "SELECT * FROM " . $_SESSION["cart"];
                $result = mysqli_query($_SESSION['db'], $displayCartQuery);
        
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $itemPrice = getItemPrice($row["itemName"]);
                        $quantity = $row["addedToCart"];
                        $price = $itemPrice * $quantity;

                        echo "<div>";
                        echo $row["itemName"];
                        echo "</div>";
                        echo "<div>";
                        echo "<span style='color: #EE4B2B;'>₱" . $itemPrice . "</span>";
                        echo "</div>";
                        echo "<div class='cartItemQuantity'>";
                        echo "<form method='post'>
                                <div><button name='subtract1'>-</button></div>
                                <input type='hidden' name='itemName' value='" . $row["itemName"] . "'>
                                <input type='hidden' name='itemQuantity' value='" . $quantity . "'>
                                <div><span style='color: #008000;'>" . $quantity . "</span></div>
                                <div><button name='add1'>+</button></div>
                            </form>";
                        echo "</div>";
                        echo "<div>";
                        echo "<span style='color: #FF5700;'>₱" . $price . "</span>";
                        echo "</div>";
                        $_SESSION["checkoutPrice"] = $_SESSION["checkoutPrice"] + $price;
                    }
                }
                ?>
        </div>
        </div>
        
        <?php
            $totalPayment = $_SESSION["checkoutPrice"] + 50;

            echo "<div class='payment-container'>";
            echo "<div class='paymentDetails'>";
            echo "<h4>Payment Details</h4><br>";
            echo "Merchandise Subtotal: ₱" . $_SESSION["checkoutPrice"] . "<br><br>";
            echo "Shipping Subtotal: ₱50<br><br><hr><br>";
            echo "<h5>Total Payment: ₱" . $totalPayment . "</h5><br>";
            echo "<form method='post'>
                    <button name='checkout'>Place Order</button>
                </form>";
            echo "</div>";
            echo "</div>";

            function alert($msg) {
                echo "<script type='text/javascript'>alert('$msg');</script>";
            }
            function getItemPrice($itemName){
                $getItemPrice = $_SESSION['db']->prepare("SELECT price FROM Items WHERE itemName = ?");
                $getItemPrice->bind_param("s", $itemName);
                $getItemPrice->execute();
                $result = $getItemPrice->get_result();
                $price;
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $price = $row["price"];
                    }
                }
                return $price;
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
            function add1($itemName, $itemQuantity){
                if($itemQuantity < getStock($itemName)){
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
            function subtract1($itemName, $itemQuantity){
                if ($itemQuantity > 1){
                    $query = "UPDATE " . $_SESSION["cart"] . "
                                SET addedToCart = addedToCart - 1
                                WHERE itemName = '" . $itemName . "';";
                    $subtract1ToItem = $_SESSION['db']->prepare($query);
                    $subtract1ToItem->execute();
                    $subtract1ToItem->close();
                    header("Refresh: 0");
                } else {
                    $query = "DELETE FROM " . $_SESSION["cart"] . " WHERE itemName = '" . $itemName . "';";
                    $subtract1ToItem = $_SESSION['db']->prepare($query);
                    $subtract1ToItem->execute();
                    $subtract1ToItem->close();
                    header("Refresh: 0");
                }
            }
            function logout(){
                session_destroy();
                header("Location: welcome.php");
            }
            if(isset($_POST["logout"])) {
                logout();
            }
            if(isset($_POST["browseSelection"])) {
                header("Location: main.php");
            }
            if(isset($_POST["checkout"])) {
                logout();
            }
            if(isset($_POST["add1"])) {
                add1($_POST["itemName"], $_POST["itemQuantity"]);
            }
            if(isset($_POST["subtract1"])) {
                subtract1($_POST["itemName"], $_POST["itemQuantity"]);
            }
        ?>
    </body>
</html>