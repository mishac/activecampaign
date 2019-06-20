<?php

namespace Drupal\activecampaign\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the ActiveCampaign List entity.
 *
 * @ConfigEntityType(
 *   id = "active_campaign_list",
 *   label = @Translation("ActiveCampaign List"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\activecampaign\ActiveCampaignListListBuilder",
 *     "route_provider" = {
 *       "html" = "Drupal\activecampaign\ActiveCampaignListHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "active_campaign_list",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/activecampaign/active_campaign_list/{active_campaign_list}",
 *     "collection" = "/admin/config/activecampaign/active_campaign_list"
 *   }
 * )
 */
class ActiveCampaignList extends ConfigEntityBase implements ActiveCampaignListInterface {

  /**
   * The ActiveCampaign List ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The ActiveCampaign List label.
   *
   * @var string
   */
  protected $label;

}
