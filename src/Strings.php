<?php

namespace is\Library;

class Strings extends Proto
{
    /**
     * Конструктор, задает базовое значение и его же присваивает текущему.
     *
     * @return self
     */
    public function __construct()
    {
        return parent::__construct('', 'string');
    }

    /**
     * Проверка наличия подстроки или символа в заданной строке
     *
     * @param mixed $needle
     * @return boolean
     */
    public static function match($needle)
    {
        return !$this->is() || mb_strpos($this->get(), (string) $needle) === false ? null : true;
    }

    /**
     * Поиск подстроки или символа в заданной строке.
     * Возвращает позицию первого найденного значения.
     *
     * @param  mixed  $needle
     * @return int
     */
    public static function find($needle)
    {
        return mb_strpos($this->get(), (string) $needle);
    }

    /**
     * Поиск подстроки или символа в заданной строке.
     * Возвращает позицию последнего найденного значения.
     *
     * @param  mixed  $needle
     * @return int
     */
    public static function findLast($needle)
    {
        return mb_strrpos($this->get(), (string) $needle);
    }

    /**
     * Поиск подстроки или символа в заданной строке с указанием позиции.
     * Положительное значение - позиция с начала, отрицательное значение - позиция с конца.
     * Возвращает результат совпадения.
     *
     * @param  mixed  $needle
     * @param  int  $position
     * @return boolean
     */
    public static function findIn($needle, int $position = 0)
    {
        return (string) $needle === mb_substr($this->get(), $position, mb_strlen($needle));
    }

    /**
     * Возвращает подстроку по указанному индексу (позиции) и заданной длины
     * индекс считается от нуля
     * при отрицательных значениях идет отсчет с конца строки
     *
     * например, строка "position"
     * 0 :          > position
     * 2 :          >   sition
     * -2 :         >       on
     * 0, 2 :       > po
     * 2, 2 :       >   si
     * -2, 2 :      >       on
     * 0, -2 :      > positi
     * 2, -2 :      >   siti
     * -2, -2 :     > 
     *
     * @param [type] $index
     * @param [type] $length
     * @return self
     */
    public static function substring(int $index, int $length = 0)
    {
        if (!$length) {
            $this->set(mb_substr($this->get(), $index));
        } else {
            $total = $this->len();
            if ($index < 0) {
                $index = $total - abs($index);
            }
            if ($length < 0) {
                $length = $total - abs($index) - abs($length);
            }
            $this->set(mb_substr($this->get(), $index, $length));
        }
        return $this;
    }

    /**
     * Удаляет подстроку из строки по указанному индексу (позиции) и заданной длины.
     * Действие обратно методу substring.
     *
     * @param integer $index
     * @param [type] $length
     * @return self
     */
    public static function cut(int $index, int $length = 0)
    {
        $total = $this->len();
        if ($index < 0) {
            $index = $total - abs($index);
        }
        $result = mb_substr($this->get(), 0, $index);
        if ($length) {
            if ($length < 0) {
                $length = $total - abs($length);
            } elseif ($index > 0) {
                $length = $total - abs($length) - abs($index);
                if ($length <= 0) {
                    $length = $total;
                }
            }
            $result .= mb_substr($this->get(), $length);
        }
        $this->set($result);
        return $this;
    }

    /**
     * Возвращает подстроку до первого заданного значения
     * включение include позволяет включить в строку найденное значение
     *
     * @param [type] $needle
     * @param [type] $include
     * @return void
     */
    public static function before($needle, $include = null)
    {
        $position = $this->find($needle);
        if (!$position) {
            $this->reset();
        } else {
            $this->substring(0, $position + ($include ? mb_strlen($needle) : 0));
        }
        return $this;
    }

    /**
     * Возвращает подстроку до последнего заданного значения
     * включение include позволяет включить в строку найденное значение
     *
     * @param [type] $needle
     * @param [type] $include
     * @return void
     */
    public static function beforeLast($needle, $include = null)
    {
        $position = $this->findLast($needle);
        if (!$position) {
            $this->reset();
        } else {
            $this->substring(0, $position + ($include ? mb_strlen($needle) : 0));
        }
        return $this;
    }

    /**
     * Возвращает подстроку после первого заданного значения
     * включение include позволяет включить в строку найденное значение
     *
     * @param [type] $needle
     * @param [type] $include
     * @return void
     */
    public static function after($needle, $include = null)
    {
        $position = $this->find($needle);
        if (!$position) {
            $this->reset();
        } else {
            $this->substring($position + ($include ? 0 : mb_strlen($needle)));
        }
        return $this;
    }

    /**
     * Возвращает подстроку после последнего заданного значения
     * включение include позволяет включить в строку найденное значение
     *
     * @param [type] $needle
     * @param [type] $include
     * @return void
     */
    public static function afterLast($needle, $include = null)
    {
        $position = $this->findLast($needle);
        if (!$position) {
            $this->reset();
        } else {
            $this->substring($position + ($include ? 0 : mb_strlen($needle)));
        }
        return $this;
    }

