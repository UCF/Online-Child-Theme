/* global dataLayer */

//
// Ported from Online-Theme
//

(function ($) {


  const $iframe = $('.embed-responsive iframe');

  // Check for iframe
  if (!$iframe.length || $iframe.attr('src').search(/vimeo.com/) < 1) {
    return;
  }

  const url = $iframe.attr('src').split('?')[0];
  let percentComplete = 0,
    videoCompleted = false,
    videoPaused = false,
    videoPlayed = false,
    videoResumed = false;

  // Listen for messages from the player
  if (window.addEventListener) {
    window.addEventListener('message', onMessageReceived, false);
  } else {
    window.attachEvent('onmessage', onMessageReceived, false);
  }

  // Send event to GA
  function sendEvent(action, nonInteractive) {
    // eslint-disable-next-line no-unused-vars
    nonInteractive = typeof nonInteractive !== 'undefined' ? nonInteractive : 1;
    dataLayer.push({
      event: 'video',
      videoAction: action,
      videoUrl: url
    });
  }

  // Handle messages received from the player
  function onMessageReceived(e) {
    if (!(/^https?:\/\/player.vimeo.com/).test(e.origin)) {
      return;
    }

    const data = JSON.parse(e.data);

    // eslint-disable-next-line default-case
    switch (data.event) {
      case 'ready':
        onReady();
        break;

      case 'playProgress':
        onPlayProgress(data.data);
        break;

      case 'play':
        onPlay();
        break;

      case 'pause':
        onPause();
        break;

      case 'finish':
        onFinish();
        break;
    }
  }

  // Helper function for sending a message to the player
  function post(action, value) {
    const data = {
      method: action
    };

    if (value) {
      data.value = value;
    }

    $iframe[0].contentWindow.postMessage(JSON.stringify(data), url);
  }

  function onReady() {
    post('addEventListener', 'play');
    post('addEventListener', 'pause');
    post('addEventListener', 'finish');
    post('addEventListener', 'playProgress');
  }

  function onPause() {
    if (!videoPaused) {
      sendEvent('Paused video');
      videoPaused = true; // Avoid subsequent pause trackings
    }
  }

  // Tracking video progress
  function onPlayProgress(data) {
    percentComplete = Math.round(data.percent * 100 / 20) * 20;
  }

  function onPlay() {
    if (!videoPlayed) {
      sendEvent('Started video');
      videoPlayed = true; // Avoid subsequent play trackings
    } else if (!videoResumed && videoPaused) {
      sendEvent('Resumed video');
      videoResumed = true; // Avoid subsequent resume trackings
    }
  }

  function onFinish() {
    if (!videoCompleted) {
      sendEvent('Completed video');
      videoCompleted = true; // Avoid subsequent finish trackings
    }
  }

  // eslint-disable-next-line no-unused-vars
  function onPageLeave(data) {
    if (!videoCompleted && videoPlayed) {
      if (percentComplete < 100) {
        sendEvent(`Abandoned video: ${percentComplete}-${percentComplete + 19}%`, 0);
      }
    }
  }

  $(window).on('beforeunload', onPageLeave);

}(jQuery));
