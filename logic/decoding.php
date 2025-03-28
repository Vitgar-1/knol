<?php

    $groups = ['ICE', '333', '444', '555'];


    // Первичная разбивка введённого текста на отдельные коды по "станциям" 
    if (isset($_REQUEST['userText'])){
        $user_text = htmlspecialchars($_REQUEST['userText']);
        // echo $user_text;
        $user_text = explode('=', $user_text);
        // dataprint($KH01);
    }

    //Начало дешифровки. Отдельные станции
    foreach ($user_text as $code){
        $help = explode(' ', $code);
        $KH01[] = $help;
    }
    // dataprint($KH01);

    function splitByGroups(array $array, array $groups): array
    {
        $result = [
            '0' => [],      // Группа для первого элемента и его значений
            '222' => [],    // Все элементы начинающиеся с 222
        ];
        
        // Добавляем остальные группы из списка
        foreach($groups as $g) {
            if($g !== '222') $result[$g] = [];
        }
    
        $currentGroup = '0'; // Начинаем с группы 0
        $result[$currentGroup][] = $array[0]; // Добавляем первый элемент
        
        // Обрабатываем остальные элементы начиная с индекса 1
        for($i = 1; $i < count($array); $i++) {
            $item = $array[$i];
            $is222 = str_starts_with($item, '222');
            $isGroup = in_array($item, $groups, true) || $is222;
    
            if($is222) {
                // Добавляем в группу 222
                $currentGroup = '222';
                $result[$currentGroup][] = $item;
            } 
            elseif(in_array($item, $groups, true)) {
                // Для других групп (333, 555 и т.д.)
                $currentGroup = $item;
            } 
            else {
                // Добавляем элемент в текущую группу
                $result[$currentGroup][] = $item;
            }
        }
        
        // Удаляем пустые группы
        return array_filter($result, fn($v) => !empty($v));
    }

    $byGroup = [];
    foreach ($KH01 as $code) {
        $byGroup[] = splitByGroups($code, $groups);
    }
    dataprint($byGroup);
?>