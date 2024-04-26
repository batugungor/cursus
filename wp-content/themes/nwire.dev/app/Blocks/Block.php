<?php

namespace app\Blocks;

abstract class Block
{
    public string $name;
    public string $title;
    public function __construct()
    {
        $reflectionClass = new \ReflectionClass(static::class);
        $fileName = $reflectionClass->getShortName();
        $this->name = $fileName;
        $this->title = preg_replace('/(?<!^)(?<!\ )[A-Z](?![A-Z])/', ' $0', $fileName);
    }
    abstract public function fields(): array;
}

