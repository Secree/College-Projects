<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Operators</h1>

    <?php
    session_start();
    $_SESSION["tanga"] = "marx";
    
    ?>

    <form action="3blecture2b.php" method="post">
        Input Number 1:
        <input type="number" name="num1"><br>
        Input Number 2:
        <input type="number" name="num2"><br>
        Session:
        <input type="text" name="val"><br>
        <input type="submit" name="pindot"><br>
        <input type="reset" name="hugot"><br>
    </form>
</body>
</html>