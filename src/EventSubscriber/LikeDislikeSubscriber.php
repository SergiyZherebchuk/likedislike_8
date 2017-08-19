<?php

namespace Drupal\likedislike\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LikeDislikeSubscriber implements EventSubscriberInterface {

  public function checkForRedirection(GetResponseEvent $event) {
    $base_path = base_path();
    $module_path = drupal_get_path('module', 'likedislike');

    drupal_add_js("var base_path = '" . $base_path . "'; var module_path = '" . $module_path . "';", "inline");
    $event['#attached']['library'][] = 'likebtn/likebtn-libraries';
  }

  /**
   * { @inheritdoc }
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST] = array('checkForRedirection');
    return $events;
  }
}
