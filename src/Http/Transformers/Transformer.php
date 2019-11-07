<?php

namespace App\Http\Transformers;


abstract class Transformer
{

    public function transformCollection(array $items)
    {
        $itemsArray = [];

        foreach ($items as $item) {

            if ($this->transform($item)) {
                $itemsArray[] = $this->transform($item);
            }
        }
        return $itemsArray;
    }

    public abstract function transform($item);


}
