<?php

/**
* $current = the current page we're on
* $max = total number of pages
* $context = number of pages to show before and after the current one (if the current page is less than the context then any unused context pages from before the current page are displayed after the current page - i.e. current=2, context=1: 1, _2_, 3 but current=1, context=1: _1_, 2, 3)
* $extraContext = the first/last pages (before/after the ellipses)
*/
class Pagination {
  function __construct($current, $max, $context, $extraContext, callable $url) {
    $this->current = $current;
    $this->max = $max;
    $this->context = $context;
    $this->extraContext = $extraContext;
    $this->url = $url;
  }

  function item($link, $text, $arrow, $current, $disabled) {
    $classes = [];
    if ($arrow) {
      // bootstrap uses no class, foundation uses arrow
      $classes[] = 'arrow';
    }
    if ($current) {
      $classes[] = 'active'; // bootstrap
      $classes[] = 'current'; // foundation
    }
    if ($disabled) {
      $classes[] = 'disabled'; // boostrap
      $classes[] = 'unavailable'; // foundation
    }

    return '<li class="'.htmlspecialchars(implode(' ', $classes)).'"><a href="'.htmlspecialchars(call_user_func($this->url, $link)).'">'.htmlspecialchars($text).'</a></li>';
  }

  function getItems() {
    $prev = $this->current - 1;
    $prev_disabled = false;
    if ($prev < 1) {
      $prev = 1;
      $prev_disabled = true;
    }

    $next = $this->current + 1;
    $next_disabled = false;
    if ($next > $this->max) {
      $next = $this->max;
      $next_disabled = true;
    }

    $items = [];

    // Set from/to ///////////////////////////////////////////////////////////

    $from = $this->current - $this->context;
    $to = $this->current + $this->context;

    if ($from < 1) {
      $to += (0 - $from) + 1;
      $from = 1;
    }

    if ($to > $this->max) {
      $from -= $to - $this->max;
      if ($from < 1) {
        $from = 1;
      }

      $to = $this->max;
    }

    // Arrow /////////////////////////////////////////////////////////////////

    $items[] = [
      'link' => $prev,
      'text' => '«',
      'arrow' => true,
      'current' => false,
      'disabled' => $prev_disabled,
    ];

    // Ellipsis //////////////////////////////////////////////////////////////

    if ($from > 1) {
      $items[] = [
        'link' => null,
        'text' => '…',
        'arrow' => false,
        'current' => false,
        'disabled' => true,
      ];
    }

    // Context and current ///////////////////////////////////////////////////

    for ($i = $from; $i <= $to; $i++) {
      $items[] = [
        'link' => $i,
        'text' => (string)$i,
        'arrow' => false,
        'current' => $i === $this->current,
        'disabled' => false,
      ];
    }

    // Ellipsis //////////////////////////////////////////////////////////////

    if ($to < $this->max) {
      $items[] = [
        'link' => null,
        'text' => '…',
        'arrow' => false,
        'current' => false,
        'disabled' => true,
      ];
    }

    // After extra context ///////////////////////////////////////////////////

    if ($to < $this->max) {
      for ($i = $this->max + 1 - $this->extraContext; $i <= $this->max; $i++) {
        $items[] = [
          'link' => $i,
          'text' => (string)$i,
          'arrow' => false,
          'current' => false,
          'disabled' => false,
        ];
      }
    }

    // Arrow /////////////////////////////////////////////////////////////////

    $items[] = [
      'link' => $next,
      'text' => '»',
      'arrow' => true,
      'current' => false,
      'disabled' => $next_disabled,
    ];

    return $items;
  }

  function render() {
    $s = [];

    $s[] = '<ul class="pagination">'; // bootstrap + foundation use the same class here

    foreach ($this->getItems() as $item) {
      $s[] = $this->item($item['link'], $item['text'], $item['arrow'], $item['current'], $item['disabled']);
    }
    $s[] = '</ul>';

    return implode('', $s);
  }
}
