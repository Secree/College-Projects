<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    session_start();
    $num1 = $_POST["num1"];
    $num2 = $_POST["num2"];
    $_SESSION["val"] = $_POST["val"];

    echo "<h1><b>",$num1,"</b>, <i> ",$num2,"</i></h1>";

    // Operators
    echo "Sum: ". $num1 + $num2. "<br>";
    echo "Diff: ". $num1 - $num2. "<br>";
    echo "Prod: ". $num1 * $num2. "<br>";
    echo "Quo: " . $num1 / $num2. "<br>";
    echo "Rem: ". $num1 % $num2. "<br><br>";

    // Relational
    echo "num1 > num2: ", $num1 > $num2, "<br>";
    echo "num1 < num2: ", $num1 < $num2, "<br>";
    echo "num1 == num2: ", $num1 == $num2, "<br><br>";

    $test1 = 69;
    $test2 = 69.0;

    echo "test1 == test2: ", $test1 == $test2, "<br>";
    echo "test1 === test2: ", $test1 === $test2, "<br>";

    // Relational
    echo "num1 > num2 AND num1 != num2: ", $num1 > $num2 AND $num1 != $num2, "<br>";
    echo "num1 > num2 OR num1 == num2: ", $num1 > $num2 OR $num1 == $num2 , "<br><br>";

    // Session
    echo $_SESSION["tanga"];

    // Conditional
    if($_SESSION["val"] == $_SESSION["tanga"]) {
        echo " pogi";
    } else {
        echo " 69";
    }
    echo "<br>";

    // Control
    while ($num1 > 0) {
        if($num1 >= 20) {
            die();
        }
        echo "ugh<br>";
        $num1--;
    }

    do {
        echo "agh<br>";
        $num2++;
    } while ($num2 < 10);

    for($i=0; $i<=10; $i++) {
        echo "tits<br>";
    }

    $jonas = array(1,2,3,4,5,6,7,8,9,10);
    foreach($jonas as $k => $v) {
        echo $v, "<br>";
    }
    ?>

    
</body>
</html>