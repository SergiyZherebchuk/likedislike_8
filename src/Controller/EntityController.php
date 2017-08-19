<?php

namespace Drupal\likedislike\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\votingapi\Entity\Vote;
use Drupal\votingapi\VoteStorage;

class EntityController extends ControllerBase {

  public function likeAdd($entity_id) {
    if ($_GET['entityid']) {
      // Get the information of type of entity and entity ID
      $node_id = intval($entity_id);
      $entity_type = $_GET['entity'];
      $this->add_likedislike($node_id, $entity_type, 'like');
    }
  }

  public function dislikeAdd() {
    if ($_GET['entityid']) {
      // Get the information of type of entity and entity ID
      $node_id = intval($_GET['entityid']);
      $entity_type = $_GET['entity'];
      $this->add_likedislike($node_id, $entity_type, 'dislike');
    }
  }

  public function add_likedislike($node_id, $entity_type, $action) {
    global $user;
    $message = '';

    // If has permission to vote execute all the vote logic
    $can_vote =
      ($entity_type == 'comment' && \Drupal::currentUser()->hasPermission('like comment')) ||
      ($entity_type == 'node' && ($node = node_load($node_id)) && \Drupal::currentUser()->hasPermission('like node ' . $node->type));
    if ($can_vote) {
      //Check if disliked
      $checkCriteria = array(
        'entity_id' => $node_id,
        'tag' => $action == 'like' ? 'dislike' : 'like',
        'uid' => $user->uid,
        'entity_type' => $entity_type,
      );
      if ($user->uid == 0) {
        $checkCriteria['vote_source'] = \Drupal::request()->getClientIp();
      }
      $dislikeResult = Vote::load($checkCriteria);
      $dislikeCount = sizeof($dislikeResult);

      if ($dislikeCount == 1) {
        print $dislikeResult->getVotedEntityId();
        votingapi_delete_votes($dislikeResult);
      }

      $vote = array(
        'entity_id' => $node_id,
        'value'=> 1,
        'tag' => $action,
        'entity_type' => $entity_type,
      );
      $setVote = Vote::create($vote);
    } else {
      $message = $this->t(\Drupal::state()->get('likedislike_vote_denied_msg', "You don't have permission to vote"));
    }

    // Get the updated like/dislike counts and print them with a message if any
    $criteriaLike = array(
      'entity_id' => $node_id,
      'tag' => 'like',
      'entity_type' => $entity_type,
    );
    $criteriaDislike = array(
      'entity_id' => $node_id,
      'tag' => 'dislike',
      'entity_type' => $entity_type,
    );

    $likeCount = sizeof(Vote::load($criteriaLike));
    $dislikeCount = sizeof(Vote::load($criteriaDislike));
    print $likeCount . "/" . $dislikeCount . "/" . $message;
  }
}
