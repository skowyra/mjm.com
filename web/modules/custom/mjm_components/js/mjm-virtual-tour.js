/**
 * @file
 * MJM Virtual Tour component JavaScript using Marzipano.
 */

(function ($, Drupal, drupalSettings, once) {
  'use strict';

  Drupal.behaviors.mjmVirtualTour = {
    attach: function (context, settings) {
      // Initialize all virtual tours using once() from the once library
      once('mjm-virtual-tour', '.mjm-virtual-tour', context).forEach(function (element) {
        const $container = $(element);
        const tourId = $container.attr('id');
        const viewerId = tourId + '-viewer';
        const tourSettings = settings.mjmVirtualTour && settings.mjmVirtualTour[tourId];
        
        console.log('Initializing virtual tour:', tourId);
        console.log('Tour settings:', tourSettings);
        
        if (!tourSettings || !tourSettings.scenes || !tourSettings.scenes.length) {
          console.warn('No scenes configured for virtual tour:', tourId);
          $container.find('.mjm-virtual-tour__loading').html('<p>Error: No scenes configured</p>');
          return;
        }

        // Check if Marzipano is loaded, if not wait for it
        if (typeof Marzipano === 'undefined') {
          console.log('Waiting for Marzipano library to load...');
          waitForMarzipano(function() {
            initializeVirtualTour($container, tourId, viewerId, tourSettings);
          });
          return;
        }

        // Initialize immediately if Marzipano is available
        initializeVirtualTour($container, tourId, viewerId, tourSettings);
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
   * Initialize a virtual tour.
   */
  function initializeVirtualTour($container, tourId, viewerId, tourSettings) {
    console.log('Starting virtual tour initialization for:', tourId);
    
    try {
      // Initialize Marzipano viewer
      const viewerElement = document.getElementById(viewerId);
      if (!viewerElement) {
        console.error('Viewer element not found:', viewerId);
        $container.find('.mjm-virtual-tour__loading').html('<p>Error: Viewer element not found</p>');
        return;
      }
      
      const viewer = new Marzipano.Viewer(viewerElement);
      const scenes = {};
      
      // Enable default controls for navigation
      viewer.controls().enabled(true);
      
      // Add console log to verify controls are working
      console.log('Viewer controls enabled:', viewer.controls().enabled());
      console.log('Available control methods:', Object.keys(viewer.controls()._methods || {}));
      
      console.log('Creating', tourSettings.scenes.length, 'scenes');

      // Create scenes
      tourSettings.scenes.forEach(function (sceneData, index) {
        console.log('Creating scene', index + 1, ':', sceneData);
        
        try {
          // Create geometry, view, and source
          const geometry = new Marzipano.EquirectGeometry([{ width: 4096 }]);
          const limiter = Marzipano.RectilinearView.limit.traditional(1024, 100 * Math.PI / 180);
          
          // Use default view if none provided
          const initialView = sceneData.initial_view || { yaw: 0, pitch: 0, fov: Math.PI / 3 };
          const view = new Marzipano.RectilinearView(initialView, limiter);
          
          console.log('Image URL:', sceneData.image_url);
          
          // Test if image loads
          const testImg = new Image();
          testImg.onload = function() {
            console.log('Image loaded successfully:', sceneData.image_url);
          };
          testImg.onerror = function() {
            console.error('Failed to load image:', sceneData.image_url);
          };
          testImg.src = sceneData.image_url;
          
          const source = Marzipano.ImageUrlSource.fromString(sceneData.image_url);
          
          // Create scene
          const scene = viewer.createScene({
            source: source,
            geometry: geometry,
            view: view,
            pinFirstLevel: true
          });

          // Store scene
          scenes[sceneData.id] = {
            scene: scene,
            data: sceneData,
            hotspots: []
          };

          console.log('Scene created successfully:', sceneData.id);

          // Create hotspots for this scene
          if (sceneData.hotspots) {
            sceneData.hotspots.forEach(function (hotspotData) {
              createHotspot(scene, hotspotData, scenes, tourId);
            });
          }
        } catch (sceneError) {
          console.error('Error creating scene:', sceneError);
        }
      });

      // Switch to first scene
      if (Object.keys(scenes).length > 0) {
        const firstSceneId = Object.keys(scenes)[0];
        console.log('Switching to first scene:', firstSceneId);
        switchScene(firstSceneId, scenes, viewer, $container);
      } else {
        console.error('No scenes were created successfully');
        $container.find('.mjm-virtual-tour__loading').html('<p>Error: No scenes could be created</p>');
        return;
      }

      // Setup controls
      setupControls($container, scenes, viewer, tourSettings, viewerId, tourId);

      // Hide loading screen after a delay
      setTimeout(function () {
        console.log('Hiding loading screen');
        $container.find('.mjm-virtual-tour__loading').addClass('hidden');
      }, 2000);

      // Auto-rotate if enabled
      if (tourSettings.autoRotate) {
        enableAutoRotate(viewer, $container);
      }
      
    } catch (error) {
      console.error('Error initializing virtual tour:', error);
      $container.find('.mjm-virtual-tour__loading').html('<p>Error: ' + error.message + '</p>');
    }
  }

  /**
   * Create a hotspot element.
   */
  function createHotspot(scene, hotspotData, scenes, tourId) {
    const hotspotElement = document.createElement('div');
    hotspotElement.className = 'mjm-virtual-tour__hotspot';
    
    if (hotspotData.type === 'info') {
      hotspotElement.className += ' mjm-virtual-tour__hotspot--info';
      hotspotElement.innerHTML = '<i class="fas fa-info"></i>';
    } else {
      hotspotElement.innerHTML = '<i class="fas fa-arrow-right"></i>';
    }

    // Add tooltip
    if (hotspotData.text) {
      const tooltip = document.createElement('div');
      tooltip.className = 'mjm-virtual-tour__tooltip';
      tooltip.textContent = hotspotData.text;
      hotspotElement.appendChild(tooltip);
    }

    // Add click handler
    hotspotElement.addEventListener('click', function () {
      handleHotspotClick(hotspotData, scenes, tourId);
    });

    // Create hotspot
    const coords = { yaw: hotspotData.yaw * Math.PI / 180, pitch: hotspotData.pitch * Math.PI / 180 };
    scene.hotspotContainer().createHotspot(hotspotElement, coords);
  }

  /**
   * Handle hotspot click.
   */
  function handleHotspotClick(hotspotData, scenes, tourId) {
    if (hotspotData.type === 'scene' && scenes[hotspotData.target]) {
      switchScene(hotspotData.target, scenes, null, $('#' + tourId));
    } else if (hotspotData.type === 'info') {
      showInfoPanel(hotspotData, tourId);
    }
  }

  /**
   * Switch to a different scene.
   */
  function switchScene(sceneId, scenes, viewer, $container) {
    if (!scenes[sceneId]) {
      console.error('Scene not found:', sceneId);
      return;
    }

    console.log('Switching to scene:', sceneId);
    const sceneObj = scenes[sceneId];
    sceneObj.scene.switchTo();

    // Update active scene button
    $container.find('.mjm-virtual-tour__scene-btn').removeClass('active');
    $container.find('[data-scene="' + sceneId + '"]').addClass('active');
  }

  /**
   * Setup control buttons.
   */
  function setupControls($container, scenes, viewer, settings, viewerId, tourId) {
    // Scene buttons
    $container.find('.mjm-virtual-tour__scene-btn').on('click', function () {
      const sceneId = $(this).data('scene');
      switchScene(sceneId, scenes, viewer, $container);
    });

    // Zoom controls
    $container.find('.mjm-virtual-tour__btn--zoom-in').on('click', function () {
      const view = viewer.view();
      const currentFov = view.fov();
      view.setFov(Math.max(currentFov * 0.8, Math.PI / 8));
    });

    $container.find('.mjm-virtual-tour__btn--zoom-out').on('click', function () {
      const view = viewer.view();
      const currentFov = view.fov();
      view.setFov(Math.min(currentFov * 1.25, Math.PI * 0.75));
    });

    // Fullscreen control - opens in new tab
    $container.find('.mjm-virtual-tour__btn--fullscreen').on('click', function () {
      // Get the current tour configuration
      const tourData = {
        tourId: tourId,
        scenes: settings.scenes,
        autoRotate: settings.autoRotate || false,
        title: settings.title || 'Virtual Tour'
      };
      
      // Create a new window/tab with the virtual tour
      const newWindow = window.open('', '_blank', 'width=1200,height=800,scrollbars=no,resizable=yes');
      
      if (newWindow) {
        // Create the HTML content for the new tab
        const htmlContent = `
          <!DOCTYPE html>
          <html>
          <head>
            <title>${tourData.title}</title>
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
            </style>
          </head>
          <body>
            <div id="viewer"></div>
            <div class="controls">
              <button class="control-btn" id="zoom-in">Zoom In</button>
              <button class="control-btn" id="zoom-out">Zoom Out</button>
              <button class="control-btn" id="auto-rotate">Auto Rotate</button>
              <button class="control-btn" id="close-tab">Close</button>
            </div>
            
            <script src="https://unpkg.com/marzipano@0.10.2/dist/marzipano.js"></script>
            <script>
              const tourData = ${JSON.stringify(tourData)};
              let viewer, scenes = {}, autoRotateInterval;
              
              // Initialize viewer
              viewer = new Marzipano.Viewer(document.getElementById('viewer'));
              viewer.controls().enabled(true);
              
              // Create scenes
              tourData.scenes.forEach(function(sceneData) {
                const geometry = new Marzipano.EquirectGeometry([{ width: 4096 }]);
                const limiter = Marzipano.RectilinearView.limit.traditional(1024, 100 * Math.PI / 180);
                const view = new Marzipano.RectilinearView(
                  sceneData.initial_view || { yaw: 0, pitch: 0, fov: Math.PI / 3 }, 
                  limiter
                );
                const source = Marzipano.ImageUrlSource.fromString(sceneData.image_url);
                
                const scene = viewer.createScene({
                  source: source,
                  geometry: geometry,
                  view: view,
                  pinFirstLevel: true
                });
                
                scenes[sceneData.id] = scene;
              });
              
              // Switch to first scene
              const firstSceneId = Object.keys(scenes)[0];
              if (firstSceneId) {
                scenes[firstSceneId].switchTo();
              }
              
              // Controls
              document.getElementById('zoom-in').onclick = function() {
                const view = viewer.view();
                const currentFov = view.fov();
                view.setFov(Math.max(currentFov * 0.8, Math.PI / 8));
              };
              
              document.getElementById('zoom-out').onclick = function() {
                const view = viewer.view();
                const currentFov = view.fov();
                view.setFov(Math.min(currentFov * 1.25, Math.PI * 0.75));
              };
              
              document.getElementById('auto-rotate').onclick = function() {
                const btn = this;
                if (autoRotateInterval) {
                  clearInterval(autoRotateInterval);
                  autoRotateInterval = null;
                  btn.classList.remove('active');
                } else {
                  autoRotateInterval = setInterval(function() {
                    const view = viewer.view();
                    const currentYaw = view.yaw();
                    view.setParameters({
                      yaw: currentYaw + 0.01,
                      pitch: view.pitch(),
                      fov: view.fov()
                    });
                  }, 50);
                  btn.classList.add('active');
                }
              };
              
              document.getElementById('close-tab').onclick = function() {
                window.close();
              };
              
              // Start auto-rotate if enabled
              if (tourData.autoRotate) {
                document.getElementById('auto-rotate').click();
              }
            </script>
          </body>
          </html>
        `;
        
        newWindow.document.write(htmlContent);
        newWindow.document.close();
        newWindow.focus();
      } else {
        alert('Please allow pop-ups for this site to open the virtual tour in a new tab.');
      }
    });

    // Auto-rotate control
    $container.find('.mjm-virtual-tour__btn--auto-rotate').on('click', function () {
      const $btn = $(this);
      if ($btn.hasClass('active')) {
        disableAutoRotate(viewer, $container);
        $btn.removeClass('active');
      } else {
        enableAutoRotate(viewer, $container);
        $btn.addClass('active');
      }
    });
  }

  /**
   * Enable auto-rotation.
   */
  function enableAutoRotate(viewer, $container) {
    if (!viewer._autoRotateInterval) {
      viewer._autoRotateInterval = setInterval(function() {
        const view = viewer.view();
        const currentYaw = view.yaw();
        const currentPitch = view.pitch();
        const currentFov = view.fov();
        
        // Rotate slowly around the yaw axis
        view.setParameters({
          yaw: currentYaw + 0.01,
          pitch: currentPitch,
          fov: currentFov
        });
      }, 50); // Update every 50ms for smooth rotation
    }
    $container.find('.mjm-virtual-tour__btn--auto-rotate').addClass('active');
  }

  /**
   * Disable auto-rotation.
   */
  function disableAutoRotate(viewer, $container) {
    if (viewer._autoRotateInterval) {
      clearInterval(viewer._autoRotateInterval);
      viewer._autoRotateInterval = null;
    }
    $container.find('.mjm-virtual-tour__btn--auto-rotate').removeClass('active');
  }

  /**
   * Show info panel.
   */
  function showInfoPanel(hotspotData, tourId) {
    const $container = $('#' + tourId);
    let $panel = $container.find('.mjm-virtual-tour__info-panel');
    
    if ($panel.length === 0) {
      $panel = $('<div class="mjm-virtual-tour__info-panel"></div>');
      $container.find('.mjm-virtual-tour__container').append($panel);
    }

    // Create close button positioned away from admin header
    const closeButtonHtml = '<button class="close-btn" style="position: absolute; top: 10rem; right: 5rem; background: rgba(255, 255, 255, 0.9); border: 2px solid rgba(255, 255, 255, 0.3); border-radius: 50%; width: 50px; height: 50px; cursor: pointer; font-size: 20px; color: #333; font-weight: bold; z-index: 100000; display: flex; align-items: center; justify-content: center; font-family: Arial, sans-serif; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3); transition: all 0.3s ease;">×</button>';
    
    $panel.html(
      closeButtonHtml +
      '<h4>' + (hotspotData.text || 'Information') + '</h4>' +
      '<p>' + (hotspotData.content || 'No additional information available.') + '</p>'
    );

    $panel.addClass('visible');
    
    console.log('Info panel created with close button');
    console.log('Close button exists:', $panel.find('.close-btn').length > 0);

    // Close button handler
    $panel.find('.close-btn').on('click', function (e) {
      console.log('Close button clicked');
      e.preventDefault();
      e.stopPropagation();
      $panel.removeClass('visible');
    });

    // Auto-hide after 8 seconds (increased time)
    setTimeout(function () {
      $panel.removeClass('visible');
    }, 8000);
  }

})(jQuery, Drupal, drupalSettings, once);
