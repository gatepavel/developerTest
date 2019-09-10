<?php
/**
 * @author Gate Pavel <gate.pavel@yandex.ru>
 */

$rss = 'https://lenta.ru/rss'; //ссылка на поток
$count = 5; //число выводимых элементов

//Так как требований к производительности не задавалось, будем использовать
// для парсинга самый простой вариант - simpleXml
$xml = simplexml_load_file($rss);

if ($xml) { //проверка на корректность загрузки файла
    //немного оформим стилями ячейки таблицы
    ?>
    <style>
        td {
            border: solid 1px #000;
            padding: 5px;
        }
        tr:nth-child(odd) {
            background: #ddd;
        }
    </style>
    <?php
    echo "<table>";

    for($i = 0; $i<$count; $i++)
    {
        $item =  $xml->channel->item[$i];
        if($item) {
            echo "<tr><td>{$item->title}</td><td><a href=\"{$item->link}\" target=\"_blank\">{$item->link}</a></td><td>{$item->description}</td></tr>";
        }
    }
    echo  "</table>";
} else {
    echo "Не удалось получить данные с {$rss}";
}

