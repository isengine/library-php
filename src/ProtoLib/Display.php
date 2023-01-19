<?php

namespace is\Library\ProtoLib;

class Display
{
    /**
     * Вывод текущего значения в виде строки. Используется приведение к строке
     *
     * @return void
     */
    public function print(&$data)
    {
        echo is_array($data) || is_object($data) ? json_encode($data) : $data;
    }

    /**
     * Функция для отладки, выводит содержимое текущих данных для проверки
     *
     * @param string $type Тип отладки: default dump q console hide
     * @param boolean $stop Остановить выполнение скрипта
     * @return void
     */
    public function debug(&$data, string $type = 'default', boolean $stop = false)
    {
        $pre = '<pre>';
        $post = '</pre>';
        $print = print_r(htmlentities($data), true);

        switch ($type) {
            case 'dump':
                $print = var_export($data, true);
                break;
            case 'q':
                $pre = '[';
                $post = ']<br>';
                break;
            case 'console':
                $pre = '<script>console.log(\'';
                $post = '\');</script>';
                $print = json_encode(print_r($data, true));
                break;
            case 'hide':
                $pre = '<!--';
                $post = '-->';
                break;
        }

        echo $pre, $print, $post;

        if ($stop) {
            exit;
        }
    }
}