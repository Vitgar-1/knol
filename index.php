<?php 

    $dr = $_SERVER['DOCUMENT_ROOT'];
    require $dr.'/knol/config.php';

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KNOL - дешифрование метеорологического кода КН-01</title>
    <link rel="stylesheet" href="/knol/styles/style.css">

    <link rel="stylesheet" href="/knol/styles/style_mera.css">

    <meta name="description" content="" />
    <meta name="keywords" content="" />
</head>
<body>
    <?php include $dr.'/knol/header.php'; ?>
    <?php

    ?>

    <section class="container_window">
        <div class="input_div_all">
            <form class="input-container" action="/knol/logic/test.php" method="POST">
                <textarea name="userText" placeholder="Введите ваш текст здесь..." required></textarea>
                <button type="submit">Отправить</button>
            </form>
            <?php
                echo 1;
        
            echo 2;

            ?>
        </div>
   
        <div class="window_all">

        </div>

    </section>

    
    



</body>

</html>
 