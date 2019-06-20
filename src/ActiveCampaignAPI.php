<?php

namespace Drupal\activecampaign;

use ActiveCampaign;
use Drupal\activecampaign\Entity\ActiveCampaignList;
use Drupal\Component\Utility\Html;

/**
 * ActiveCampaign Api wrapper.
 */
class ActiveCampaignAPI {

  protected $ac;
  protected $apiKey;
  protected $apiUrl;

  /**
   * Constructor.
   */
  public function __construct($apiUrl, $apiKey) {
    $this->apiKey = $apiKey;
    $this->apiUrl = $apiUrl;
    $this->connect();
  }

  /**
   * Authenticate.
   */
  protected function connect() {
    $ac = new ActiveCampaign($this->apiUrl, $this->apiKey);
    if (!(int) $ac->credentials_test()) {
      drupal_set_message(t("Access denied: Invalid credentials (URL and/or API key).</p>"));
    }
    $ac->set_curl_timeout(10);
    $this->ac = $ac;
  }

  /**
   * Get Lists.
   */
  public function getLists() {
    $params = [
      'ids'  => 'all',
      'full' => '1',
    ];
    $result = $this->ac->api("list/list_", $params);

    if ($result->result_code === 1) {
      $arr = (array) $result;
      return array_filter($arr, function ($val, $key) {
        return is_numeric($key) && is_object(($val));
      }, ARRAY_FILTER_USE_BOTH);
    }
  }

  /**
   * Sync Lists.
   */
  public function syncLists() {
    $storage = \Drupal::entityTypeManager()->getStorage('active_campaign_list');
    $drupal_lists = $storage->loadMultiple();
    $ac_lists = $this->getLists();
    $ac_list_ids = array_map(function ($list) {
      return $this->generateMachineName($list->listid, $list->stringid);
    }, $ac_lists);

    foreach ($ac_lists as $list) {
      $machine_name = $this->generateMachineName($list->listid, $list->stringid);
      $dl = $storage->loadByProperties(['id' => $machine_name]);

      if (empty($dl)) {
        $entity = ActiveCampaignList::create([
          'id' => $machine_name,
          'label' => $list->name,
        ]);
        $entity->save();
      }
    }

    foreach ($drupal_lists as $list) {
      if (!in_array($list->get('id'), $ac_list_ids)) {
        $list->delete();
      }
    }
  }

  /**
   * Generate machine name.
   */
  private function generateMachineName($id, $label) {
    return Html::cleanCssIdentifier($label . '-' . $id);
  }

}
