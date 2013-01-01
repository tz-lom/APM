<?php

use APM\Router;
use APM\SimpleMatch;

class BasicTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleMatch()
    {
        $p = new SimpleMatch('bar');
        $this->assertEquals('bar', $p->rebuildPath());

        $p->addPrefix('foo');
        $this->assertTrue($p->checkPath('foobar'));
    }

    public function testBasicRouter()
    {
        $r = new Router;

        $p0 = new SimpleMatch('foo');
        $p1 = new SimpleMatch('foo');
        $p2 = new SimpleMatch('bar');


        $this->assertSame($r, $r->add($p0));
        $r->add($p1);
        $r->add($p2);

        $this->assertSame($p0, $r->findFirstRoute('foo'));
        $this->assertSame($p2, $r->findFirstRoute('bar'));
        $this->assertEquals(NULL, $r->findFirstRoute('baz'));

        $this->assertSame(
            array($p0, $p1),
            $r->findAllRoutes('foo')
        );
        $this->assertSame(array($p2), $r->findAllRoutes('bar'));
        $this->assertEquals(array(), $r->findAllRoutes('baz'));
    }

    public function testSectioning()
    {
        $root = new Router();
        $r = $root;
        $this->assertSame($root, $root->getRoot());
        $this->assertSame($root, $root->endSubSection());


        $r->add(new SimpleMatch('root'));

        $r = $r->beginSubSection('foo/');
        $r->add(new SimpleMatch('item'));

        $r = $r->beginSubSection('bar/');
        $r->add(new SimpleMatch('item2'));

        $this->assertSame($root, $r->getRoot());

        $r = $r->endSubSection();
        $r->add(new SimpleMatch('item3'));

        $this->assertNotNull($root->findFirstRoute('root'));
        $this->assertNull($root->findFirstRoute('foo/'));
        $this->assertNotNull($root->findFirstRoute('foo/item'));
        $this->assertNotNull($root->findFirstRoute('foo/item3'));
        $this->assertNotNull($root->findFirstRoute('foo/bar/item2'));
        $this->assertNull($root->findFirstRoute('bar/item2'));
    }
}