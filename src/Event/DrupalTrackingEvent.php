<?php

namespace Drupal\drupal_tracking\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class DrupalTrackingEvent.
 *
 * @package Drupal\drupal_tracking\Event
 */
class DrupalTrackingEvent extends Event {

  const PORTAL_SEARCH_EVENT = 'drupal_tracking.event.search';

  /**
   * Event category.
   *
   * @var string
   */
  protected $category;

  /**
   * Data about event.
   *
   * @var array
   */
  protected $data;

  /**
   * Constructs an event object.
   *
   * @param string $category
   *   Event category.
   * @param array $data
   *   Event data parameters.
   */
  public function __construct($category, array $data = []) {
    $this->category = $category;
    $this->data = $data;
  }

  /**
   * Get the event category.
   */
  public function getCategory() {
    return $this->category;
  }

  /**
   * Get the inserted data.
   */
  public function getEventData() {
    return $this->data;
  }

}
