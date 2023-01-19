<?php

namespace is\Library\ProtoLib;

use is\Library\ProtoLib\Data;

/**
 * Класс работает со следующими типами данных:
 * - iterable - итерируемый тип, массив или объект (при приведении задается массив),
 * - numeric - число или строка, записанная числом,
 * - scalar - строковый тип, строка или число (при приведении задается строка),
 * - string - строка,
 * - true - значение true,
 * - пустой элемент - false, null, undefined, пустая строка или пустой объект/массив, но не ноль.
 */
class Type
{
    private $data;

    /**
     * Конструктор, создает объект данных.
     *
     * @return self
     */
    public function __construct()
    {
        $this->data = new Data;
        return $this;
    }

    /**
     * Сеттер, задает текущее значение.
     *
     * @param  mixed  $data  Новое значение
     * @return self
     */
    public function set($data)
    {
        $this->data->set($data);
        return $this;
    }

    /**
     * Проверка, что данные существуют и они не пустые.
     * Отличия от встроенных проверок:
     * - проверка на ноль дает true,
     * - проверка на пустой массив дает false.
     *
     * @param  mixed  $data  Данные
     * @return boolean
     */
    public function is(&$data)
    {
        if (isset($data) && $data === true) {
            return true;
        } elseif (!isset($data) || $data === false || $data === null) {
            return false;
        } elseif (empty($data) && is_numeric($data)) {
            return true;
        } elseif (empty($data)) {
            return false;
        } elseif (is_array($data) || is_object($data)) {
            foreach ($data as $i) {
                if (!empty($i) || is_numeric($i)) {
                    return true;
                }
            }
            return false;
        } elseif (is_string($data)) {
            return trim($data) !== '';
        }

        return true;
    }

    /**
     * Проверка типа данных.
     * Возвращает тип.
     * Если вторым аргументом задан тип, то вернет результат соответствия этому типу.
     *
     * @param  mixed  $data  Данные
     * @param  string  $type  Тип данных
     * @return string|boolean|null $result
     */
    public function compare(&$data, ?string $type = null)
    {
        if (!$this->is($data)) {
            return null;
        }

        $result = null;
        $common = null;

        if (is_bool($data)) {
            $result = 'true';
        } elseif (is_array($data) || is_object($data)) {
            $result = 'iterable';
        } elseif (is_scalar($data)) {
            $common = 'scalar';
            $result = 'string';
            if (is_numeric(preg_replace(
                ['/\s/u', '/\,/u'],
                ['', '.'],
                $data
            ))) {
                $result = 'numeric';
            }
        }

        if ($type) {
            return $type === $result || $type === $common;
        }

        return $result;
    }

    /**
     * Приведение данных к заданному типу.
     *
     * @param  mixed  $data  Данные
     * @return void
     */
    public function convert(&$data)
    {
        switch ($this->data->get()) {
            case 'true':
                $data = $data ? true : false;
                break;
            case 'numeric':
                $data = (float) $data;
                break;
            case 'scalar':
            case 'string':
                $data = is_array($data) || is_object($data) ? json_encode($data) : (string) $data;
                break;
            case 'iterable':
                $data = (array) $data;
                break;
        }
        return $data;
    }
}