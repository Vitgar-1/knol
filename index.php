<?php 

    $dr = $_SERVER['DOCUMENT_ROOT'];
    require $dr.'/config.php';

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KNOL - дешифрование метеорологического кода КН-01</title>
    <link rel="stylesheet" href="/styles/style.css">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
</head>
<body>
    <?php include $dr.'/header.php'; ?>
    <?php

    ?>
    <form class="input-container" action="/logic/test.php" method="POST">
        <textarea name="userText" placeholder="Введите ваш текст здесь..." required></textarea>
        <button type="submit">Отправить</button>
    </form>
    <?php
    ?>
    




</body>

</html>
 