<?php
    $errors = [0];
    $groups = ['333', '444', '555'];


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
    // Пробегаюсь по отдельным кодам "="
    foreach($byGroup as $code){
        // Сразу можно выдернуть данные о станции и основные данные
        $YY = $code[0][0][0].$code[0][0][1];
        $GG = $code[0][0][2].$code[0][0][3];
        $iw = $code[0][0][4];
        $IIiii = substr($code[0][0], 5, 5);

        $ir = $code[0][1][0];
        $ix = $code[0][1][1];
        $h = $code[0][1][2];
        $VV = $code[0][1][3].$code[0][1][4];

        $N = $code[0][2][0];
        $dd = $code[0][2][1].$code[0][2][2];
        $ff = $code[0][2][3].$code[0][2][4];
        // echo '<br>YY: '.$YY.'<br>';
        // echo $GG.'<br>';
        // echo $iw.'<br>';
        // echo $IIiii.'<br>';
        // echo $ir.'<br>';
        // echo $ix.'<br>';
        // echo $h.'<br>';
        // echo $VV.'<br>';
        // echo $N.'<br>';
        // echo $dd.'<br>';
        // echo $ff.'<br>';

        // Пробегаюсь по отдельным группам " "
        foreach($code as $group => $info){

            if ($group == 0){

                // В "нулевой" группе нулевым, первым и вторым значенияем были данные о станции которые мы уже собрали выше, пропускаем их
                $count = count($info);
                for ($i = 3; $i < $count; $i++){
                    $sub_group = $info[$i][0];
                    switch ($sub_group){
                        case 0:
                            $errors[0] = 1; $errors[] = 'раздел 111 подраздел 0 не должен существовать';
                            break;
                        case 1:
                            $sn1 = $info[$i][1];
                            if ($sn1 != 1 || $sn1 != 0){$errors[0] = 1; $errors[] = ' "sn" знак температуры в раздел 111, подгруппе 1';} 
                            $TTT = substr($info[$i], 2, 3);
                            break;
                        case 2:
                            $sn2 = $info[$i][1];
                            if ($sn2 != 1 || $sn2 != 0){$errors[0] = 1; $errors[] = ' "sn" знак температуры в раздел 111, подгруппе 2';} 
                            $TdTdTd = substr($info[$i], 2, 3);
                            break;
                        case 3:
                            $P0P0P0 = substr($info[$i], 1, 4);
                            break;
                        case 4:
                            $PPPP = substr($info[$i], 1, 4);
                            break;
                        case 5:
                            $a = $info[$i][1];
                            if ($a == 9){$errors[0] = 1; $errors[] = ' "a" характеристика барической тенденции';}
                            break;
                        case 6:
                            if ($ir != 1){$errors[0] = 1; $errors[] = ' "ir" или раздел 111 подраздел 6';}
                            if ($ir == 2){break;} else {$RRR = substr($info[$i], 1, 3); $tr = $info[$i][4];}
                            break;
                        case 7:
                            if ($ix == 2 || $ix == 3 || $ix == 5 || $ix == 6){$errors[0] = 1; $errors[] = ' "ix" или подраздел 7';}
                            $ww = $info[$i][1].$info[$i][2];
                            $W1W2 = $info[$i][3].$info[$i][4];
                            break;
                        case 8:
                            $Nh = $info[$i][1];
                            $Cl = $info[$i][2];
                            $Cm = $info[$i][3];
                            $Ch = $info[$i][4];
                            break;
                        case 9:
                            $hh = $info[$i][1].$info[$i][2];
                            if ($info[$i][3] != '/' || $info[$i][4] != '/'){$errors[0] = 1; $errors[] = 'подраздел 9';}
                            break;
                    }
                } 

            } elseif ($group == 222){
                $errors[0] = 1; $errors[] = 'раздел 222 (а так же ICE) относится к ship морской части КН-01 и в данный момент не поддерживается.';

            }  elseif ($group == 333){

                foreach ($info as $sub_group){
                    $num_sub_group = $sub_group[0];
                    switch ($num_sub_group){
                        case 0:
                            $errors[0] = 1; $errors[] = 'раздел 333 подраздел 0 не должен существовать';
                        case 1:
                            $sn31 = $sub_group[1];
                            if ($sn31 != 1 || $sn31 != 0){$errors[0] = 1; $errors[] = ' "sn" знак температуры в раздел 333 подгруппе 1';} 
                            $TxTxTx = substr($sub_group, 2, 3);
                            break;
                        case 2:
                            $sn32 = $sub_group[1];
                            if ($sn32 != 1 || $sn32 != 0){$errors[0] = 1; $errors[] = ' "sn" знак температуры в раздел 333 подгруппе 2';} 
                            $TnTnTn = substr($sub_group, 2, 3);
                            break;
                        case 3:
                            $errors[0] = 1; $errors[] = 'раздел 333 подраздел 3 не должен существовать';
                            break;
                        case 4:
                            $errors[0] = 1; $errors[] = 'раздел 333 подраздел 4 не должен существовать';
                            break;
                        case 5:
                            $errors[0] = 1; $errors[] = 'раздел 333 подраздел 5 не должен существовать';
                            break;
                        case 6:
                            if ($ir != 2){$errors[0] = 1; $errors[] = ' "ir" или раздел 333 подраздел 6';}
                            if ($ir == 1){break;} else {$RRR = substr($sub_group, 1, 3); $tr = $sub_group[4];}
                            break;
                        case 7:
                            $errors[0] = 1; $errors[] = 'раздел 333 подраздел 7 не должен существовать';
                            break;
                        case 8:
                            $Ns = $sub_group[1];
                            $C = $sub_group[2];
                            $hshs = $sub_group[3].$sub_group[4];
                            break;
                        case 9:
                            $SpSp = $sub_group[1].$sub_group[2];
                            $spsp = $sub_group[3].$sub_group[4];
                            break;
                    }
                }

            } elseif ($group == 444) {
                $Nch = $info[0][0];
                $Cch = $info[0][1];
                $HchHch = $info[0][2].$info[0][3];
                $C1 = $info[0][4];
                if (count($info) > 1){
                    $errors[0] = 1; $errors[] = 'раздел 444. Подразделов не должено существовать';
                }

            } elseif ($groups == 555){

                foreach ($info as $sub_group){
                    $num_sub_group = $sub_group[0];
                    switch ($num_sub_group){
                        case 0:
                            $errors[0] = 1; $errors[] = 'раздел 555 подраздел 0 не должен существовать';
                            break;
                        case 1:
                            $sn51 = $sub_group[1];
                            if ($sn51 != 1 || $sn51 != 0){$errors[0] = 1; $errors[] = ' "sn" знак температуры в раздел 555 подгруппе 1';} 
                            $T24T24T24 = substr($sub_group, 2, 3);
                            break;
                        case 2:
                            $errors[0] = 1; $errors[] = 'раздел 555 подраздел 2 не должен существовать';
                            break;
                        case 3:
                            if ($sub_group[1] != '/')$errors[0] = 1; $errors[] = ' не хватает "/" раздел 555 подраздел 3 ';
                            $sn53 = $sub_group[2];
                            if ($sn53 != 1 || $sn53 != 0){$errors[0] = 1; $errors[] = ' "sn" знак температуры в раздел 555 подгруппе 3';} 
                            $TgTg = $sub_group[3].$sub_group[4];
                            break;
                        case 4:
                            $Ech = $sub_group[1];
                            $sss = substr($sub_group, 2, 3);
                            break;
                        case 5:
                            $errors[0] = 1; $errors[] = 'раздел 555 подраздел 5 не должен существовать';
                            break;
                    }
                }

            }
        
        }
    }
    dataprint($byGroup);
?>