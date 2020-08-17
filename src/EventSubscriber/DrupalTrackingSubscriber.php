<?php

namespace Drupal\drupal_tracking\EventSubscriber;

use Drupal\Core\Path\PathValidatorInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Drupal\drupal_tracking\Event\DrupalTrackingEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Drupal\drupal_tracking\Service\DrupalTrackingService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class DrupalTrackingSubscriber.
 */
class DrupalTrackingSubscriber implements EventSubscriberInterface {

  /**
   * Drupal tracking service.
   *
   * @var \Drupal\drupal_tracking\Service\DrupalTrackingService
   */
  protected $drupalTrackingService;

  /**
   * The path validator.
   *
   * @var \Drupal\Core\Path\PathValidatorInterface
   */
  protected $pathValidator;

  /**
   * Constructs a new DrupalTrackingSubscriber object.
   *
   * @param \Drupal\drupal_tracking\Service\DrupalTrackingService $drupalTrackingService
   *   Drupal tracking service.
   * @param \Drupal\Core\Path\PathValidatorInterface $path_validator
   *   Path Validator interface.
   */
  public function __construct(DrupalTrackingService $drupalTrackingService, PathValidatorInterface $path_validator) {
    $this->drupalTrackingService = $drupalTrackingService;
    $this->pathValidator = $path_validator;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['navigationTracking'];
    $events[DrupalTrackingEvent::PORTAL_SEARCH_EVENT] = ['searchTracking'];

    return $events;
  }

  /**
   * This method is called when the Kernel event is dispatched.
   *
   * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
   *   The dispatched event.
   */
  public function navigationTracking(GetResponseEvent $event) {
    $uri = $event->getRequest()->getRequestUri();
    // Check if url is valid.
    $url_object = $this->pathValidator->getUrlIfValid($uri);
    // Get route name if exists.
    $route_name = $url_object ? $url_object->getRouteName() : NULL;
    // Skip some route name kernel event requested.
    if (!is_null($route_name) && !in_array($route_name, ['media.oembed_iframe', 'image.style_public'])) {
      // Store navigation.
      $this->drupalTrackingService->storeClientAction([
        'action' => 'navigation',
        'page' => $uri,
      ]);
    }
  }

  /**
   * This method is called when the search is dispatched.
   *
   * @param \Drupal\drupal_tracking\Event\DrupalTrackingEvent $event
   *   The dispatched event.
   */
  public function searchTracking(DrupalTrackingEvent $event) {
    // Store navigation.
    $this->drupalTrackingService->storeClientAction([
      'action' => 'search',
      'category' => $event->getCategory(),
      'data' => $event->getEventData(),
    ]);
  }

}
