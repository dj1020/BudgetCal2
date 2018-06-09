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
      */
    public function it_should_get_one_month_budget()
    {
        // Arrange
        $calculator = new BudgetCalculator();

        // Act
        $startDate = '2018/01/01';
        $endDate = '2018/01/31';
        $actual = $calculator->calculate($startDate, $endDate);

        // Assert
        $expected = 3100;
        $this->assertEquals($expected, $actual);
    }
}
