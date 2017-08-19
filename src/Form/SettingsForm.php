<?php

namespace Drupal\likedislike\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\NodeType;
use Drupal\Core\Url;
use Drupal\likebtn\LikebtnInterface;

class SettingsForm extends ConfigFormBase {

  protected function getEditableConfigNames() {
    return;
  }

  public function getFormId() {
    return 'likedislike_settings';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['likedislike_vote_denied_msg'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Vote denied message'),
      '#description' => $this->t("This is the message that the user will see if doesn't have permission to vote"),
      '#default_value' => \Drupal::state()->get('likedislike_vote_denied_msg', "You don't have permission to vote"),
    );

    return parent::buildForm($form, $form_state);
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement validateForm() method.
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    \Drupal::state()->set('likedislike_vote_denied_msg', $values['likedislike_vote_denied_msg']);

    parent::submitForm($form, $form_state);
  }
}
