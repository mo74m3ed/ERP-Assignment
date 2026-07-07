<?php

namespace App\Actions\Inventory;

use App\Helpers\LocaleHelper;
use App\Models\Inventory;
use Illuminate\Pagination\LengthAwarePaginator;

class InventoryListAction
{
    public function __invoke(): LengthAwarePaginator
    {
        // Fetch all records so we can apply a global locale-aware sort before
        // paginating. Sorting after Eloquent::paginate() would only order each
        // page in isolation, producing inconsistent cross-page ordering.
        // Mirrors the fix in FreshRSS/FreshRSS#8985.
        $all = Inventory::all()->all();
        uasort($all, static fn (Inventory $a, Inventory $b): int => LocaleHelper::localeCompare($a->name, $b->name));
        $sorted = array_values($all);

        $perPage = (int) (request()->input('per_page', 15));
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $items = array_slice($sorted, ($currentPage - 1) * $perPage, $perPage);

        return new LengthAwarePaginator($items, count($sorted), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
    }
}
