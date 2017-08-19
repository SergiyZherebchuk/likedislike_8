<?php

namespace Drupal\likedislike;

use Drupal\node\Entity\NodeType;

class FilterPermissions {
  public function permissions() {
    $permissions = [];

    $node_types = NodeType::loadMultiple();
    foreach ($node_types as $type_name => $type_info) {
      $permissions['like node ' . $type_name] = array(
        'title' => t('Add like to %type', array('%type' => $type_name)),
        'description' => t('Allow users to add like to the nodes of type %type.', array('%type' => $type_name)),
        'restrict access' => TRUE,
      );
      $permissions['view likes ' . $type_name] = array(
        'title' => t('View likes of %type', array('%type' => $type_name)),
        'description' => t('Allow users to view likes to the nodes of type %type.', array('%type' => $type_name)),
        'restrict access' => TRUE,
      );
    }
    return $permissions;
  }
}
