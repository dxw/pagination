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

  function render() {
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

    $s = [];
    $s[] = '<ul class="pagination">'; // bootstrap + foundation use the same class here
    $s[] = $this->item($prev, '«', true, false, $prev_disabled);

    $s[] = $this->item($this->current, $this->current, false, true, false);

    $s[] = $this->item($next, '»', true, false, $next_disabled);
    $s[] = '</ul>';

    return implode('', $s);
  }
}
