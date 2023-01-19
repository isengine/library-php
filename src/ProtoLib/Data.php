<?php

namespace is\Library\ProtoLib;

class Data
{
    private $data;

    /**
     * Геттер, возвращает текущее значение.
     *
     * @return mixed
     */
    public function get()
    {
        return $this->data;
    }

    /**
     * Сеттер, задает текущее значение.
     *
     * @param  mixed  $data  Базовое значение
     * @return void
     */
    public function set($data = null)
    {
        $this->data = $data;
    }
}