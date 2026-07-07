<?php

namespace Tests\Unit;

use App\Helpers\LocaleHelper;
use Tests\TestCase;

class LocaleHelperTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        LocaleHelper::resetCollator();
    }

    protected function tearDown(): void
    {
        LocaleHelper::resetCollator();
        parent::tearDown();
    }

    public function test_returns_negative_when_first_string_is_less(): void
    {
        $this->assertLessThan(0, LocaleHelper::localeCompare('apple', 'banana'));
    }

    public function test_returns_positive_when_first_string_is_greater(): void
    {
        $this->assertGreaterThan(0, LocaleHelper::localeCompare('banana', 'apple'));
    }

    public function test_returns_zero_for_equal_strings(): void
    {
        $this->assertSame(0, LocaleHelper::localeCompare('apple', 'apple'));
    }

    public function test_accented_names_sort_near_base_letters(): void
    {
        // Without locale-aware sorting, 'Ábaco' (0xC3…) would sort after 'Zorro'.
        // With a Collator for 'es' (Spanish), it sorts before 'banana'.
        app()->setLocale('es');
        LocaleHelper::resetCollator();

        $names = ['banana', 'Manzana', 'nube', 'Zorro', 'Ábaco', 'Ñandú', 'Úlcera', 'árbol'];
        usort($names, fn (string $a, string $b) => LocaleHelper::localeCompare($a, $b));

        // 'Ábaco' and 'árbol' must appear before 'banana', not after 'Zorro'
        $posAbaco = array_search('Ábaco', $names, true);
        $posArbol = array_search('árbol', $names, true);
        $posBanana = array_search('banana', $names, true);
        $posNandu = array_search('Ñandú', $names, true);
        $posUlcera = array_search('Úlcera', $names, true);
        $posZorro = array_search('Zorro', $names, true);

        $this->assertIsInt($posAbaco);
        $this->assertIsInt($posArbol);
        $this->assertIsInt($posBanana);
        $this->assertIsInt($posNandu);
        $this->assertIsInt($posUlcera);
        $this->assertIsInt($posZorro);

        $this->assertLessThan($posBanana, $posAbaco);
        $this->assertLessThan($posBanana, $posArbol);
        $this->assertLessThan($posZorro, $posNandu);
        $this->assertLessThan($posZorro, $posUlcera);
    }
}
