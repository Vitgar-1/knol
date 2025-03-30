<?php 

    $dr = $_SERVER['DOCUMENT_ROOT'];
    require $dr.'/knol/config.php';
    include $dr.'/knol/logic/decoding.php';
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

<?php include $dr.'/knol/header.php'; ?>
<body>


    <div class="svg-row">
        <?php
            
            // foreach($KH01 as $num => $code){
            //     foreach($code as $key => $value){
            //         switch($key){
            //             case 'Ch':
            //                 if ($value == 0){break;}
            //                 echo $num.'<br>';
            //                 echo '<div class="svg-container">';
            //                 include $dr.'/knol/logic/pages/Ch/Ch_'.$value.'.svg';
            //                 echo '</div>';
            //                 break;
            //             case 'Cl':
            //                 if ($value == 0){break;}
            //                 echo $num.'<br>';
            //                 echo '<div class="svg-container">';
            //                 include $dr.'/knol/logic/pages/Cl/Cl_'.$value.'.svg';
            //                 echo '</div>';
            //                 break;
            //             case 'Cm':
            //                 if ($value == 0){break;}
            //                 echo $num.'<br>';
            //                 echo '<div class="svg-container">';
            //                 include $dr.'/knol/logic/pages/Cm/Cm_'.$value.'.svg';
            //                 echo '</div>';
            //                 break;
            //         }
                    
            //     }
            // }
        ?>
    </div>
    
        


</body>
</html>
