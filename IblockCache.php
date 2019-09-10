<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

use \Bitrix\Main\Data\Cache;


/**
 * Класс для получения кэшированных значений инфоблока
 * Class IblockCache
 *
 * @author Gate Pavel <gate.pavel@yandex.ru>
 */
class IblockCache
{
    /**
     * Функция получения кэшированных значений
     *
     * Синтаксис похож на стандартную функцию CIBlockElement::GetList,
     * параметры $arOrder, $arFilter, $arSelect тоже ей соответствуют
     * для простоты применения
     *
     * @param array $arOrder массив сортировки
     * @param array $arFilter массив фильтров
     * @param array $arSelect массив парамтров выборки
     * @param int $cacheTime время кэширования, по дефолту 1 час
     *
     * @return array;
     */
    public static function getList($arOrder = ['SORT' => 'ASC'], $arFilter = [], $arSelect = ['*'], $cacheTime = 3600)
    {
        //Генерируем ключ кэша, зависящий от входных параметров
        $key = json_encode([$arOrder, $arFilter, $arSelect]);
        $cache = Cache::createInstance(); // получаем экземпляр класса кэша
        $out = [];
        if ($cache->initCache($cacheTime, $key, 'iblock_cache')) { // проверяем кеш и задаём настройки
            $out = $cache->getVars(); // достаем переменные из кеша
        }
        elseif ($cache->startDataCache()) {
            \Bitrix\Main\Loader::includeModule('iblock');
            $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
            while($ob = $res->GetNextElement())
            {
                $out[] = $ob->GetFields();
            }
            $cache->endDataCache($out); // записываем в кеш
        }

        return $out;
    }
}



/**
 * Пример работы, выбираем все элементы, кэширование на час
 */
$result = IblockCache::getList(['SORT' => 'ASC'], ['IBLOCK_ID' => 3]);
\Bitrix\Main\Diag\Debug::dump($result);