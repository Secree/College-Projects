<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <?php
    $lname = $_POST["lname"];
    $fname = $_POST["fname"];
    $sno = $_POST["sno"];
    $email= $_POST["email"];
    $address= $_POST["address"];
    $age = $_POST["age"];
    
    echo "Hello Mr/Ms $lname, your student number is $sno and email is $email<br>";
    echo "Is the information correct?";

    ?>

    <form action="Act1.php" method="post">
        <input type="submit" value="YES">
        <input type="submit" value="NO">
    </form>
</body>
</html>