<?php

class Pagination {
  function __construct($current, $max, $context, callable $url) {
    $this->current = $current;
    $this->max = $max;
    $this->context = $context;
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
      $next = 1;
      $next_disabled = true;
    }

    $items = [];

    $items[] = [
      'link' => $prev,
      'text' => '«',
      'arrow' => true,
      'current' => false,
      'disabled' => $prev_disabled,
    ];

    $items[] = [
      'link' => $this->current,
      'text' => (string)$this->current,
      'arrow' => false,
      'current' => true,
      'disabled' => false,
    ];

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
