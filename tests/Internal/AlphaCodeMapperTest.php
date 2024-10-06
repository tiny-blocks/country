<?php

declare(strict_types=1);

namespace TinyBlocks\Country\Internal;

use PHPUnit\Framework\TestCase;
use TinyBlocks\Country\Internal\Exceptions\AlphaCodeNotFound;
use TinyBlocks\Country\Models\AlphaCodeXpto;

final class AlphaCodeMapperTest extends TestCase
{
    public function testExceptionWhenAlphaCodeNotFound(): void
    {
        /** @Given an AlphaCode enum case */
        $alphaCode = AlphaCodeXpto::SWITZERLAND;

        /** @Then expect an AlphaCodeNotFound exception */
        $this->expectException(AlphaCodeNotFound::class);
        $this->expectExceptionMessage('Alpha code with name <XXX> not found.');

        /** @When calling getBy with an invalid code */
        $alphaCode->getBy(name: 'XXX', alphaCodes: $alphaCode::cases());
    }
}
