<?php

namespace App\Tests;



use CadotInfo\getLinks;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertFalse;
use Symfony\Component\Panther\PantherTestCase;
use function PHPUnit\Framework\assertGreaterThan;


class Test  extends TestCase
{
    use getLinks;
    public function testOptNotRecognized(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        ($this->getLinks('<!DOCTYPE html><html><a href="https://github.com/cadot-info/testTools">github</a></html>', 0, ['test' => 'test']));
        throw new \InvalidArgumentException();
    }
    public function testUrl(): void
    {
        $this->assertGreaterThan(1, $this->getLinks('https://github.com'));
    }
    public function testOne(): void
    {
        assertCount(1, $this->getLinks('<!DOCTYPE html><html><a href="https://github.com/cadot-info/testTools">github</a></html>', 0));
    }
    public function testRecursivity(): void
    {
        assertGreaterThan(2, $this->getLinks('<!DOCTYPE html><html><a href="https://github.com/cadot-info/testTools">github</a></html>', 1));
    }
    public function testcontrolhttps(): void
    {
        //control https exists
        assertCount(1, $this->getLinks('<!DOCTYPE html><html><a href="https://github.com/cadot-info/testTools">github</a></html>', 0, ['urlTwoPoints' => []]));
    }
    public function testcontrolnohttps(): void
    {
        //control remove this links
        assertCount(0, $this->getLinks('<!DOCTYPE html><html><a href="https://github.com/cadot-info/testTools">github</a></html>', 1, ['urlTwoPoints' => ['https']]));
    }
    public function testcontrolsublink(): void
    {
        //control refuse sub links with tests
        assertGreaterThan(2, $this->getLinks('<!DOCTYPE html><html><a href="https://github.com/cadot-info/testTools">github</a></html>', 1, ['urlTwoPoints' => ['https'], 'passRefuse' => true]));
    }
    public function testurlPoint(): void
    {
        assertCount(0, $this->getLinks('<!DOCTYPE html><html><a href="https://github.com/cadot-info/testTools">github</a></html>', 1, ['urlPoint' => ['https://github']]));
    }
    public function testclass(): void
    {
        assertCount(0, $this->getLinks('<!DOCTYPE html><html><a href="https://github.com/cadot-info/testTools" class="test">github</a></html>', 0, ['classRefuse' => ['test']]));
    }
    public function testempty(): void
    {
        assertCount(0, $this->getLinks('<!DOCTYPE html><html><a href="" >github</a></html>', 0));
    }
    public function testiholdlink(): void
    {
        assertCount(1, $this->getLinks('<!DOCTYPE html><html></html>', 0, [], ['https://github.com' => 'github']));
    }
    public function testihavelink(): void
    {
        assertCount(2, $this->getLinks('<!DOCTYPE html><html><a href="https://github.com" >github</a><a href="toto">toto</a></html>', 0, [], ['https://github.com' => 'github']));
    }
    public function testnostart(): void
    {
        assertCount(0, $this->getLinks('<!DOCTYPE html><html><a href="https://github.com/cadot-info/testTools" class="test">github</a></html>', 0, ['noStart' => ['https://github']]));
    }
}
