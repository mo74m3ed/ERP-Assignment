<?php

namespace App\Actions\Inventory;

use App\Helpers\LocaleHelper;
use App\Models\Inventory;
use Illuminate\Pagination\LengthAwarePaginator;

class InventoryListAction
{
    /** Maximum number of items that may be requested per page. */
    public const MAX_PER_PAGE = 100;

    /** Default number of items returned per page. */
    public const DEFAULT_PER_PAGE = 15;

    public function __invoke(): LengthAwarePaginator
    {
        // Fetch all records so we can apply a global locale-aware sort before
        // paginating. Sorting after Eloquent::paginate() would only order each
        // page in isolation, producing inconsistent cross-page ordering.
        // Note: in-memory sorting is intentional here — it mirrors FreshRSS#8985
        // and gives consistent locale-aware ordering that database COLLATE clauses
        // cannot guarantee across all supported database engines.
        // Mirrors the fix in FreshRSS/FreshRSS#8985.
        $collection = Inventory::all();
        $items = $collection->all(); // plain array for uasort
        uasort($items, static fn (Inventory $a, Inventory $b): int => LocaleHelper::localeCompare($a->name, $b->name));
        $sorted = array_values($items);

        // Clamp per_page to a safe range to prevent memory exhaustion.
        $perPage = max(1, min(self::MAX_PER_PAGE, (int) request()->input('per_page', self::DEFAULT_PER_PAGE)));
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $pageItems = array_slice($sorted, ($currentPage - 1) * $perPage, $perPage);

        return new LengthAwarePaginator($pageItems, count($sorted), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
    }
}
