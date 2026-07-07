<?php

namespace App\Actions\Inventory;

use App\Helpers\LocaleHelper;
use App\Models\Inventory;
use Illuminate\Pagination\LengthAwarePaginator;

class InventoryListAction
{
    public function __invoke(): LengthAwarePaginator
    {
        $paginator = Inventory::paginate();

        // Re-sort using locale-aware collation so that accented names (e.g. Ábaco,
        // Ñandú) appear near their base letters rather than at the end of the list.
        // Mirrors the fix in FreshRSS/FreshRSS#8985.
        $items = $paginator->getCollection()->all();
        uasort($items, static fn (Inventory $a, Inventory $b): int => LocaleHelper::localeCompare($a->name, $b->name));

        return $paginator->setCollection(collect(array_values($items)));
    }
}
