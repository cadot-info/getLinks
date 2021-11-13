<?php

namespace App\Tests;



use function CadotInfo\getLinks;
use PHPUnit\Framework\TestCase;



class Test  extends TestCase
{
    public function testOptNotRecognized(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (getLinks('<!DOCTYPE html><html><a href="https://github.com/cadot-info/testTools">github</a></html>', 0, ['test' => 'test']));
        throw new \InvalidArgumentException();
    }
    public function testUrl(): void
    {
        $this->assertGreaterThan(1, getLinks('https://github.com'));
    }
    public function testOne(): void
    {
        $this->assertCount(1, getLinks('<!DOCTYPE html><html><a href="https://github.com/cadot-info/testTools">github</a></html>', 0));
    }
    public function testRecursivity(): void
    {
        $this->assertGreaterThan(2, getLinks('<!DOCTYPE html><html><a href="https://github.com/cadot-info/testTools">github</a></html>', 1));
    }
    public function testcontrolhttps(): void
    {
        //control https exists
        $this->assertCount(1, getLinks('<!DOCTYPE html><html><a href="https://github.com/cadot-info/testTools">github</a></html>', 0, ['2points' => []]));
    }
    public function testcontrolnohttps(): void
    {
        //control remove this links
        $this->assertCount(0, getLinks('<!DOCTYPE html><html><a href="https://github.com/cadot-info/testTools">github</a></html>', 1, ['2points' => ['https']]));
    }
    public function testcontrolsublink(): void
    {
        //control refuse sub links with tests
        $this->assertGreaterThan(2, getLinks('<!DOCTYPE html><html><a href="https://github.com/cadot-info/testTools">github</a></html>', 1, ['2points' => ['https'], 'pass' => true]));
    }
    public function testpoint(): void
    {
        $this->assertCount(0, getLinks('<!DOCTYPE html><html><a href="https://github.com/cadot-info/testTools">github</a></html>', 1, ['point' => ['https://github']]));
    }
    public function testclass(): void
    {
        $this->assertCount(0, getLinks('<!DOCTYPE html><html><a href="https://github.com/cadot-info/testTools" class="test">github</a></html>', 0, ['class' => ['test']]));
    }
    public function testempty(): void
    {
        $this->assertCount(0, getLinks('<!DOCTYPE html><html><a href="" >github</a></html>', 0));
    }
    public function testiholdlink(): void
    {
        $this->assertCount(1, getLinks('<!DOCTYPE html><html></html>', 0, [], ['https://github.com' => 'github']));
    }
    public function testihavelink(): void
    {
        $this->assertCount(2, getLinks('<!DOCTYPE html><html><a href="https://github.com" >github</a><a href="toto">toto</a></html>', 0, [], ['https://github.com' => 'github']));
    }
    public function teststart(): void
    {
        $this->assertCount(0, getLinks('<!DOCTYPE html><html><a href="https://github.com/cadot-info/testTools" class="test">github</a></html>', 0, ['start' => ['https://github']]));
    }
}
