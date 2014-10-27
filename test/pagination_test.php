<?php

class PaginotionTest extends PHPUnit_Framework_TestCase {
  function getText($a, $b, $c, $d) {
    $a = (new Pagination($a, $b, $c, $d, function ($n) { return "http://abc/page/$n/"; }))->getItems();
    return array_map(function ($b) { return $b['text']; }, $a);
  }

  function testOther() {
    $a = (new Pagination(1, 1, 1, 0, function ($n) { return "http://abc/page/$n/"; }))->render();
    $this->assertContains('class="pagination"', $a);
  }

  function testBasic() {
    $this->assertSame(['«', '1', '»'], $this->getText(1, 1, 1, 0));
  }

  function testContext() {
    $this->assertSame(['«', '1', '2', '3', '»'], $this->getText(1, 3, 1, 0));
    $this->assertSame(['«', '1', '2', '3', '…', '»'], $this->getText(1, 4, 1, 0));
  }

  function testExtraContext() {
    $this->assertSame(['«', '1', '2', '3', '…', '6', '»'], $this->getText(1, 6, 1, 1));
    $this->assertSame(['«', '1', '2', '3', '…', '5', '6', '»'], $this->getText(1, 6, 1, 2));
    $this->assertSame(['«', '1', '2', '3', '4', '»'], $this->getText(1, 4, 1, 1));
  }
}
