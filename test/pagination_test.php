<?php

class PaginotionTest extends PHPUnit_Framework_TestCase {
  function getText($a, $b, $c) {
    $a = (new Pagination($a, $b, $c, function ($n) { return "http://abc/page/$n/"; }))->getItems();
    return array_map(function ($b) { return $b['text']; }, $a);
  }

  function testBasic() {
    $a = (new Pagination(1, 1, 1, function ($n) { return "http://abc/page/$n/"; }))->render();
    $this->assertContains('class="pagination"', $a);
  }

  function testText() {
    $this->assertSame(['«', '1', '»'], $this->getText(1, 1, 1));
  }
}
