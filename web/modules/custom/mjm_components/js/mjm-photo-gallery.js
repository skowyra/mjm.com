/**
 * @file
 * MJM Photo Gallery component JavaScript.
 */

(function ($, Drupal, drupalSettings, once) {
  'use strict';

  Drupal.behaviors.mjmPhotoGallery = {
    attach: function (context, settings) {
      // Initialize all photo galleries using once() from the once library
      once('mjm-photo-gallery', '.mjm-photo-gallery', context).forEach(function (element) {
        const $container = $(element);
        const galleryId = $container.attr('id');
        const gallerySettings = settings.mjmPhotoGallery && settings.mjmPhotoGallery[galleryId];
        
        console.log('Initializing photo gallery:', galleryId);
        console.log('Gallery settings:', gallerySettings);
        
        if (!gallerySettings || !gallerySettings.images || !gallerySettings.images.length) {
          console.warn('No photos configured for gallery:', galleryId);
          return;
        }

        initializePhotoGallery($container, gallerySettings);
      });
    }
  };

  /**
   * Initialize a photo gallery.
   */
  function initializePhotoGallery($container, settings) {
    const $items = $container.find('.mjm-photo-gallery__item');
    const layout = settings.layout || 'grid';
    let currentIndex = 0;
    let autoPlayInterval = null;

    console.log('Initializing photo gallery with layout:', layout);

    // Setup based on layout
    if (layout === 'carousel') {
      setupCarousel($container, settings, currentIndex, autoPlayInterval);
    } else if (layout === 'masonry') {
      setupMasonry($container, settings);
    }

    // Setup lightbox if enabled
    if (settings.enableLightbox) {
      setupLightbox($container, settings, currentIndex, autoPlayInterval);
    }

    // Setup click handlers for photo items
    $items.on('click', '.mjm-photo-gallery__view-btn, .mjm-photo-gallery__image', function (e) {
      e.preventDefault();
      if (settings.enableLightbox) {
        const index = parseInt($(this).closest('.mjm-photo-gallery__item').data('index'));
        openLightbox($container, settings, index);
      }
    });
  }

  /**
   * Setup carousel functionality.
   */
  function setupCarousel($container, settings, currentIndex, autoPlayInterval) {
    const $items = $container.find('.mjm-photo-gallery__item');
    const totalItems = $items.length;

    // Show first item
    showCarouselItem($container, 0);

    // Navigation buttons
    $container.find('.mjm-photo-gallery__carousel-btn--prev').on('click', function () {
      currentIndex = (currentIndex - 1 + totalItems) % totalItems;
      showCarouselItem($container, currentIndex);
      updateCarouselIndicators($container, currentIndex);
    });

    $container.find('.mjm-photo-gallery__carousel-btn--next').on('click', function () {
      currentIndex = (currentIndex + 1) % totalItems;
      showCarouselItem($container, currentIndex);
      updateCarouselIndicators($container, currentIndex);
    });

    // Indicator buttons
    $container.find('.mjm-photo-gallery__indicator').on('click', function () {
      currentIndex = parseInt($(this).data('index'));
      showCarouselItem($container, currentIndex);
      updateCarouselIndicators($container, currentIndex);
    });

    // Auto-play toggle
    if (settings.autoPlay) {
      $container.find('.mjm-photo-gallery__play-btn').on('click', function () {
        const $btn = $(this);
        if ($btn.hasClass('active')) {
          stopCarouselAutoPlay($container, autoPlayInterval);
          $btn.removeClass('active').find('i').removeClass('fa-pause').addClass('fa-play');
        } else {
          autoPlayInterval = startCarouselAutoPlay($container, settings, currentIndex, totalItems);
          $btn.addClass('active').find('i').removeClass('fa-play').addClass('fa-pause');
        }
      });

      // Start auto-play by default
      if (settings.autoPlay) {
        autoPlayInterval = startCarouselAutoPlay($container, settings, currentIndex, totalItems);
        $container.find('.mjm-photo-gallery__play-btn').addClass('active').find('i').removeClass('fa-play').addClass('fa-pause');
      }
    }

    // Keyboard navigation
    $(document).on('keydown', function(e) {
      if ($container.is(':visible')) {
        if (e.key === 'ArrowLeft') {
          $container.find('.mjm-photo-gallery__carousel-btn--prev').click();
        } else if (e.key === 'ArrowRight') {
          $container.find('.mjm-photo-gallery__carousel-btn--next').click();
        }
      }
    });
  }

  /**
   * Show specific carousel item.
   */
  function showCarouselItem($container, index) {
    const $items = $container.find('.mjm-photo-gallery__item');
    $items.css('transform', `translateX(-${index * 100}%)`);
  }

  /**
   * Update carousel indicators.
   */
  function updateCarouselIndicators($container, index) {
    $container.find('.mjm-photo-gallery__indicator')
      .removeClass('active')
      .eq(index)
      .addClass('active');
  }

  /**
   * Start carousel auto-play.
   */
  function startCarouselAutoPlay($container, settings, currentIndex, totalItems) {
    return setInterval(function() {
      currentIndex = (currentIndex + 1) % totalItems;
      showCarouselItem($container, currentIndex);
      updateCarouselIndicators($container, currentIndex);
    }, settings.autoPlaySpeed);
  }

  /**
   * Stop carousel auto-play.
   */
  function stopCarouselAutoPlay($container, autoPlayInterval) {
    if (autoPlayInterval) {
      clearInterval(autoPlayInterval);
      autoPlayInterval = null;
    }
  }

  /**
   * Setup masonry layout.
   */
  function setupMasonry($container, settings) {
    // Masonry is handled by CSS columns, but we can add image load events
    const $images = $container.find('.mjm-photo-gallery__image');
    let loadedImages = 0;

    $images.on('load', function() {
      loadedImages++;
      if (loadedImages === $images.length) {
        console.log('All masonry images loaded');
        // Trigger a reflow to ensure proper masonry layout
        $container.find('.mjm-photo-gallery__container').css('column-count', settings.columns);
      }
    });
  }

  /**
   * Setup lightbox functionality.
   */
  function setupLightbox($container, settings, currentIndex, autoPlayInterval) {
    const $lightbox = $container.find('.mjm-photo-gallery__lightbox');
    
    // Close lightbox - multiple methods
    $lightbox.find('.mjm-photo-gallery__lightbox-close').on('click', function (e) {
      e.stopPropagation();
      closeLightbox($container);
    });
    
    // Close when clicking on overlay background
    $lightbox.find('.mjm-photo-gallery__lightbox-overlay').on('click', function () {
      closeLightbox($container);
    });
    
    // Close when clicking outside the image
    $lightbox.on('click', function (e) {
      if (e.target === this) {
        closeLightbox($container);
      }
    });

    // Navigation
    $lightbox.find('.mjm-photo-gallery__lightbox-prev').on('click', function (e) {
      e.stopPropagation();
      navigateLightbox($container, settings, -1);
    });

    $lightbox.find('.mjm-photo-gallery__lightbox-next').on('click', function (e) {
      e.stopPropagation();
      navigateLightbox($container, settings, 1);
    });

    // Thumbnail navigation
    $lightbox.find('.mjm-photo-gallery__lightbox-thumb').on('click', function (e) {
      e.stopPropagation();
      const index = parseInt($(this).data('index'));
      showLightboxImage($container, settings, index);
    });

    // Auto-play in lightbox
    if (settings.autoPlay) {
      $lightbox.find('.mjm-photo-gallery__lightbox-play').on('click', function (e) {
        e.stopPropagation();
        const $btn = $(this);
        if ($btn.hasClass('active')) {
          stopLightboxAutoPlay($container, autoPlayInterval);
          $btn.removeClass('active').find('i').removeClass('fa-pause').addClass('fa-play');
        } else {
          autoPlayInterval = startLightboxAutoPlay($container, settings);
          $btn.addClass('active').find('i').removeClass('fa-play').addClass('fa-pause');
        }
      });
    }

    // Enhanced keyboard navigation - using document-level event listener
    const keydownHandler = function(e) {
      if ($lightbox.hasClass('active')) {
        switch(e.key) {
          case 'Escape':
            e.preventDefault();
            closeLightbox($container);
            break;
          case 'ArrowLeft':
            e.preventDefault();
            navigateLightbox($container, settings, -1);
            break;
          case 'ArrowRight':
            e.preventDefault();
            navigateLightbox($container, settings, 1);
            break;
          case ' ':
          case 'Spacebar':
            e.preventDefault();
            if (settings.autoPlay) {
              $lightbox.find('.mjm-photo-gallery__lightbox-play').click();
            }
            break;
          case 'Home':
            e.preventDefault();
            $lightbox.data('current-index', 0);
            showLightboxImage($container, settings, 0);
            break;
          case 'End':
            e.preventDefault();
            const lastIndex = settings.images.length - 1;
            $lightbox.data('current-index', lastIndex);
            showLightboxImage($container, settings, lastIndex);
            break;
        }
      }
    };
    
    // Store the handler so we can remove it later
    $lightbox.data('keydown-handler', keydownHandler);
    $(document).on('keydown.photo-gallery-' + $container.attr('id'), keydownHandler);
  }

  /**
   * Open lightbox with specific image.
   */
  function openLightbox($container, settings, index) {
    const $lightbox = $container.find('.mjm-photo-gallery__lightbox');
    
    // Store current index in data attribute
    $lightbox.data('current-index', index);
    
    // Show lightbox
    $lightbox.addClass('active');
    
    // Show the image
    showLightboxImage($container, settings, index);
    
    // Prevent body scroll
    $('body').css('overflow', 'hidden');
  }

  /**
   * Close lightbox.
   */
  function closeLightbox($container) {
    const $lightbox = $container.find('.mjm-photo-gallery__lightbox');
    $lightbox.removeClass('active');
    
    // Remove keyboard event listener
    $(document).off('keydown.photo-gallery-' + $container.attr('id'));
    
    // Stop auto-play if running
    const autoPlayInterval = $lightbox.data('autoplay-interval');
    if (autoPlayInterval) {
      clearInterval(autoPlayInterval);
      $lightbox.removeData('autoplay-interval');
    }
    
    // Restore body scroll
    $('body').css('overflow', '');
  }

  /**
   * Navigate lightbox (direction: -1 for prev, 1 for next).
   */
  function navigateLightbox($container, settings, direction) {
    const $lightbox = $container.find('.mjm-photo-gallery__lightbox');
    const currentIndex = $lightbox.data('current-index') || 0;
    const totalImages = settings.images.length;
    const newIndex = (currentIndex + direction + totalImages) % totalImages;
    
    $lightbox.data('current-index', newIndex);
    showLightboxImage($container, settings, newIndex);
  }

  /**
   * Show specific image in lightbox.
   */
  function showLightboxImage($container, settings, index) {
    const $lightbox = $container.find('.mjm-photo-gallery__lightbox');
    const image = settings.images[index];
    
    // Update image
    const $img = $lightbox.find('.mjm-photo-gallery__lightbox-image');
    $img.attr('src', image.url).attr('alt', image.title || '');
    
    // Update info
    $lightbox.find('.mjm-photo-gallery__lightbox-title').text(image.title || '');
    $lightbox.find('.mjm-photo-gallery__lightbox-description').text(image.description || '');
    
    // Update meta information
    let metaHTML = '';
    if (image.photographer) metaHTML += `<span>📸 ${image.photographer}</span>`;
    if (image.date_taken) metaHTML += `<span>📅 ${image.date_taken}</span>`;
    if (image.location) metaHTML += `<span>📍 ${image.location}</span>`;
    $lightbox.find('.mjm-photo-gallery__lightbox-meta').html(metaHTML);
    
    // Update counter
    $lightbox.find('.mjm-photo-gallery__lightbox-current').text(index + 1);
    $lightbox.find('.mjm-photo-gallery__lightbox-total').text(settings.images.length);
    
    // Update active thumbnail
    $lightbox.find('.mjm-photo-gallery__lightbox-thumb')
      .removeClass('active')
      .eq(index)
      .addClass('active');
  }

  /**
   * Start lightbox auto-play.
   */
  function startLightboxAutoPlay($container, settings) {
    const $lightbox = $container.find('.mjm-photo-gallery__lightbox');
    
    const interval = setInterval(function() {
      navigateLightbox($container, settings, 1);
    }, settings.autoPlaySpeed);
    
    $lightbox.data('autoplay-interval', interval);
    return interval;
  }

  /**
   * Stop lightbox auto-play.
   */
  function stopLightboxAutoPlay($container, autoPlayInterval) {
    const $lightbox = $container.find('.mjm-photo-gallery__lightbox');
    const interval = $lightbox.data('autoplay-interval');
    
    if (interval) {
      clearInterval(interval);
      $lightbox.removeData('autoplay-interval');
    }
  }

})(jQuery, Drupal, drupalSettings, once);
