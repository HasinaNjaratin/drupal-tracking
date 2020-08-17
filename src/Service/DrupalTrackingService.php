<?php

namespace Drupal\drupal_tracking\Service;

use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\Core\TempStore\TempStoreException;
use Drupal\Core\TempStore\PrivateTempStoreFactory;

/**
 * Class DrupalTrackingService.
 */
class DrupalTrackingService {

  /**
   * Drupal\Core\TempStore\PrivateTempStoreFactory definition.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected $tempstorePrivate;

  /**
   * LoggerChannelFactory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactory
   */
  protected $loggerFactory;

  /**
   * Constructs a new DrupalTrackingService object.
   *
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $tempstore_private
   *   Private tempstore factory.
   * @param \Drupal\Core\Logger\LoggerChannelFactory $loggerFactory
   *   LoggerChannelFactory.
   */
  public function __construct(PrivateTempStoreFactory $tempstore_private, LoggerChannelFactory $loggerFactory) {
    $this->tempstorePrivate = $tempstore_private;
    $this->loggerFactory = $loggerFactory;
  }

  /**
   * Store Actions.
   *
   * @param array $data
   *   Actions data.
   */
  public function storeClientAction(array $data) {
    $store = $this->tempstorePrivate->get('drupal_tracking_collection');
    try {
      $stored_data = $store->get('actions') ?? [];
      array_push($stored_data, $data);
      $store->set('actions', $stored_data);
    }
    catch (TempStoreException $e) {
      $this->loggerFactory->get('drupal_tracking')->notice('<code><pre>' . $e->getMessage() . '</code></pre>');
    }
  }

}
