<?php

use Drupal\Core\Template\Attribute;

function anytown_preprocess_page(&$variables) {

  $is_front = \Drupal::service('path.matcher')->isFrontPage();

  $variables['is_front'] = $is_front;

}