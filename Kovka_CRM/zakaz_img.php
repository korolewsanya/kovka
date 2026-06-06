<?php
function can_upload($file){
    // если имя пустое, значит файл не выбран
    if($file['name'] == '')
        return 'Вы не выбрали файл.';
    
    // если размер файла 0, значит его не пропустили настройки сервера
    if($file['size'] == 0)
        return 'Файл слишком большой.';
    
    // проверяем расширение
    $getMime = explode('.', $file['name']);
    $mime = strtolower(end($getMime));
    $types = array('jpg', 'png', 'gif', 'bmp', 'jpeg');
    
    if(!in_array($mime, $types))
        return 'Недопустимый тип файла.';
    
    return true;
}

function make_upload($file){
    // формируем уникальное имя картинки: случайное число + оригинальное имя
    $name = mt_rand(0, 10000) . '_' . str_replace(' ', '_', basename($file['name']));
    
    // абсолютный путь к папке uploads
    $uploadDir = '../img/';
    
    // создаём папку, если её нет
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $destination = $uploadDir . $name;
    
    if (copy($file['tmp_name'], $destination)) {
        return $name; // возвращаем только имя файла
    } else {
        return false;
    }
}
?>