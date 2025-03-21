<?php
// Включаем отчет обо всех ошибках
error_reporting(E_ALL);

// Обработчик ошибок
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    $errorTypes = [
        E_ERROR => 'ERROR',
        E_WARNING => 'WARNING',
        E_PARSE => 'PARSING ERROR',
        E_NOTICE => 'NOTICE',
        E_CORE_ERROR => 'CORE ERROR',
        E_CORE_WARNING => 'CORE WARNING',
        E_COMPILE_ERROR => 'COMPILE ERROR',
        E_COMPILE_WARNING => 'COMPILE WARNING',
        E_USER_ERROR => 'USER ERROR',
        E_USER_WARNING => 'USER WARNING',
        E_USER_NOTICE => 'USER NOTICE',
        E_STRICT => 'STRICT NOTICE',
        E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR',
        E_DEPRECATED => 'DEPRECATED',
        E_USER_DEPRECATED => 'USER DEPRECATED',
    ];

    $errorType = isset($errorTypes[$errno]) ? $errorTypes[$errno] : 'UNKNOWN ERROR';
    $errorMessage = "[$errorType] $errstr in $errfile on line $errline";

    // Логирование ошибки
    error_log($errorMessage);

    // Отображение ошибки в блоке
    echo "<script>showError('" . addslashes($errorMessage) . "');</script>";
}

set_error_handler('customErrorHandler');

// Обработчик исключений
function customExceptionHandler($exception) {
    $errorMessage = "Uncaught exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine();

    // Логирование исключения
    error_log($errorMessage);

    // Отображение исключения в блоке
    echo "<script>showError('" . addslashes($errorMessage) . "');</script>";
}

set_exception_handler('customExceptionHandler');

// Функция для вывода данных массива
function dataprint($array) {
    // Преобразуем массив в строку JSON
    $data = json_encode($array);

    // Вызываем JavaScript функцию для отображения данных
    echo "<script>showErrorWithData(" . $data . ");</script>";
}

?>

<div id="error-block" class="error-block">
    <div class="error-content">
        <span id="error-message"></span>
        <button id="toggle-error-button">Свернуть</button>
    </div>
    <div id="data-container"></div>
</div>

<style>
.error-block {
    position: fixed;
    bottom: 0;
    right: 0;
    width: 50vw;
    height: 60vh;
    background-color: rgba(255, 73, 73, 0.9);
    color: white;
    z-index: 10000;
    display: none;
    flex-direction: column;
    padding: 20px;
    border: 2px solid #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    overflow-y: auto; /* Enable vertical scrolling */
}

.error-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    width: 100%;
}

#error-message {
    text-align: left;
    flex-grow: 1;
    font-size: 16px;
    line-height: 1.5;
}

#toggle-error-button {
    margin-left: auto;
    padding: 10px 20px;
    background-color: white;
    color: red;
    border: 1px solid red;
    border-radius: 4px;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    transition: background-color 0.3s, color 0.3s;
}

#toggle-error-button:hover {
    background-color: red;
    color: white;
}

#data-container {
    margin-top: 2px;
    background-color: rgba(255, 255, 255, 0.1);
    padding: 75px;
    border-radius: 4px;
    overflow-y: auto;
    max-height: 40vh;
    min-height: 30vh;
}

</style>

<script>
function showError(message) {
    // Get the error block and its elements
    const errorBlock = document.getElementById('error-block');
    const errorMessage = document.getElementById('error-message');
    const toggleButton = document.getElementById('toggle-error-button');

    // Append the new error message
    errorMessage.innerHTML += `<p>${message}</p>`;

    // Display the error block
    errorBlock.style.display = 'flex';
    errorBlock.style.height = '60vh';
    toggleButton.textContent = 'Свернуть';

    // Add event listener to the toggle button
    toggleButton.onclick = function() {
        if (errorBlock.style.height === '5vh') {
            errorBlock.style.height = '60vh';
            toggleButton.textContent = 'Свернуть';
        } else {
            errorBlock.style.height = '5vh';
            toggleButton.textContent = 'Развернуть';
        }
    };
}

function showErrorWithData(data) {
    showError(''); // Отображаем окно ошибки без сообщения об ошибке
    const dataContainer = document.getElementById('data-container');

    // Добавляем новые данные в контейнер
    dataContainer.innerHTML += `<pre>${JSON.stringify(data, null, 2)}</pre>`;
}



</script>
