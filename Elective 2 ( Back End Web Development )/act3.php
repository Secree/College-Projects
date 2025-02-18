<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>DECENALAURENTE</title>
</head>
<body>
    <?php
    
    if(isset($_POST["num1"])) {
        session_start();
        $num = $_POST['num1'];
        $_SESSION["ar"] = array();
        $_SESSION["num1"] = array();
        for($i=0 ; $i<$num1 ; $i++) {
        echo "What is the value? ";
        echo ("
            <form action='activity#3.php' method='post'>
            <label>TYPE KA DITO:</label>
            <input type='number' name='num1'><br>
            </form>
        ");

        }
        echo "<input type='submit' name='procede'>";


    }       
        else if (isset($_SESSION["ar"])) {
            array_push( $_SESSION["ar"], $_SESSION["num"]);
            echo $_SESSION["ar"];

        }else{
            echo ("<h1>what is the number? </h1>
            <form action='activity#3.php' method='post'>
            
        ");
        }
        
?>
            <form action='activity#3.php' method='post'>
            <label>TYPE KA DITO:</label>
            <input type='number' name='num1'><br>
            <input type='submit' name='procede'>
            </form> 


    
</body>
</html>