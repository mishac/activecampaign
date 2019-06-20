<?php

namespace Drupal\activecampaign\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\activecampaign\ActiveCampaignAPI;

/**
 * Class SettingsForm.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'activecampaign.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('activecampaign.settings');
    $form['api_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Url'),
      '#description' => $this->t('API Url'),
      '#default_value' => $config->get('api_url'),
    ];
    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Key'),
      '#description' => $this->t('API Key'),
      '#maxlength' => 72,
      '#size' => 72,
      '#default_value' => $config->get('api_key'),
    ];

    return parent::buildForm($form, $form_state);

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('activecampaign.settings')
      ->set('api_key', $form_state->getValue('api_key'))
      ->set('api_url', $form_state->getValue('api_url'))
      ->save();

    $api = new ActiveCampaignAPI($form_state->getValue('api_url'), $form_state->getValue('api_key'));
    $api->syncLists();
  }

}
