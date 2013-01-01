<?php

use APM\Router;
use APM\URLRoute;

class URLRouteTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleMatching()
    {
        $p = new URLRoute('static');
        $this->assertTrue($p->checkPath('static'));
        $this->assertFalse($p->checkPath('foo/static'));

        $p->addPrefix('foo/');
        $this->assertTrue($p->checkPath('foo/static'));
        $this->assertFalse($p->checkPath('static'));
    }

    public function testTemplateMatching()
    {
        $p = new URLRoute('/foo/{a}/{b}');
        $this->assertFalse($p->checkPath('/foo'));
        $this->assertTrue($p->checkPath('/foo/bar/baz'));
        $this->assertEquals(
            array(
                 'a' => 'bar',
                 'b' => 'baz'
            ),
            $p->getSlugs()
        );
    }

    public function testOptionalField()
    {
        $p = new URLRoute('/foo/{bar?}');
        $this->assertTrue($p->checkPath('/foo/'));
        $this->assertEquals(
            array(
                 'bar' => NULL
            ),
            $p->getSlugs()
        );

        $this->assertTrue($p->checkPath('/foo/baz'));
        $this->assertEquals(
            array(
                 'bar' => 'baz'
            ),
            $p->getSlugs()
        );

        $this->assertFalse($p->checkPath('/foo/bar/baz'));
    }

    public function testCustomRegexpField()
    {
        $p = new URLRoute('/foo/{num:[0-9]+%}{tail?:.+}');
        $this->assertFalse($p->checkPath('/foo/bar'));
        $this->assertTrue($p->checkPath('/foo/42%'));
        $this->assertEquals(
            array(
                 'num'  => '42%',
                 'tail' => ''
            ),
            $p->getSlugs()
        );
        $this->assertEquals(
            '/foo/42%',
            $p->rebuildPath($p->getSlugs())
        );

        $this->assertTrue($p->checkPath('/foo/100%/and-more'));
        $this->assertEquals(
            array(
                 'num'  => '100%',
                 'tail' => '/and-more'
            ),
            $p->getSlugs()
        );
        $this->assertEquals(
            '/foo/100%/and-more',
            $p->rebuildPath($p->getSlugs())
        );
    }

    /**
     * @expectedException \APM\CannotRebuildPath
     */
    public function testRebuildPath()
    {
        $p = new URLRoute('/foo/{a}/{b}');
        $this->assertEquals(
            '/foo/bar/baz',
            $p->rebuildPath(array(
                                 'a' => 'bar',
                                 'b' => 'baz'
                            ))
        );

        $p->rebuildPath(array('a'=>'a','c'=>'c'));
    }
}