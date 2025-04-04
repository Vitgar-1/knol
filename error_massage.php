<script src="https://code.jquery.com/jquery-3.6.0.min.js">

    $(".popup_close").click(function() {
		$('.error_container').fadeOut( "slow");
	});
</script>
<style>
    .error_container {
        position: fixed;
        right: 2em;
        bottom: 5%;
        width: 50em;
        height: 39em;
        background: #e9e9e9;
        filter:drop-shadow(0px 2px 4px #f7f7f7);
        border-radius: .5em;
        z-index: 99999;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        flex-direction: column;
    }
    .error_container .popup_close {
        position: relative;
        float: right;
        cursor: pointer;
        right: .5em;
        top: .5em;
    }
    .error_container .popup_close img{
        width: 1em;
        position: relative;
    }
    .error_text {
        position: relative;
        float: left;
        margin: 1em;
        overflow: scroll;
        background: white;
        border-radius: .5em;
        padding: 2em;
        box-sizing: border-box;
        height: 100%;
        width: calc(100% - 2em);
        font-size: .75em;
    }
</style>
<?php 
    // echo 1;
    error_reporting(E_ALL); 
	ini_set('display_errors', 1);
	$errorMessages = [];
    // Функция для обработки ошибок
    function customErrorHandler($errno, $errstr, $errfile, $errline) {
        global $errorMessages;

        // Формируем сообщение об ошибке
        $errorMessage = "Ошибка [$errno]: $errstr в файле $errfile на строке $errline";

        // Добавляем сообщение об ошибке в массив
        $errorMessages[] = $errorMessage;

        // Если это Notice, можно игнорировать его или обрабатывать по-другому
        if ($errno === E_NOTICE) {
            // Игнорируем Notice
            return true; // Возвращаем true, чтобы игнорировать стандартную обработку
        }

        // Для других типов ошибок можно продолжить стандартную обработку
        return false; // Возвращаем false, чтобы стандартный обработчик ошибок продолжал работу
    }

    // Функция для обработки фатальных ошибок
    function shutdownFunction() {
        global $errorMessages;
        $error = error_get_last();
        if ($error) {
            $errorMessage = "Фатальная ошибка: {$error['message']} в файле {$error['file']} на строке {$error['line']}";
            $errorMessages[] = $errorMessage;
        }
    }

    // Устанавливаем пользовательскую функцию обработки ошибок
    set_error_handler('customErrorHandler');

    // Регистрируем функцию для обработки фатальных ошибок
    register_shutdown_function('shutdownFunction');
    // echo $lolol;

    function dataprint($data){
        // echo 1;
        global $errorMessages;
        // Используем буферизацию вывода для захвата вывода print_r
        ob_start();
        print_r($data);
        $printROutput = ob_get_clean();

        // Добавляем вывод print_r в массив $errorMessages
        $errorMessages[] = '<pre>' . $printROutput . '</pre>';
    }

    function error_masage(){
        global $errorMessages;
        if (!empty($errorMessages)) {
            echo '
            <section class="error_container">
                <div class="popup_close">
                    <img src="/image/cloz.png">
                </div>
                <pre class="error_text">
            ';
            foreach ($errorMessages as $message) {
                echo "<p>$message</p>";
            }
            echo '
                </pre>
            </section>';
        }
    }
?>
