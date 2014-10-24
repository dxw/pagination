<?php

class PaginotionTest extends PHPUnit_Framework_TestCase {
  function testBasic() {
    $a = (new Pagination(1, 1, 1, function ($n) { return "http://abc/page/$n/"; }))->render();
    $this->assertContains('class="pagination"', $a);
  }
}
