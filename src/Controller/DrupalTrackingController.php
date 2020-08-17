<?php

namespace Drupal\drupal_tracking\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\drupal_tracking\Event\DrupalTrackingEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DrupalTrackingController.
 */
class DrupalTrackingController extends ControllerBase {

  /**
   * Drupal\drupal_tracking\Service\DrupalTrackingService definition.
   *
   * @var \Drupal\drupal_tracking\Service\DrupalTrackingService
   */
  protected $drupalTrackingService;

  /**
   * The event dispatcher.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->drupalTrackingService = $container->get('drupal_tracking.service');
    $instance->eventDispatcher = $container->get('event_dispatcher');
    return $instance;
  }

  /**
   * Do Tracking actions server side.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   Request http object.
   *
   * @return \Drupal\Component\Serialization\JsonResponse
   *   Return formatted data to send to partner from js.
   */
  public function doTracking(Request $request) {
    // Get post data from client ajax.
    $data = Json::decode($request->getContent());
    // Make sure having array.
    $tracking_data = !is_null($data) ? (array) $data : [];
    // Extract tracking category.
    $tracking_category = $tracking_data['category'] ?? '';
    $tracking_category = isset($tracking_data['type']) ? "$tracking_category::${$tracking_data['type']}" : $tracking_category;
    unset($tracking_data['category']);
    // Dispatch Drupal tracking event to store data.
    $drupalTrackingEvent = new DrupalTrackingEvent($tracking_category, $tracking_data);
    $this->eventDispatcher->dispatch(DrupalTrackingEvent::PORTAL_SEARCH_EVENT, $drupalTrackingEvent);
    // Return formatted data to send to partner from js.
    return new JsonResponse([
      'action' => 'search',
      'category' => $tracking_category,
      'data' => $tracking_data,
    ]);
  }

}
