<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class ElementCollection extends Collection
{
    /**
     * Get an item from the collection by key, returning null if not found.
     * Prevents "Undefined array key" exceptions in Blade templates using $elements['key'].
     */
    public function offsetGet($key): mixed
    {
        return $this->items[$key] ?? null;
    }
}

