<?php 

    $dr = $_SERVER['DOCUMENT_ROOT'];
    require $dr.'/config.php';

?>

<!DOCTYPE html>
<html lang="ru">
<meta charset="utf-8">
<head>
    <style>
        .svg-container {
            width: 100px; /* или любое другое значение */
            height: auto; /* чтобы сохранить пропорции */
            display: flex;
            justify-content: center; /* по центру */
            align-items: center; /* по центру */
            border: 3px solid #000; /* граница для наглядности */
            margin: 10px; /* отступы между div */
            padding: 10px; /* внутренние отступы */
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2); /* легкая тень для визуального эффекта */
        }

        .svg-row {
            display: flex; /* Используем flexbox для контейнера */
            flex-direction: row; /* Устанавливаем направление по горизонтали */
            flex-wrap: wrap; /* Позволяет элементам переноситься на новую строку при необходимости */
        }

        .svg-container svg {
            width: 100%; /* растягиваем SVG на весь доступный размер div */
            height: auto; /* сохраняем пропорции */
        }
    </style>
</head>
<body>

<?php include $dr.'/header.php'; ?>

    <div class="svg-row">
        <?php
            
            for ($i=0;$i<100;$i++){
                // echo $i;
                echo '<div class="svg-container">';
                include $dr.'/logic/pages/ww/ww_'.$i.'.svg';
                echo '</div>';
            }

        ?>
    </div>
</body>
</html>
