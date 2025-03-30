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

    $KH01 = [];// Обнуляю массив, в него буду записывать дешифрованные данные
    // Пробегаюсь по отдельным кодам "="
    foreach($byGroup as $key => $code){
        // Сразу можно выдернуть данные о станции и основные данные
        $KH01[$key]['YY'] = $code[0][0][0].$code[0][0][1];
        $KH01[$key]['GG'] = $code[0][0][2].$code[0][0][3];
        $KH01[$key]['iw'] = $code[0][0][4];
        $KH01[$key]['IIiii'] = substr($code[0][0], 5, 5);

        $KH01[$key]['ir'] = $code[0][1][0];
        $KH01[$key]['ix'] = $code[0][1][1];
        $KH01[$key]['h'] = $code[0][1][2];
        $KH01[$key]['VV'] = $code[0][1][3].$code[0][1][4];

        $KH01[$key]['N'] = $code[0][2][0];
        $KH01[$key]['dd'] = $code[0][2][1].$code[0][2][2];
        $KH01[$key]['ff'] = $code[0][2][3].$code[0][2][4];
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
                            $KH01[$key]['error'][] = 'раздел 111 подраздел 0 не должен существовать';
                            break;
                        case 1:
                            $KH01[$key]['sn1'] = $info[$i][1];
                            if ($KH01[$key]['sn1'] != 1 && $KH01[$key]['sn1'] != 0){$KH01[$key]['error'][] = ' "sn" знак температуры в раздел 111, подгруппе 1';} 
                            $KH01[$key]['TTT'] = substr($info[$i], 2, 3);
                            break;
                        case 2:
                            $KH01[$key]['sn2'] = $info[$i][1];
                            if ($KH01[$key]['sn2'] != 1 && $KH01[$key]['sn2'] != 0){$KH01[$key]['error'][] = ' "sn" знак температуры в раздел 111, подгруппе 2';} 
                            $KH01[$key]['TdTdTd'] = substr($info[$i], 2, 3);
                            break;
                        case 3:
                            $KH01[$key]['P0P0P0'] = substr($info[$i], 1, 4);
                            break;
                        case 4:
                            $KH01[$key]['PPPP'] = substr($info[$i], 1, 4);
                            break;
                        case 5:
                            $KH01[$key]['a'] = $info[$i][1];
                            if ($KH01[$key]['a'] == 9){$KH01[$key]['error'][] = ' "a" характеристика барической тенденции';}
                            break;
                        case 6:
                            if ($KH01[$key]['ir'] != 1){$KH01[$key]['error'][] = ' "ir" или раздел 111 подраздел 6';}
                            if ($KH01[$key]['ir'] == 2){break;} else {$KH01[$key]['RRR'] = substr($info[$i], 1, 3); $KH01[$key]['tr'] = $info[$i][4];}
                            break;
                        case 7:
                            if ($KH01[$key]['ix'] == 2 || $KH01[$key]['ix'] == 3 || $KH01[$key]['ix'] == 5 || $KH01[$key]['ix'] == 6){$KH01[$key]['error'][] = ' "ix" или подраздел 7';}
                            $KH01[$key]['ww'] = $info[$i][1].$info[$i][2];
                            $KH01[$key]['W1W2'] = $info[$i][3].$info[$i][4];
                            break;
                        case 8:
                            $KH01[$key]['Nh'] = $info[$i][1];
                            $KH01[$key]['Cl'] = $info[$i][2];
                            $KH01[$key]['Cm'] = $info[$i][3];
                            $KH01[$key]['Ch'] = $info[$i][4];
                            break;
                        case 9:
                            $KH01[$key]['hh'] = $info[$i][1].$info[$i][2];
                            if ($info[$i][3] != '/' || $info[$i][4] != '/'){$KH01[$key]['error'][] = 'подраздел 9';}
                            break;
                    }
                } 

            } elseif ($group == 222){
                $KH01[$key]['error'][] = 'раздел 222 (а так же ICE) относится к ship морской части КН-01 и в данный момент не поддерживается.';

            }  elseif ($group == 333){

                foreach ($info as $sub_group){
                    $num_sub_group = $sub_group[0];
                    switch ($num_sub_group){
                        case 0:
                            $KH01[$key]['error'][] = 'раздел 333 подраздел 0 не должен существовать';
                        case 1:
                            $KH01[$key]['sn31'] = $sub_group[1];
                            if ($KH01[$key]['sn31'] != 1 && $KH01[$key]['sn31'] != 0){$KH01[$key]['error'][] = ' "sn" знак температуры в раздел 333 подгруппе 1';} 
                            $TxTxTx = substr($sub_group, 2, 3);
                            break;
                        case 2:
                            $KH01[$key]['sn32'] = $sub_group[1];
                            if ($KH01[$key]['sn32'] != 1 && $KH01[$key]['sn32'] != 0){$KH01[$key]['error'][] = ' "sn" знак температуры в раздел 333 подгруппе 2';} 
                            $KH01[$key]['TnTnTn'] = substr($sub_group, 2, 3);
                            break;
                        case 3:
                            $KH01[$key]['error'][] = 'раздел 333 подраздел 3 не должен существовать';
                            break;
                        case 4:
                            $KH01[$key]['error'][] = 'раздел 333 подраздел 4 не должен существовать';
                            break;
                        case 5:
                            $KH01[$key]['error'][] = 'раздел 333 подраздел 5 не должен существовать';
                            break;
                        case 6:
                            if ($KH01[$key]['ir'] != 2){$KH01[$key]['error'][] = ' "ir" или раздел 333 подраздел 6';}
                            if ($KH01[$key]['ir'] == 1){break;} else {$KH01[$key]['RRR'] = substr($sub_group, 1, 3); $KH01[$key]['tr'] = $sub_group[4];}
                            break;
                        case 7:
                            $KH01[$key]['error'][] = 'раздел 333 подраздел 7 не должен существовать';
                            break;
                        case 8:
                            $KH01[$key]['Ns'] = $sub_group[1];
                            $KH01[$key]['C'] = $sub_group[2];
                            $KH01[$key]['hshs'] = $sub_group[3].$sub_group[4];
                            break;
                        case 9:
                            $KH01[$key]['SpSp'] = $sub_group[1].$sub_group[2];
                            $KH01[$key]['spsp'] = $sub_group[3].$sub_group[4];
                            break;
                    }
                }

            } elseif ($group == 444) {
                $KH01[$key]['Nch'] = $info[0][0];
                $KH01[$key]['Cch'] = $info[0][1];
                $KH01[$key]['HchHch'] = $info[0][2].$info[0][3];
                $KH01[$key]['C1'] = $info[0][4];
                if (count($info) > 1){
                    $KH01[$key]['error'][] = 'раздел 444. Подразделов не должено существовать';
                }

            } elseif ($groups == 555){

                foreach ($info as $sub_group){
                    $num_sub_group = $sub_group[0];
                    switch ($num_sub_group){
                        case 0:
                            $KH01[$key]['error'][] = 'раздел 555 подраздел 0 не должен существовать';
                            break;
                        case 1:
                            $KH01[$key]['sn51'] = $sub_group[1];
                            if ($KH01[$key]['sn51'] != 1 && $KH01[$key]['sn51'] != 0){$KH01[$key]['error'][] = ' "sn" знак температуры в раздел 555 подгруппе 1';} 
                            $KH01[$key]['T24T24T24'] = substr($sub_group, 2, 3);
                            break;
                        case 2:
                            $KH01[$key]['error'][] = 'раздел 555 подраздел 2 не должен существовать';
                            break;
                        case 3:
                            if ($sub_group[1] != '/')$KH01[$key]['error'][] = ' не хватает "/" раздел 555 подраздел 3 ';
                            $KH01[$key]['sn53'] = $sub_group[2];
                            if ($KH01[$key]['sn53'] != 1 && $KH01[$key]['sn53'] != 0){$KH01[$key]['error'][] = ' "sn" знак температуры в раздел 555 подгруппе 3';} 
                            $KH01[$key]['TgTg'] = $sub_group[3].$sub_group[4];
                            break;
                        case 4:
                            $KH01[$key]['Ech'] = $sub_group[1];
                            $KH01[$key]['sss'] = substr($sub_group, 2, 3);
                            break;
                        case 5:
                            $KH01[$key]['error'][] = 'раздел 555 подраздел 5 не должен существовать';
                            break;
                        case 6:
                            if (isset($KH01[$key]['RRR']) || isset($KH01[$key]['tr'])){
                                $KH01[$key]['error'][] = '555. Подгруппа 6 уже есть в разделе 111 или 333 согласно ir='.$KH01[$key]['ir'];
                            } else {
                                $KH01[$key]['RRR'] = substr($sub_group, 1, 3);
                                $KH01[$key]['tr'] = $sub_group[4];
                             }
                            break;
                        case 7:
                            $KH01[$key]['R24R24R24'] = substr($sub_group, 1, 3);
                            $KH01[$key]['E'] = $sub_group[4];
                            break;
                        case 8:
                            $KH01[$key]['error'][] = 'раздел 555 подраздел 8 не должен существовать';
                        case 9:
                            $KH01[$key]['SpSp'] = $sub_group[1].$sub_group[2];
                            $KH01[$key]['spsp'] = $sub_group[3].$sub_group[4];
                    }
                }

            }
        
        }
    }
    dataprint($KH01);
?>