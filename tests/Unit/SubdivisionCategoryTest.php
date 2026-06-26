<?php

declare(strict_types=1);

namespace Test\TinyBlocks\Country\Unit;

use PHPUnit\Framework\TestCase;
use TinyBlocks\Country\SubdivisionCategory;

final class SubdivisionCategoryTest extends TestCase
{
    public function testIsStateWhenStateThenTrue(): void
    {
        /** @Given the state category */
        $category = SubdivisionCategory::STATE;

        /** @When asking whether it is a state */
        $isState = $category->isState();

        /** @Then it is true */
        self::assertTrue($isState);
    }

    public function testIsStateWhenOtherThenFalse(): void
    {
        /** @Given a category that is not a state */
        $category = SubdivisionCategory::REGION;

        /** @When asking whether it is a state */
        $isState = $category->isState();

        /** @Then it is false */
        self::assertFalse($isState);
    }

    public function testIsRegionWhenOtherThenFalse(): void
    {
        /** @Given a category that is not a region */
        $category = SubdivisionCategory::STATE;

        /** @When asking whether it is a region */
        $isRegion = $category->isRegion();

        /** @Then it is false */
        self::assertFalse($isRegion);
    }

    public function testIsRegionWhenRegionThenTrue(): void
    {
        /** @Given the region category */
        $category = SubdivisionCategory::REGION;

        /** @When asking whether it is a region */
        $isRegion = $category->isRegion();

        /** @Then it is true */
        self::assertTrue($isRegion);
    }

    public function testIsDistrictWhenOtherThenFalse(): void
    {
        /** @Given a category that is not a district */
        $category = SubdivisionCategory::STATE;

        /** @When asking whether it is a district */
        $isDistrict = $category->isDistrict();

        /** @Then it is false */
        self::assertFalse($isDistrict);
    }

    public function testIsDistrictWhenDistrictThenTrue(): void
    {
        /** @Given the district category */
        $category = SubdivisionCategory::DISTRICT;

        /** @When asking whether it is a district */
        $isDistrict = $category->isDistrict();

        /** @Then it is true */
        self::assertTrue($isDistrict);
    }
}
