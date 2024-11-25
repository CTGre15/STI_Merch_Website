<?php
    session_start();
?>
<html>
    <head>
        
    </head>
    <body>
        Hello, <?php echo $_SESSION["fName"] . " " . $_SESSION["lName"]; ?>
    </body>
</html>