    /**
     * Дополняет строку в конец символами или подстрокой на указанное число символов $len
     *
     * @param int $len
     * @param string $values
     * @return void
     */
    public static function add(int $len, $values = ' ')
    {
        $this->set(str_pad(
            $this->set(),
            $this->len() + $len,
            $values,
            STR_PAD_RIGHT // STR_PAD_LEFT
        ));
        return $this;
    }

    /**
     * Дополняет строку в начало символами или подстрокой на указанное число символов $len
     *
     * @param int $len
     * @param string $values
     * @return void
     */
    public static function addLeft(int $len, $values = ' ')
    {
        $this->set(str_pad(
            $this->set(),
            $this->len() + $len,
            $values,
            STR_PAD_LEFT
        ));
        return $this;
    }

    /**
     * Функция удаления символов из строки по их номерам
     *
     * @param [type] $needle
     * @return void
     */
    public static function remove($needle)
    {
        $haystack = $this->get();
        if (System::typeOf($haystack, 'iterable')) {
            return null;
        }

        $needle = Objects::convert($needle);
        $needle = Objects::sort($needle, true);
        $needle = Objects::reverse($needle);

        foreach ((array) $needle as $item) {
            $haystack = substr_replace($haystack, '', $item, 1);
        }
        unset($item);

        return $haystack;
    }

    /**
     * Функция повторяет строку string указанное число раз
     *
     * @param [type] $count
     * @return void
     */
    public static function multiply($count)
    {
        $string = $this->get();
        $result = null;

        if (
            !System::set($string)
            || !System::type($count, 'numeric')
        ) {
            return $string;
        }

        while ((int) $count > 0) {
            $result .= $string;
            $count--;
        }

        return $result;
    }

    /**
     * Функция разворота строки задом наперед
     *
     * @param [type] $item
     * @return void
     */
    public static function reverse()
    {
        $item = $this->get();
        $item = mb_convert_encoding($item, 'UTF-16LE', 'UTF-8');
        $item = strrev($item);
        return mb_convert_encoding($item, 'UTF-8', 'UTF-16BE');
    }

    /**
     * Функция возврата первого символа строки
     *
     * @return void
     */
    public static function first()
    {
        $item = $this->get();
        if (!$item) {
            return;
        }
        return $item[0];
    }

    /**
     * Функция возврата последнего символа строки
     *
     * @return void
     */
    public static function last()
    {
        $item = $this->get();
        if (!$item) {
            return;
        }

        return mb_substr($item, -1);
    }

    /**
     * Функция замены первого символа строки
     *
     * @param [type] $data
     * @return void
     */
    public static function refirst($data)
    {
        $item = $this->get();
        if (!$item) {
            return;
        }

        $item = $data . self::unfirst($item);
        return $item;
    }

    /**
     * Функция замены последнего символа строки
     *
     * @param [type] $data
     * @return void
     */
    public static function relast($data)
    {
        $item = $this->get();
        if (!$item) {
            return;
        }

        $item = self::unlast($item) . $data;
        return $item;
    }

    /**
     * Функция возврата первого символа строки
     *
     * @return void
     */
    public static function unfirst()
    {
        $item = $this->get();
        return System::set($item) ? mb_substr($item, 1) : '';
    }

    /**
     * Функция возврата последнего символа строки
     *
     * @return void
     */
    public static function unlast()
    {
        $item = $this->get();
        return System::set($item) ? mb_substr($item, 0, -1) : null;
    }

    /**
     * Функция возврата длины строки
     *
     * @return void
     */
    public static function len()
    {
        return mb_strlen($this->get());
    }

    /**
     * Функция разбивает строку на массив данных по указанным символам
     *
     * @param string $splitter
     * @return void
     */
    public static function split($splitter = '\s,;', $clear = null)
    {
        $item = $this->get();

        if (System::typeOf($item) !== 'scalar') {
            return null;
        } elseif (System::type($splitter) !== 'string') {
            return [$item];
        }

        $result = preg_split('/[' . $splitter . ']/u', $item, 0, 0);

        if (System::set($clear)) {
            $result = Objects::clear($result);
            //$result = array_diff($result, [null]);
        }

        return $result;
        //return preg_split('/[' . $splitter . ']/u', $item, null, System::set($clear) ? PREG_SPLIT_NO_EMPTY : null);
    }

    /**
     * Функция объединяет массив в строку с разделителем
     *
     * @param [type] $item
     * @param string $splitter
     * @return void
     */
    public static function join($item, $splitter = ' ')
    {
        if (!is_array($item) && !is_object($item)) {
            return;
        }

        if (is_object($item)) {
            $item = (array) $item;
        }

        $this->set(implode($splitter, $item));
    }

