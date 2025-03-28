<?php

    $groups = ['222', 'ICE', '333', '444', '555'];


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

    function splitByGroups($array, $groups)
    {
        $result = [];
        $currentKey = null;
        $groupValues = [];
        
        foreach ($array as $item) {
            if (in_array($item, $groups, true)) {
                if ($currentKey !== null) {
                    $result[$currentKey] = $groupValues;
                    $groupValues = [];
                }
                $currentKey = $item;
            } else {
                if ($currentKey === null) {
                    $currentKey = $item;
                } else {
                    $groupValues[] = $item;
                }
            }
        }
        
        if ($currentKey !== null && !empty($groupValues)) {
            $result[$currentKey] = $groupValues;
        }
        
        return $result;
    }

    $byGroup = [];
    foreach ($KH01 as $code) {
        $byGroup[] = splitByGroups($code, $groups);
    }
    dataprint($byGroup[10]);
?>