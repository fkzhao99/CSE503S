<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculator</title>
    <style>
        .cal{
            text-align: center;
            padding-top: 50px;
            margin: auto;
        }
        .cal p{
            display: inline;
        }
    </style>
</head>
<body>
    <form class="cal" method="GET" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
        <input type="text" name="num1" placeholder="Input the 1st number" required>
        <br><br>
        <p><input type ="radio" name="op" value = "+" required> +</p>
        <p><input type ="radio" name="op" value = "-" required> -</p>
        <p><input type ="radio" name="op" value = "x" required> x</p>
        <p><input type ="radio" name="op" value = "÷" required> ÷</p>
        <br><br>
        <input type="text" name="num2" placeholder="Input the 2nd number" required>
        <br><br>
        <input type="submit" value="Calculate">
        <br><br>
<?php
    function calculate($x, $y, $op){
        switch($op){
            case '+':
                return $x + $y;
                break;
            case '-':
                return $x - $y;
                break;
            case 'x':
                return $x * $y;
                break;
            case '÷':
                if($y == 0){
                    return "error";
                    break;
                }
                else{
                    return $x / $y;
                    break;
                }
                
        }
    }
    if(isset($_GET['num1']) && isset($_GET['num2']) && isset($_GET['op'])){
        $num1 = $_GET['num1'];
        $num2 = $_GET['num2'];
        $operator = $_GET['op'];
        if(is_numeric($num1) && is_numeric($num2)){
            $result = calculate($num1, $num2, $operator);
            if($result == "error"){
                echo "Divisor must not be 0!";
            }
            else{
                echo "<div>$num1 $operator $num2 = $result</div>";
            }
        }
        else{
            echo "Please input valid number!";
        }
    }
        
?>
    </form>
</body>
</html>