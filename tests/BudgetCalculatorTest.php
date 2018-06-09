<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../src/BudgetCalculator.php';

class BudgetCalculatorTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_initialize_an_instance_of_calculator()
    {
        // Act
        $calculator = new BudgetCalculator();

        // Assert
        $this->assertInstanceOf(BudgetCalculator::class, $calculator);
    }

    /**
     * @test
     * @dataProvider fixtureProvider
     */
    public function it_should_calculate_budget($startDate, $endDate, $expected)
    {
        // Arrange
        $calculator = new BudgetCalculator();

        // Act
        $actual = $calculator->calculate($startDate, $endDate);

        // Assert
        $this->assertEquals($expected, $actual);
    }

    public function fixtureProvider()
    {
        return [
            ['2018/01/01', '2018/01/31', 3100]
        ];
    }
}
