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
        $model = Mockery::mock(new BudgetModel());
        $model->shouldReceive('query')
            ->with($startDate, $endDate)
            ->andReturn($this->getStubData());
        $calculator = new BudgetCalculator($model);

        // Act
        $actual = $calculator->calculate($startDate, $endDate);

        // Assert
        $this->assertEquals($expected, $actual);
    }

    public function fixtureProvider()
    {
        return [
            ['2018/01/01', '2018/01/31', 3100],
            ['2018/02/01', '2018/02/28', 5600],
        ];
    }

    private function getStubData()
    {
        return [
            '201801' => 3100,
            '201802' => 5600, // 28*200
            '201804' => 3000,
            '201805' => 3100,
            '202001' => 310,  // 31*10
            '202002' => 8700, // 29*300
        ];
    }
}
