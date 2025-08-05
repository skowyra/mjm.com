/**
 * @file
 * MJM Image Gallery component JavaScript using Marzipano.
 */

(function ($, Drupal, drupalSettings, once) {
  'use strict';

  Drupal.behaviors.mjmImageGallery = {
    attach: function (context, settings) {
      // Initialize all image galleries using once() from the once library
      once('mjm-image-gallery', '.mjm-image-gallery', context).forEach(function (element) {
        const $container = $(element);
        const galleryId = $container.attr('id');
        const viewerId = galleryId + '-viewer';
        const gallerySettings = settings.mjmImageGallery && settings.mjmImageGallery[galleryId];
        
        if (!gallerySettings || !gallerySettings.images || !gallerySettings.images.length) {
          console.warn('No images configured for gallery:', galleryId);
          $container.find('.mjm-image-gallery__loading').html('<p>Error: No images configured</p>');
          return;
        }

        // Check if Marzipano is loaded, if not wait for it
        if (typeof Marzipano === 'undefined') {
          console.log('Waiting for Marzipano library to load...');
          waitForMarzipano(function() {
            initializeImageGallery($container, galleryId, viewerId, gallerySettings);
          });
          return;
        }

        // Initialize immediately if Marzipano is available
        initializeImageGallery($container, galleryId, viewerId, gallerySettings);
      });
    }
  };

  /**
   * Wait for Marzipano library to load.
   */
  function waitForMarzipano(callback) {
    var attempts = 0;
    var maxAttempts = 50; // 5 seconds max wait time
    
    function checkMarzipano() {
      attempts++;
      if (typeof Marzipano !== 'undefined') {
        console.log('Marzipano loaded successfully');
        callback();
      } else if (attempts < maxAttempts) {
        setTimeout(checkMarzipano, 100);
      } else {
        console.error('Marzipano library failed to load after 5 seconds');
      }
    }
    
    checkMarzipano();
  }

  /**
   * Initialize an image gallery.
   */
  function initializeImageGallery($container, galleryId, viewerId, gallerySettings) {
    console.log('Starting image gallery initialization for:', galleryId);
    
    try {
      // Initialize Marzipano viewer
      const viewerElement = document.getElementById(viewerId);
      if (!viewerElement) {
        console.error('Viewer element not found:', viewerId);
        $container.find('.mjm-image-gallery__loading').html('<p>Error: Viewer element not found</p>');
        return;
      }
      
      const viewer = new Marzipano.Viewer(viewerElement);
      const scenes = {};
      let currentImageIndex = 0;
      let autoPlayInterval = null;
      
      // Enable default controls for navigation
      viewer.controls().enabled(true);
      
      console.log('Creating', gallerySettings.images.length, 'image scenes');

      // Create scenes for each image
      gallerySettings.images.forEach(function (imageData, index) {
        console.log('Creating image scene', index + 1, ':', imageData);
        
        try {
          // For regular images displayed as flat panoramas, use equirectangular geometry
          const geometry = new Marzipano.EquirectGeometry([{ width: 2048 }]);
          const limiter = Marzipano.RectilinearView.limit.traditional(1024, 120 * Math.PI / 180);
          const view = new Marzipano.RectilinearView({ yaw: 0, pitch: 0, fov: Math.PI / 2 }, limiter);
          
          console.log('Image URL:', imageData.url);
          
          // Test if image loads
          const testImg = new Image();
          testImg.onload = function() {
            console.log('Image loaded successfully:', imageData.url);
          };
          testImg.onerror = function() {
            console.error('Failed to load image:', imageData.url);
          };
          testImg.src = imageData.url;
          
          const source = Marzipano.ImageUrlSource.fromString(imageData.url);
          
          // Create scene
          const scene = viewer.createScene({
            source: source,
            geometry: geometry,
            view: view,
            pinFirstLevel: true
          });

          // Store scene
          scenes[index] = {
            scene: scene,
            data: imageData,
            index: index
          };

          console.log('Image scene created successfully:', index);
        } catch (sceneError) {
          console.error('Error creating image scene:', sceneError);
        }
      });

      // Switch to first image
      if (Object.keys(scenes).length > 0) {
        console.log('Switching to first image');
        switchToImage(0, scenes, viewer, $container, gallerySettings);
      } else {
        console.error('No image scenes were created successfully');
        $container.find('.mjm-image-gallery__loading').html('<p>Error: No image scenes could be created</p>');
        return;
      }

      // Setup controls
      setupGalleryControls($container, scenes, viewer, gallerySettings, viewerId, galleryId, currentImageIndex, autoPlayInterval);

      // Hide loading screen after a delay
      setTimeout(function () {
        console.log('Hiding loading screen');
        $container.find('.mjm-image-gallery__loading').addClass('hidden');
      }, 2000);

      // Auto-play if enabled
      if (gallerySettings.autoPlay) {
        startAutoPlay($container, scenes, viewer, gallerySettings, currentImageIndex, autoPlayInterval);
      }
      
    } catch (error) {
      console.error('Error initializing image gallery:', error);
      $container.find('.mjm-image-gallery__loading').html('<p>Error: ' + error.message + '</p>');
    }
  }

  /**
   * Switch to a specific image.
   */
  function switchToImage(imageIndex, scenes, viewer, $container, gallerySettings) {
    if (!scenes[imageIndex]) {
      console.error('Image not found:', imageIndex);
      return;
    }

    console.log('Switching to image:', imageIndex);
    const sceneObj = scenes[imageIndex];
    sceneObj.scene.switchTo();

    // Update counter
    $container.find('.mjm-image-gallery__current').text(imageIndex + 1);

    // Update active thumbnail
    $container.find('.mjm-image-gallery__thumbnail').removeClass('active');
    $container.find('.mjm-image-gallery__thumbnail').eq(imageIndex).addClass('active');

    // Update image info if available
    const imageData = sceneObj.data;
    if (imageData.title || imageData.description) {
      updateImageInfo($container, imageData);
    }

    return imageIndex;
  }

  /**
   * Setup gallery control buttons.
   */
  function setupGalleryControls($container, scenes, viewer, settings, viewerId, galleryId, currentImageIndex, autoPlayInterval) {
    const totalImages = Object.keys(scenes).length;
    
    // Navigation controls
    $container.find('.mjm-image-gallery__btn--prev').on('click', function () {
      currentImageIndex = (currentImageIndex - 1 + totalImages) % totalImages;
      switchToImage(currentImageIndex, scenes, viewer, $container, settings);
    });

    $container.find('.mjm-image-gallery__btn--next').on('click', function () {
      currentImageIndex = (currentImageIndex + 1) % totalImages;
      switchToImage(currentImageIndex, scenes, viewer, $container, settings);
    });

    // Thumbnail navigation
    $container.find('.mjm-image-gallery__thumbnail').on('click', function () {
      const imageIndex = parseInt($(this).data('image'));
      currentImageIndex = imageIndex;
      switchToImage(currentImageIndex, scenes, viewer, $container, settings);
    });

    // Zoom controls
    if (settings.enableZoom) {
      $container.find('.mjm-image-gallery__btn--zoom-in').on('click', function () {
        const view = viewer.view();
        const currentFov = view.fov();
        view.setFov(Math.max(currentFov * 0.8, Math.PI / 8));
      });

      $container.find('.mjm-image-gallery__btn--zoom-out').on('click', function () {
        const view = viewer.view();
        const currentFov = view.fov();
        view.setFov(Math.min(currentFov * 1.25, Math.PI * 0.9));
      });

      $container.find('.mjm-image-gallery__btn--reset').on('click', function () {
        const view = viewer.view();
        view.setParameters({ yaw: 0, pitch: 0, fov: Math.PI / 2 });
      });
    }

    // Auto-play control
    if (settings.autoPlay) {
      $container.find('.mjm-image-gallery__btn--play').on('click', function () {
        const $btn = $(this);
        if ($btn.hasClass('active')) {
          stopAutoPlay($container, autoPlayInterval);
          $btn.removeClass('active').find('i').removeClass('fa-pause').addClass('fa-play');
        } else {
          autoPlayInterval = startAutoPlay($container, scenes, viewer, settings, currentImageIndex, autoPlayInterval);
          $btn.addClass('active').find('i').removeClass('fa-play').addClass('fa-pause');
        }
      });
    }

    // Fullscreen control - opens in new tab
    $container.find('.mjm-image-gallery__btn--fullscreen').on('click', function () {
      // Get the current gallery configuration
      const galleryData = {
        galleryId: galleryId,
        images: settings.images,
        autoPlay: settings.autoPlay || false,
        autoPlaySpeed: settings.autoPlaySpeed || 3000,
        title: settings.title || 'Image Gallery',
        currentIndex: currentImageIndex
      };
      
      // Create a new window/tab with the image gallery
      const newWindow = window.open('', '_blank', 'width=1200,height=800,scrollbars=no,resizable=yes');
      
      if (newWindow) {
        // Create the HTML content for the new tab
        const htmlContent = `
          <!DOCTYPE html>
          <html>
          <head>
            <title>${galleryData.title}</title>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="https://unpkg.com/marzipano@0.10.2/dist/marzipano.css">
            <style>
              body { 
                margin: 0; 
                padding: 0; 
                background: #000; 
                font-family: Arial, sans-serif;
                overflow: hidden;
              }
              #viewer { 
                width: 100vw; 
                height: 100vh; 
              }
              .controls {
                position: absolute;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 1000;
                display: flex;
                gap: 10px;
              }
              .control-btn {
                background: rgba(0,0,0,0.7);
                color: white;
                border: none;
                padding: 10px 15px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 14px;
              }
              .control-btn:hover {
                background: rgba(0,0,0,0.9);
              }
              .control-btn.active {
                background: rgba(255,255,255,0.2);
              }
              .counter {
                position: absolute;
                top: 20px;
                right: 20px;
                color: white;
                background: rgba(0,0,0,0.7);
                padding: 8px 12px;
                border-radius: 20px;
              }
            </style>
          </head>
          <body>
            <div id="viewer"></div>
            <div class="counter">
              <span id="current">1</span> / <span id="total">${galleryData.images.length}</span>
            </div>
            <div class="controls">
              <button class="control-btn" id="prev">Previous</button>
              <button class="control-btn" id="next">Next</button>
              <button class="control-btn" id="zoom-in">Zoom In</button>
              <button class="control-btn" id="zoom-out">Zoom Out</button>
              <button class="control-btn" id="reset-zoom">Reset</button>
              <button class="control-btn" id="auto-play">Auto Play</button>
              <button class="control-btn" id="close-tab">Close</button>
            </div>
            
            <script src="https://unpkg.com/marzipano@0.10.2/dist/marzipano.js"></script>
            <script>
              const galleryData = ${JSON.stringify(galleryData)};
              let viewer, scenes = {}, currentIndex = ${currentImageIndex}, autoPlayInterval;
              
              // Initialize viewer
              viewer = new Marzipano.Viewer(document.getElementById('viewer'));
              viewer.controls().enabled(true);
              
              // Create scenes for each image
              galleryData.images.forEach(function(imageData, index) {
                const geometry = new Marzipano.EquirectGeometry([{ width: 2048 }]);
                const limiter = Marzipano.RectilinearView.limit.traditional(1024, 120 * Math.PI / 180);
                const view = new Marzipano.RectilinearView({ yaw: 0, pitch: 0, fov: Math.PI / 2 }, limiter);
                const source = Marzipano.ImageUrlSource.fromString(imageData.url);
                
                const scene = viewer.createScene({
                  source: source,
                  geometry: geometry,
                  view: view,
                  pinFirstLevel: true
                });
                
                scenes[index] = scene;
              });
              
              // Switch to current image
              function switchToImage(index) {
                if (scenes[index]) {
                  scenes[index].switchTo();
                  document.getElementById('current').textContent = index + 1;
                  currentIndex = index;
                }
              }
              
              // Initialize with current image
              switchToImage(currentIndex);
              
              // Controls
              document.getElementById('prev').onclick = function() {
                currentIndex = (currentIndex - 1 + galleryData.images.length) % galleryData.images.length;
                switchToImage(currentIndex);
              };
              
              document.getElementById('next').onclick = function() {
                currentIndex = (currentIndex + 1) % galleryData.images.length;
                switchToImage(currentIndex);
              };
              
              document.getElementById('zoom-in').onclick = function() {
                const view = viewer.view();
                const currentFov = view.fov();
                view.setFov(Math.max(currentFov * 0.8, Math.PI / 8));
              };
              
              document.getElementById('zoom-out').onclick = function() {
                const view = viewer.view();
                const currentFov = view.fov();
                view.setFov(Math.min(currentFov * 1.25, Math.PI * 0.9));
              };
              
              document.getElementById('reset-zoom').onclick = function() {
                const view = viewer.view();
                view.setParameters({ yaw: 0, pitch: 0, fov: Math.PI / 2 });
              };
              
              document.getElementById('auto-play').onclick = function() {
                const btn = this;
                if (autoPlayInterval) {
                  clearInterval(autoPlayInterval);
                  autoPlayInterval = null;
                  btn.classList.remove('active');
                } else {
                  autoPlayInterval = setInterval(function() {
                    currentIndex = (currentIndex + 1) % galleryData.images.length;
                    switchToImage(currentIndex);
                  }, galleryData.autoPlaySpeed);
                  btn.classList.add('active');
                }
              };
              
              document.getElementById('close-tab').onclick = function() {
                window.close();
              };
              
              // Keyboard navigation
              document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowLeft') {
                  document.getElementById('prev').click();
                } else if (e.key === 'ArrowRight') {
                  document.getElementById('next').click();
                } else if (e.key === ' ') {
                  e.preventDefault();
                  document.getElementById('auto-play').click();
                }
              });
              
              // Start auto-play if enabled
              if (galleryData.autoPlay) {
                document.getElementById('auto-play').click();
              }
            </script>
          </body>
          </html>
        `;
        
        newWindow.document.write(htmlContent);
        newWindow.document.close();
        newWindow.focus();
      } else {
        alert('Please allow pop-ups for this site to open the gallery in a new tab.');
      }
    });

    // Keyboard navigation
    $(document).on('keydown', function(e) {
      if ($container.is(':visible')) {
        if (e.key === 'ArrowLeft') {
          $container.find('.mjm-image-gallery__btn--prev').click();
        } else if (e.key === 'ArrowRight') {
          $container.find('.mjm-image-gallery__btn--next').click();
        }
      }
    });
  }

  /**
   * Start auto-play slideshow.
   */
  function startAutoPlay($container, scenes, viewer, settings, currentImageIndex, autoPlayInterval) {
    const totalImages = Object.keys(scenes).length;
    
    if (autoPlayInterval) {
      clearInterval(autoPlayInterval);
    }
    
    autoPlayInterval = setInterval(function() {
      currentImageIndex = (currentImageIndex + 1) % totalImages;
      switchToImage(currentImageIndex, scenes, viewer, $container, settings);
    }, settings.autoPlaySpeed);
    
    $container.find('.mjm-image-gallery__btn--play').addClass('active');
    return autoPlayInterval;
  }

  /**
   * Stop auto-play slideshow.
   */
  function stopAutoPlay($container, autoPlayInterval) {
    if (autoPlayInterval) {
      clearInterval(autoPlayInterval);
      autoPlayInterval = null;
    }
    $container.find('.mjm-image-gallery__btn--play').removeClass('active');
  }

  /**
   * Update image info panel.
   */
  function updateImageInfo($container, imageData) {
    const $panel = $container.find('.mjm-image-gallery__info-panel');
    
    if (imageData.title || imageData.description) {
      $panel.find('.mjm-image-gallery__info-title').text(imageData.title || '');
      $panel.find('.mjm-image-gallery__info-description').text(imageData.description || '');
      
      // Show info panel briefly
      $panel.addClass('visible');
      
      // Auto-hide after 3 seconds
      setTimeout(function() {
        $panel.removeClass('visible');
      }, 3000);
    }
    
    // Close button handler
    $panel.find('.mjm-image-gallery__info-close').off('click').on('click', function () {
      $panel.removeClass('visible');
    });
  }

})(jQuery, Drupal, drupalSettings, once);
