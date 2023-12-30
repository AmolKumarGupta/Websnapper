<?php

namespace App\Utility;

class Stat 
{

    protected $model;

    protected string $label;

    protected string $prefix;

    protected string $suffix;

    protected $aggFunc = null;

    /**
     * @param <class-string> $model
     */
    public function __construct($model)
    {
        $this->model = $model;
        return $this;
    }

    public function label($label) 
    {
        $this->label = $label;
        return $this;
    }

    public function agg($func) 
    {
        $this->aggFunc = $func;
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
        $query = ($this->model)::query();

        return [
            'label' => $this->label ?? $this->getLabel(),
            'value' => ($this->aggFunc === null) 
                ? $query->count()
                : ($this->aggFunc)($query),
        ];
    }

    public static function collect(Stat ...$stats): array 
    {
        return array_map(fn ($f) => $f(), $stats);
    }

}