    /**
     * Функция объединяет массив в строку с разделителями
     * можно указать разделители между ключами, между значениями
     * первую и последную строки, которые будут добавлены только если результат не будет пустым
     *
     * @param [type] $item
     * @param [type] $keys
     * @param [type] $values
     * @param [type] $first
     * @param [type] $last
     * @return void
     */
    public static function combine($item, $keys = null, $values = null, $first = null, $last = null)
    {
        if (!System::typeIterable($item)) {
            return $item;
        }

        $result = null;

        $f = Objects::first($item, 'key');

        foreach ($item as $k => $i) {
            $result .= ($k === $f ? null : $keys) . $k . $values . $i;
        }
        unset($k, $i);

        return $result ? $first . $result . $last : null;
    }

    /**
     * Функция объединяет массив в строку по маске {k} {i}
     * except содержит символы-исключения, которые будут очищены из массива
     *
     * @param [type] $item
     * @param [type] $mask
     * @param [type] $first
     * @param [type] $last
     * @param [type] $except
     * @return void
     */
    public static function combineMask($item, $mask, $first = null, $last = null, $except = null)
    {
        if (!System::typeIterable($item)) {
            return $except ? self::except($item, $except) : $item;
        }

        $result = $first;

        foreach ($item as $k => $i) {
            if ($except) {
                $k = self::except($k, $except);
                $i = self::except($i, $except);
            }
            $result .= self::replace($mask, ['{k}', '{i}'], [$k, $i]);
        }
        unset($k, $i);

        return $result . $last;
    }

    /**
     * Функция очистки строки от указанных символов
     *
     * @param [type] $item
     * @param [type] $except
     * @return void
     */
    public static function except($item, $except = null)
    {
        if (
            !System::set($item)
            || !System::set($except)
        ) {
            return $item;
        }

        return preg_replace('/[' . preg_quote($except, '/') . ']/u', '', $item);
    }

    /**
     * Функция замены search на replace в строке item
     * поддерживает массив замен, как в оригинальной функции на php так и в js реализации
     *
     * @param [type] $item
     * @param [type] $search
     * @param string $replace
     * @return void
     */
    public static function replace($item, $search, $replace = '')
    {
        return System::set($item) ? str_replace($search, $replace, $item) : null;
    }

    /**
     * Функция удаления всех пробелов и пустых символов из строки
     *
     * @param [type] $item
     * @return void
     */
    public static function clear($item)
    {
        return preg_replace('/(\s|\r|\n|\r\n)+/u', '', $item);
    }

    /**
     * Функция удаления одинаковых значений из строки
     *
     * @param [type] $item
     * @return void
     */
    public static function unique($item)
    {
        $result = preg_split('//u', $item);
        $result = array_unique($result);
        $result = implode('', $result);

        return $result;
    }

    /**
     * Функция сортировки строки по символам
     * вторым аргументом можно выключить сортировку с учетом регистра
     *
     * @param [type] $haystack
     * @param boolean $register
     * @return void
     */
    public static function sort($haystack, $register = true)
    {
        $haystack = preg_split('//u', $haystack);

        sort($haystack, $register ? SORT_NATURAL : SORT_NATURAL | SORT_FLAG_CASE);

        $str = implode('', $haystack);

        //foreach ($haystack as $item) {
        //    $str .= $item;
        //}
        //unset($item);

        return $str;
    }

    /**
     * Функция сортировки строки в случайном порядке
     *
     * @param [type] $haystack
     * @return void
     */
    public static function random($haystack)
    {
        $haystack = preg_split('//u', $haystack);
        shuffle($haystack);
        return self::join($haystack, null);
    }

    /**
     * Функция возвращает строку, содержащую различия между двумя строками
     *
     * @param [type] $haystack
     * @param [type] $needle
     * @return void
     */
    public static function difference($haystack, $needle)
    {
        $haystack = preg_split('//u', $haystack);
        $needle = preg_split('//u', $needle);

        $diff = array_diff($haystack, $needle);

        $str = null;

        if (!empty($diff)) {
            foreach ($diff as $item) {
                $str .= $item;
            }
            unset($item);
        }

        return $str;
    }

    /**
     * Функция, которая разбивает строку на значения до сплиттера и после сплиттера
     * и возвращает в виде массива
     * сплиттер вырезается из строки
     *
     * @param [type] $string
     * @param string $splitter
     * @return void
     */
    public static function pairs($string, $splitter = ':')
    {
        $pos = (int) self::find($string, $splitter);

        return [
            self::substring($string, 0, $pos),
            self::substring($string, $pos + 1)
        ];
    }

    /**
     * Функция, которая разбивает строку на значения до индекса и после индекса
     * и возвращает в виде массива
     * индекс вырезается из строки, но
     * можно задать смещение, и тогда индекс останется либо в строке после (1), либо в строке до (-1)
     *
     * @param [type] $string
     * @param [type] $index
     * @param [type] $offset
     * @return void
     */
    public static function pairsByIndex($string, $index, $offset = null)
    {
        $before = $offset < 0 ? 1 : 0;
        $after = $offset > 0 ? 0 : 1;

        return [
            self::substring($string, 0, $index + $before),
            self::substring($string, $index + $after)
        ];
    }
}
