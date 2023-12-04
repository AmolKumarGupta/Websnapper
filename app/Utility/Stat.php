<?php

namespace App\Utility;

class Stat 
{

    protected $model;

    protected string $label;

    protected string $prefix;

    protected string $suffix;

    /**
     * @param <class-string> $model
     */
    public function __construct($model)
    {
        $this->model = $model;
        return $this;
    }

    private function getLabel() 
    {
        $label = (new \ReflectionClass($this->model))->getShortName();
        $arr = preg_split('/(?=\p{Lu})/u', $label, -1, PREG_SPLIT_NO_EMPTY);
        return implode(' ', $arr);
    }

    public function __invoke()
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            'label' => $this->getLabel(),
            'value' => ($this->model)::count(),
        ];
    }

    public static function collect(Stat ...$stats): array 
    {
        return array_map(fn ($f) => $f(), $stats);
    }

}