<?php

namespace Drupal\activecampaign\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ActiveCampaignListForm.
 */
class ActiveCampaignListForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $active_campaign_list = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $active_campaign_list->label(),
      '#description' => $this->t("Label for theActiveCampaign List."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $active_campaign_list->id(),
      '#machine_name' => [
        'exists' => '\Drupal\activecampaign\Entity\ActiveCampaignList::load',
      ],
      '#disabled' => !$active_campaign_list->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $active_campaign_list = $this->entity;
    $status = $active_campaign_list->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %labelActiveCampaign List.', [
          '%label' => $active_campaign_list->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %labelActiveCampaign List.', [
          '%label' => $active_campaign_list->label(),
        ]));
    }
    $form_state->setRedirectUrl($active_campaign_list->toUrl('collection'));
  }

}
