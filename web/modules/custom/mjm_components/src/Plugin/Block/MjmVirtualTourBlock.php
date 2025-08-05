<?php

namespace Drupal\mjm_components\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

/**
 * Provides a 'MJM Virtual Tour' Block.
 *
 * @Block(
 *   id = "mjm_virtual_tour_block",
 *   admin_label = @Translation("MJM Virtual Tour"),
 *   category = @Translation("MJM Components"),
 * )
 */
class MjmVirtualTourBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();
    
    // Generate unique ID for this tour instance
    $tour_id = 'mjm-tour-' . uniqid();
    
    // Prepare scenes data
    $scenes = [];
    if (!empty($config['scenes'])) {
      $scenes_data = json_decode($config['scenes'], true);
      if ($scenes_data) {
        foreach ($scenes_data as $scene) {
          $image_url = '';
          
          // Handle uploaded file or external URL
          if (!empty($scene['image'])) {
            $file = File::load($scene['image']);
            if ($file) {
              $image_url = $file->createFileUrl();
            }
          } elseif (!empty($scene['image_url'])) {
            $image_url = $scene['image_url'];
          }
          
          if ($image_url) {
            $scenes[] = [
              'id' => $scene['id'] ?? uniqid(),
              'name' => $scene['name'] ?? 'Scene',
              'image_url' => $image_url,
              'initial_view' => [
                'yaw' => floatval($scene['yaw'] ?? 0),
                'pitch' => floatval($scene['pitch'] ?? 0),
                'fov' => floatval($scene['fov'] ?? 90),
              ],
              'hotspots' => $scene['hotspots'] ?? [],
            ];
          }
        }
      }
    }

    return [
      '#theme' => 'mjm_virtual_tour',
      '#tour_id' => $tour_id,
      '#title' => $config['title'] ?? '',
      '#description' => $config['description'] ?? '',
      '#scenes' => $scenes,
      '#width' => $config['width'] ?? '100%',
      '#height' => $config['height'] ?? '500px',
      '#auto_rotate' => $config['auto_rotate'] ?? false,
      '#show_controls' => $config['show_controls'] ?? true,
      '#attached' => [
        'library' => [
          'mjm_components/marzipano-tour',
        ],
        'drupalSettings' => [
          'mjmVirtualTour' => [
            $tour_id => [
              'scenes' => $scenes,
              'autoRotate' => $config['auto_rotate'] ?? false,
              'showControls' => $config['show_controls'] ?? true,
            ],
          ],
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Tour Title'),
      '#default_value' => $config['title'] ?? '',
      '#description' => $this->t('Optional title for the virtual tour'),
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Tour Description'),
      '#default_value' => $config['description'] ?? '',
      '#rows' => 3,
      '#description' => $this->t('Optional description for the virtual tour'),
    ];

    $form['dimensions'] = [
      '#type' => 'details',
      '#title' => $this->t('Dimensions & Settings'),
      '#open' => FALSE,
    ];

    $form['dimensions']['width'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Width'),
      '#default_value' => $config['width'] ?? '100%',
      '#description' => $this->t('Width in pixels or percentage (e.g., 800px or 100%)'),
    ];

    $form['dimensions']['height'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Height'),
      '#default_value' => $config['height'] ?? '500px',
      '#description' => $this->t('Height in pixels (e.g., 500px)'),
    ];

    $form['dimensions']['auto_rotate'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Auto Rotate'),
      '#default_value' => $config['auto_rotate'] ?? false,
      '#description' => $this->t('Automatically rotate the panorama'),
    ];

    $form['dimensions']['show_controls'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show Controls'),
      '#default_value' => $config['show_controls'] ?? true,
      '#description' => $this->t('Show zoom and fullscreen controls'),
    ];

    $form['scenes_config'] = [
      '#type' => 'details',
      '#title' => $this->t('360° Scenes Configuration'),
      '#open' => TRUE,
    ];

    $form['scenes_config']['scenes'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Scenes JSON Configuration'),
      '#default_value' => $config['scenes'] ?? $this->getDefaultScenesJson(),
      '#rows' => 15,
      '#description' => $this->t('Configure your 360° scenes in JSON format. See below for example structure.'),
    ];

    $form['scenes_config']['example'] = [
      '#type' => 'details',
      '#title' => $this->t('Example Configuration'),
      '#open' => FALSE,
    ];

    $form['scenes_config']['example']['example_json'] = [
      '#markup' => '<pre>' . htmlspecialchars($this->getExampleScenesJson()) . '</pre>',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    
    $values = $form_state->getValues();
    $this->configuration['title'] = $values['title'];
    $this->configuration['description'] = $values['description'];
    $this->configuration['width'] = $values['dimensions']['width'];
    $this->configuration['height'] = $values['dimensions']['height'];
    $this->configuration['auto_rotate'] = $values['dimensions']['auto_rotate'];
    $this->configuration['show_controls'] = $values['dimensions']['show_controls'];
    $this->configuration['scenes'] = $values['scenes_config']['scenes'];
  }

  /**
   * Get default scenes JSON.
   */
  private function getDefaultScenesJson() {
    return json_encode([
      [
        'id' => 'scene1',
        'name' => 'Main Room',
        'image_url' => 'https://your-cdn.com/360-image1.jpg',
        'yaw' => 0,
        'pitch' => 0,
        'fov' => 90,
        'hotspots' => [
          [
            'id' => 'hotspot1',
            'yaw' => 45,
            'pitch' => 0,
            'type' => 'scene',
            'target' => 'scene2',
            'text' => 'Go to Kitchen',
          ],
        ],
      ],
    ], JSON_PRETTY_PRINT);
  }

  /**
   * Get example scenes JSON.
   */
  private function getExampleScenesJson() {
    return json_encode([
      [
        'id' => 'living_room',
        'name' => 'Living Room',
        'image_url' => 'https://your-cdn.digitaloceanspaces.com/tours/living-room-360.jpg',
        'yaw' => 0,
        'pitch' => 0,
        'fov' => 90,
        'hotspots' => [
          [
            'id' => 'to_kitchen',
            'yaw' => 90,
            'pitch' => -10,
            'type' => 'scene',
            'target' => 'kitchen',
            'text' => 'Kitchen',
          ],
          [
            'id' => 'info_sofa',
            'yaw' => 180,
            'pitch' => -20,
            'type' => 'info',
            'text' => 'Comfortable seating area',
            'content' => 'This cozy living room features modern furniture and great natural lighting.',
          ],
        ],
      ],
      [
        'id' => 'kitchen',
        'name' => 'Kitchen',
        'image_url' => 'https://your-cdn.digitaloceanspaces.com/tours/kitchen-360.jpg',
        'yaw' => 0,
        'pitch' => 0,
        'fov' => 90,
        'hotspots' => [
          [
            'id' => 'back_to_living',
            'yaw' => 270,
            'pitch' => 0,
            'type' => 'scene',
            'target' => 'living_room',
            'text' => 'Back to Living Room',
          ],
        ],
      ],
    ], JSON_PRETTY_PRINT);
  }

}
