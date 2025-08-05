/**
 * @file
 * JavaScript for Kuula Tour component.
 */

(function (Drupal, drupalSettings, once) {
  'use strict';

  /**
   * Kuula Tour behavior.
   */
  Drupal.behaviors.kuulaTour = {
    attach: function (context, settings) {
      const tours = once('kuula-tour', '.kuula-tour-block', context);
      
      tours.forEach(function (tour) {
        initKuulaTour(tour);
      });
    }
  };

  /**
   * Initialize a Kuula tour.
   */
  function initKuulaTour(tourElement) {
    const iframe = tourElement.querySelector('.kuula-tour-block__iframe');
    const loading = tourElement.querySelector('.kuula-tour-block__loading');
    const fullscreenBtn = tourElement.querySelector('.kuula-tour-block__fullscreen-toggle');
    
    // Handle iframe loading
    if (iframe && loading) {
      iframe.addEventListener('load', function() {
        setTimeout(function() {
          loading.classList.add('hidden');
        }, 1000); // Give it a moment to fully load
      });
    }
    
    // Handle fullscreen functionality
    if (fullscreenBtn) {
      fullscreenBtn.addEventListener('click', function() {
        toggleFullscreen(tourElement);
      });
    }
    
    // Handle escape key for fullscreen
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && tourElement.classList.contains('fullscreen')) {
        exitFullscreen(tourElement);
      }
    });
    
    // Handle window resize in fullscreen
    window.addEventListener('resize', function() {
      if (tourElement.classList.contains('fullscreen')) {
        adjustFullscreenSize(tourElement);
      }
    });
  }

  /**
   * Toggle fullscreen mode.
   */
  function toggleFullscreen(tourElement) {
    if (tourElement.classList.contains('fullscreen')) {
      exitFullscreen(tourElement);
    } else {
      enterFullscreen(tourElement);
    }
  }

  /**
   * Enter fullscreen mode.
   */
  function enterFullscreen(tourElement) {
    tourElement.classList.add('fullscreen');
    
    // Update button icon
    const icon = tourElement.querySelector('.kuula-tour-block__fullscreen-toggle i');
    if (icon) {
      icon.className = 'fas fa-compress';
    }
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
    
    // Try to use browser fullscreen API if available
    if (tourElement.requestFullscreen) {
      tourElement.requestFullscreen().catch(function(err) {
        console.log('Fullscreen request failed:', err);
      });
    } else if (tourElement.webkitRequestFullscreen) {
      tourElement.webkitRequestFullscreen();
    } else if (tourElement.mozRequestFullScreen) {
      tourElement.mozRequestFullScreen();
    } else if (tourElement.msRequestFullscreen) {
      tourElement.msRequestFullscreen();
    }
    
    adjustFullscreenSize(tourElement);
  }

  /**
   * Exit fullscreen mode.
   */
  function exitFullscreen(tourElement) {
    tourElement.classList.remove('fullscreen');
    
    // Update button icon
    const icon = tourElement.querySelector('.kuula-tour-block__fullscreen-toggle i');
    if (icon) {
      icon.className = 'fas fa-expand';
    }
    
    // Restore body scroll
    document.body.style.overflow = '';
    
    // Exit browser fullscreen if active
    if (document.exitFullscreen && document.fullscreenElement) {
      document.exitFullscreen();
    } else if (document.webkitExitFullscreen && document.webkitFullscreenElement) {
      document.webkitExitFullscreen();
    } else if (document.mozCancelFullScreen && document.mozFullScreenElement) {
      document.mozCancelFullScreen();
    } else if (document.msExitFullscreen && document.msFullscreenElement) {
      document.msExitFullscreen();
    }
  }

  /**
   * Adjust tour size in fullscreen.
   */
  function adjustFullscreenSize(tourElement) {
    const container = tourElement.querySelector('.kuula-tour-block__container');
    if (container && tourElement.classList.contains('fullscreen')) {
      container.style.width = '100vw';
      container.style.height = '100vh';
    }
  }

  /**
   * Handle browser fullscreen events.
   */
  document.addEventListener('fullscreenchange', handleFullscreenChange);
  document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
  document.addEventListener('mozfullscreenchange', handleFullscreenChange);
  document.addEventListener('MSFullscreenChange', handleFullscreenChange);

  function handleFullscreenChange() {
    const fullscreenElement = document.fullscreenElement || 
                             document.webkitFullscreenElement || 
                             document.mozFullScreenElement || 
                             document.msFullscreenElement;
    
    if (!fullscreenElement) {
      // Browser exited fullscreen, update our UI
      const tourElements = document.querySelectorAll('.kuula-tour-block.fullscreen');
      tourElements.forEach(function(tour) {
        exitFullscreen(tour);
      });
    }
  }

  /**
   * Auto-hide loading indicator after timeout.
   */
  function autoHideLoading() {
    const loadingElements = document.querySelectorAll('.kuula-tour-block__loading:not(.hidden)');
    loadingElements.forEach(function(loading) {
      setTimeout(function() {
        if (!loading.classList.contains('hidden')) {
          loading.classList.add('hidden');
        }
      }, 5000); // Hide after 5 seconds if iframe hasn't loaded
    });
  }

  // Auto-hide loading indicators on page load
  setTimeout(autoHideLoading, 100);

})(Drupal, drupalSettings, once);
