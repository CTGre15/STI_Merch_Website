<?php
    session_start();
    $order = $_SESSION["itemsOrdered"];
    $toPay = $_SESSION["totalToPay"];
?>
<html>
    <head>
        <link rel="stylesheet" href="CODCheckoutPage.css">
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

        <!-- Navigation bar -->
        <nav class="navbar">
            <form method="post">
                <button name="return" class="return-button">Return</button>
            </form>
            <div class="user-info">
                <span>Hello, <?php echo $_SESSION["fName"] . " " . $_SESSION["lName"]; ?></span>
            </div>
        </nav>

        <div class="user"><h1>Checkout</h1></div>
        
        <form method="post" class="listItem">
            <h3>Enter Delivery Details</h3><br>
            <label for="fullName">Full Name:</label>
            <input type="text" name="fullName" placeholder="Mario Gonzales" required><br>
            <label for="phoneNum">Phone Number:</label>
            <input type="number" name="phoneNum" placeholder="09123456789" required><br>
            <label>Address:</label>
            <div class="address">
                <div>
                    <input type="text" name="province" placeholder="Province" required><br>
                </div>
                <div>
                    <input type="text" name="city" placeholder="City" required><br>
                </div>
                <div>
                    <input type="text" name="barangay" placeholder="Barangay" required><br>
                </div>
                <div>
                    <input type="number" name="pCode" placeholder="Postal Code" required><br>
                </div>
            </div>
            <input type="text" name="specAddress" placeholder="Street Name, Building, House No." required><br><br>
            <label for="fullName">Cards Accepted:</label>
            <img src="images/cards.png" alt="bank cards">
            <label for="cardName">Name on Card:</label>
            <input type="text" name="cardName" placeholder="Mario Gonzales" required><br>
            <label for="cardNumber">Card Number:</label>
            <input type="number" name="cardNumber" placeholder="1234 2345 3456 4444" required><br>
            <label for="expMonth">Exp. Month: </label>
            <input type="text" name="expMonth" placeholder="September" required><br>
            <div class="address">
                <div>
                    <label for="expYear">Exp. Year:</label>
                    <input type="number" name="expYear" placeholder="2030" required><br>
                </div>
                <div>
                    <label for="cvv">CVV: </label>
                    <input type="number" name="cvv" pattern="[0-9]{3}" placeholder="203" required><br>
                </div>
            </div>
            <button name="placeOrder">Place Order</button>
        </form>

        <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbName = "STIMerch";
            $db = new mysqli($servername, $username, $password, $dbName);
            $_SESSION['db'] = $db;

            function alert($msg) {
                echo "<script type='text/javascript'>alert('$msg');</script>";
            }
            function getAddedOrderID(){
                $query = "SELECT * FROM Orders";
                $result = mysqli_query($_SESSION['db'], $query);
                $rows = mysqli_num_rows($result);
                return $rows;
            }
            function listOrder($fullName, $phoneNum, $completeAddress, $order, $toPay){
                $insertOrderQuery = $_SESSION['db']->prepare("INSERT INTO Orders (fullName, orderAddress, phoneNum, items, priceToPay, orderType)
                VALUES (?, ?, ?, ?, ?, 'card')");
                $insertOrderQuery->bind_param("ssisi", $fullName, $completeAddress, $phoneNum, $order, $toPay);
                $insertOrderQuery->execute();

                $_SESSION["currentOrder"] = getAddedOrderID();
            }
            function passItemVariables($order, $toPay){
                $fullName = $_POST["fullName"];
                $phoneNum = $_POST["phoneNum"];
                $completeAddress = $_POST["province"] . ", " . $_POST["city"] . ", " . $_POST["barangay"] . ", " . $_POST["pCode"] . ", " . $_POST["specAddress"];

                listOrder($fullName, $phoneNum, $completeAddress, $order, $toPay);
            }
            function deleteCart($cart){
                $query = "TRUNCATE TABLE " . $cart;
                $deleteCart = $_SESSION['db']->prepare($query);
                $deleteCart->execute();
                $deleteCart->close();
            }
            function logout(){
                session_destroy();
                echo "<script>window.location.href = 'welcome.php';</script>";
            }
            if(isset($_POST["logout"])) {
                logout();
            }
            if(isset($_POST["placeOrder"])) {
                deleteCart($_SESSION["cart"]);
                passItemVariables($order, $toPay);
                echo "<script>window.location.href = 'cardConfirmation.php';</script>";
            }
            if(isset($_POST["return"])) {
                echo "<script>window.location.href = 'cart.php';</script>";
            }
        ?>
    </body>
</html>