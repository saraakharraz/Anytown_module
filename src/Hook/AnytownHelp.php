<?php


declare(strict_types=1);

namespace Drupal\anytown\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

class AnytownHelp {

use StringTranslationTrait;
  
  private $currentUser;

  public function __construct(AccountProxyInterface $current_user) {
    $this->currentUser = $current_user;
  }

  /**
   * Implements hook_help().
   */
  #[Hook('help')]
  public function help($route_name, RouteMatchInterface $route_match) {
    if ($route_name === 'help.page.anytown') {
      $name = $this->currentUser->getDisplayName();
      return '<p>' .$this->t("Hi %name, the anytown module provides code specific to the Anytown Farmer's market website. This includes the weather forecast page, block, and related settings. route name : @routename", ['%name' => $name,'@routename'=>$route_match->getRouteName()] ) . '</p>';
    }
  }

}