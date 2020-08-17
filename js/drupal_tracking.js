/**
 * @file
 */

;(function ($, Drupal) {
  Drupal.behaviors.drupalTracking = {
    attach(context) {

      // Do tracking actions.
      function doDrupalTracking(trackingContext) {
        const trackingData = { category: trackingContext }

        // Collect data event.
        // Get items having the same context than the submit.
        $(document)
          .find(`[data-tracking-context*=${trackingContext}]`)
          .each(function () {
            const fieldLabel = $(this).data('tracking-field')
            $.extend(trackingData, { [fieldLabel]: $(this).val() })
          })

        // Call Drupal Tracking action.
        $.ajax({
          type: 'POST',
          url: '/drupal_tracking/do-tracking',
          data: JSON.stringify(trackingData),
          dataType: 'json',
          success(response) {
            // Send to analytics partner.
          },
        })
      }

      $(document).ready(function () {
        // Get tracking btn element and avoid listener conflict.
        const trackingSubmitElement = $(document)
          .find('[data-tracking-context-submit]', context)
          .once('drupalTracking')
        // Do tracking on submit btn mousedown.
        trackingSubmitElement.on('mousedown', function () {
          doDrupalTracking($(this).data('tracking-context-submit'))
        })
      })
    },
  }
})(jQuery, Drupal)
