<?php

namespace Tests\Unit;

use App\Models\Inventory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\InventoryConfigurationTrait;

class InventoryTotalCostTest extends TestCase
{
    use RefreshDatabase, WithFaker, InventoryConfigurationTrait;

    public function test_total_cost_calculation(): void
    {
        $inventories = $this->inventory(10);

        $totalCost = Inventory::calculateTotalCost();
        $inventories->each(function ($item) {
            return $item->total = $item->quantity * $item->unit_price;
        });

        $this->assertEquals(round($totalCost, 2), round($inventories->sum('total'), 2));
    }
}
