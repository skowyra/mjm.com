<?php

namespace Drupal\mjm_components\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Url;
use Drupal\Component\Utility\Html;

/**
 * Provides an MJM Image Gallery block using Marzipano.
 *
 * @Block(
 *   id = "mjm_image_gallery",
 *   admin_label = @Translation("MJM Image Gallery"),
 *   category = @Translation("MJM Components")
 * )
 */
class MjmImageGalleryBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();
    $gallery_id = 'mjm-image-gallery-' . Html::getUniqueId('gallery');
    
    // Process images
    $images = [];
    if (!empty($config['images'])) {
      foreach ($config['images'] as $index => $image_data) {
        if (!empty($image_data['image_file']) || !empty($image_data['image_url'])) {
          $image_url = '';
          
          // Handle file upload
          if (!empty($image_data['image_file'])) {
            $file = File::load($image_data['image_file'][0]);
            if ($file) {
              $image_url = $file->createFileUrl();
            }
          }
          // Handle URL input (takes precedence)
          elseif (!empty($image_data['image_url'])) {
            $image_url = $image_data['image_url'];
          }
          
          if ($image_url) {
            $images[] = [
              'id' => 'image_' . $index,
              'url' => $image_url,
              'title' => $image_data['title'] ?? 'Image ' . ($index + 1),
              'description' => $image_data['description'] ?? '',
            ];
          }
        }
      }
    }

    if (empty($images)) {
      return [
        '#markup' => '<p>No images configured for gallery. Please configure images in the block settings.</p>',
      ];
    }

    // Prepare settings for JavaScript
    $gallery_settings = [
      'images' => $images,
      'autoPlay' => $config['auto_play'] ?? FALSE,
      'autoPlaySpeed' => (int) ($config['auto_play_speed'] ?? 3000),
      'showThumbnails' => $config['show_thumbnails'] ?? TRUE,
      'enableZoom' => $config['enable_zoom'] ?? TRUE,
      'title' => $config['gallery_title'] ?? 'Image Gallery',
    ];

    return [
      '#theme' => 'mjm_image_gallery',
      '#gallery_id' => $gallery_id,
      '#images' => $images,
      '#config' => $config,
      '#attached' => [
        'library' => ['mjm_components/marzipano-gallery'],
        'drupalSettings' => [
          'mjmImageGallery' => [
            $gallery_id => $gallery_settings,
          ],
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();

    $form['gallery_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Gallery Title'),
      '#default_value' => $config['gallery_title'] ?? '',
      '#description' => $this->t('Optional title for the image gallery.'),
    ];

    // Gallery settings
    $form['settings'] = [
      '#type' => 'details',
      '#title' => $this->t('Gallery Settings'),
      '#open' => TRUE,
    ];

    $form['settings']['auto_play'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Auto-play slideshow'),
      '#default_value' => $config['auto_play'] ?? FALSE,
      '#description' => $this->t('Automatically advance through images.'),
    ];

    $form['settings']['auto_play_speed'] = [
      '#type' => 'number',
      '#title' => $this->t('Auto-play speed (ms)'),
      '#default_value' => $config['auto_play_speed'] ?? 3000,
      '#min' => 1000,
      '#max' => 10000,
      '#step' => 500,
      '#states' => [
        'visible' => [
          ':input[name="settings[block_form][auto_play]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['settings']['show_thumbnails'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show thumbnails'),
      '#default_value' => $config['show_thumbnails'] ?? TRUE,
      '#description' => $this->t('Display thumbnail navigation.'),
    ];

    $form['settings']['enable_zoom'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable zoom controls'),
      '#default_value' => $config['enable_zoom'] ?? TRUE,
      '#description' => $this->t('Allow users to zoom in/out on images.'),
    ];

    // Images section
    $form['images'] = [
      '#type' => 'details',
      '#title' => $this->t('Images'),
      '#open' => TRUE,
      '#description' => $this->t('Add multiple images to create your gallery. You can either upload files or provide URLs to external images.'),
    ];

    // Get existing images or default to 3 empty slots
    $images = $config['images'] ?? [];
    $num_images = max(3, count($images) + 1);

    for ($i = 0; $i < $num_images; $i++) {
      $form['images'][$i] = [
        '#type' => 'details',
        '#title' => $this->t('Image @num', ['@num' => $i + 1]),
        '#open' => !empty($images[$i]) || $i < 3,
      ];

      $form['images'][$i]['title'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Title'),
        '#default_value' => $images[$i]['title'] ?? '',
        '#description' => $this->t('Optional title for this image.'),
      ];

      $form['images'][$i]['description'] = [
        '#type' => 'textarea',
        '#title' => $this->t('Description'),
        '#default_value' => $images[$i]['description'] ?? '',
        '#rows' => 3,
        '#description' => $this->t('Optional description for this image.'),
      ];

      $form['images'][$i]['image_url'] = [
        '#type' => 'url',
        '#title' => $this->t('Image URL'),
        '#default_value' => $images[$i]['image_url'] ?? '',
        '#description' => $this->t('Enter a URL to an external image (e.g., from DigitalOcean CDN). This takes precedence over file upload.'),
      ];

      $form['images'][$i]['image_file'] = [
        '#type' => 'managed_file',
        '#title' => $this->t('Upload Image'),
        '#default_value' => $images[$i]['image_file'] ?? [],
        '#upload_location' => 'public://mjm-gallery/',
        '#upload_validators' => [
          'FileExtension' => ['extensions' => 'jpg jpeg png gif webp'],
          'FileSizeLimit' => ['fileLimit' => 10485760], // 10MB
        ],
        '#description' => $this->t('Upload an image file. Supported formats: JPG, PNG, GIF, WebP. Max size: 10MB.'),
        '#states' => [
          'visible' => [
            ':input[name="settings[block_form][images][' . $i . '][image_url]"]' => ['value' => ''],
          ],
        ],
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockValidate($form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $has_images = FALSE;

    // Check if at least one image is provided
    if (!empty($values['images'])) {
      foreach ($values['images'] as $image_data) {
        if (!empty($image_data['image_url']) || !empty($image_data['image_file'])) {
          $has_images = TRUE;
          break;
        }
      }
    }

    if (!$has_images) {
      $form_state->setErrorByName('images', $this->t('Please provide at least one image for the gallery.'));
    }

    // Validate URLs
    if (!empty($values['images'])) {
      foreach ($values['images'] as $index => $image_data) {
        if (!empty($image_data['image_url'])) {
          if (!filter_var($image_data['image_url'], FILTER_VALIDATE_URL)) {
            $form_state->setErrorByName("images][$index][image_url", $this->t('Please enter a valid URL for image @num.', ['@num' => $index + 1]));
          }
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    
    // Save file references
    if (!empty($values['images'])) {
      foreach ($values['images'] as $index => $image_data) {
        if (!empty($image_data['image_file'])) {
          $file = File::load($image_data['image_file'][0]);
          if ($file) {
            $file->setPermanent();
            $file->save();
          }
        }
      }
    }

    // Filter out empty images
    $images = [];
    if (!empty($values['images'])) {
      foreach ($values['images'] as $image_data) {
        if (!empty($image_data['image_url']) || !empty($image_data['image_file'])) {
          $images[] = $image_data;
        }
      }
    }

    $this->setConfigurationValue('gallery_title', $values['gallery_title']);
    $this->setConfigurationValue('auto_play', $values['auto_play']);
    $this->setConfigurationValue('auto_play_speed', $values['auto_play_speed']);
    $this->setConfigurationValue('show_thumbnails', $values['show_thumbnails']);
    $this->setConfigurationValue('enable_zoom', $values['enable_zoom']);
    $this->setConfigurationValue('images', $images);
  }

}
