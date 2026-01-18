<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Document</title>
    <style>
        .a{
            animation-name: a;
            animation-duration: 10s;
            animation-iteration-count: infinite;
            animation-timing-function: linear;
        }
        @keyframes a {
            0% {transform: rotate(0deg);}
            100% {transform: rotate(360deg);}
        }
    </style>
</head>
<body bgcolor="pink">
    <h1>Tables</h1>

    <?php
    session_start();
    if(isset($_POST["row1"]) && isset($_POST["col1"])) {
        $_SESSION["row1"]=$_POST["row1"];
        $_SESSION["col1"]=$_POST["col1"]; 
        

        $count = 1;

        echo "<table border='5'  align='center' class='a'>";
        for($i=0; $i<$_SESSION["row1"];$i++) {
            echo "<tr>";
            for($j=0; $j<$_SESSION["col1"];$j++) {
                echo "<td colspan='5'>", $count,"</td>";
                $count++;
            }
            echo "</tr>";
        }
        echo "</table>";

        echo "
            <form action='3blecture3a.php' method='post'>
            <label>Enter rows:</label>
            <input type='number' name='row1'><br>
            <label>Enter column:</label>
            <input type='number' name='col1'><br>
        ";
        
    } else if(isset($_POST["arr1"])) {
        
        array_push($_SESSION["arr1"], $_POST['arr1']);
        $count=1;
        echo"<table border='2'";
        for($i=0; $i<$_SESSION["row1"];$i++) {
            echo "<tr>";
            for($j=0; $j<$_SESSION["col1"];$j++) {
                if(isset($_SESSION["arr1"][$count])) {
                echo "<td>", $_SESSION['arr1'][$count],"</td>";
                } else {
                    echo "<td>", $count, "</td>";
                }
                $count++;
            }
            echo "</tr>";
        }
        
        echo "</table>";
        echo (" 
            <form action='3blecture3a.php' method='post'>
            <label>Enter value:</label>
            <input type='number' name='arr1'><br>
        ");
        
    } else {
        echo "
            <form action='3blecture3a.php' method='post'>
            <label>Enter rows:</label>
            <input type='number' name='row1'><br>
            <label>Enter column:</label>
            <input type='number' name='col1'><br>
        ";
    }
    
   
    ?>
    
    
        <input type="submit" value="process">
        <input type="reset" value="clear">
    </form>
</body>
</html>
