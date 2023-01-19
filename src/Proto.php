<?php

namespace is\Library;

use is\Library\ProtoLib\Data;
use is\Library\ProtoLib\Type;
use is\Library\ProtoLib\Display;

class Proto
{
    private $origin;
    private $data;
    private $type;
    private $display;

    /**
     * Конструктор, задает базовое значение и его же присваивает текущему.
     * Вторым аргументом можно задать типизацию данных,
     * которая будет применяться в дальнейшем каждый раз при установке нового значения
     *
     * @param  mixed  $origin  Базовое значение
     * @param  string  $type  Тип данных
     * @return self
     */
    public function __construct($origin = null, ?string $type = null)
    {
        $this->origin = new Data;
        $this->data = new Data;
        $this->type = new Type;
        $this->display = new Display;

        if ($type) {
            $this->type->set($type);
        }

        if ($origin !== null) {
            $this->origin->set($this->type->convert($origin));
        }

        return $this;
    }

    /**
     * Геттер, возвращает текущее значение.
     *
     * @return mixed
     */
    public function get()
    {
        return $this->data->get();
    }

    /**
     * Сеттер, задает текущее значение с приведением к типу.
     *
     * @param  mixed  $data  Новое значение
     * @return self
     */
    public function set($data)
    {
        $this->data->set($this->type->convert($data));
        return $this;
    }

    /**
     * Сброс текущего значения к исходному.
     *
     * @return self
     */
    public function reset()
    {
        $this->data->set($this->origin->get());
        return $this;
    }

    /**
     * Сброс текущего значения к исходному.
     *
     * @return boolean
     */
    public function is()
    {
        return $this->type->is($this->data->get());
    }

    /**
     * Вывод текущего значения в виде строки. Используется приведение к строке
     *
     * @return self
     */
    public function print()
    {
        $this->display->print($this->data);
        return $this;
    }
}