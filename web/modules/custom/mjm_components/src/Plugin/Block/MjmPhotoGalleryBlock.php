<?php

namespace Drupal\mjm_components\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Url;
use Drupal\Component\Utility\Html;

/**
 * Provides an MJM Photo Gallery block.
 *
 * @Block(
 *   id = "mjm_photo_gallery",
 *   admin_label = @Translation("MJM Photo Gallery"),
 *   category = @Translation("MJM Components")
 * )
 */
class MjmPhotoGalleryBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();
    $gallery_id = 'mjm-photo-gallery-' . Html::getUniqueId('gallery');
    
    // Process images
    $images = [];
    if (!empty($config['photos'])) {
      foreach ($config['photos'] as $index => $photo_data) {
        if (!empty($photo_data['photo_file']) || !empty($photo_data['photo_url'])) {
          $image_url = '';
          $thumbnail_url = '';
          
          // Handle file upload
          if (!empty($photo_data['photo_file'])) {
            $file = File::load($photo_data['photo_file'][0]);
            if ($file) {
              $image_url = $file->createFileUrl();
              // Use the same image as thumbnail for now - could be enhanced with image styles
              $thumbnail_url = $image_url;
            }
          }
          // Handle URL input (takes precedence)
          elseif (!empty($photo_data['photo_url'])) {
            $image_url = $photo_data['photo_url'];
            $thumbnail_url = $photo_data['thumbnail_url'] ?: $image_url;
          }
          
          if ($image_url) {
            $images[] = [
              'id' => 'photo_' . $index,
              'url' => $image_url,
              'thumbnail' => $thumbnail_url,
              'title' => $photo_data['title'] ?? '',
              'description' => $photo_data['description'] ?? '',
              'photographer' => $photo_data['photographer'] ?? '',
              'date_taken' => $photo_data['date_taken'] ?? '',
              'location' => $photo_data['location'] ?? '',
            ];
          }
        }
      }
    }

    if (empty($images)) {
      return [
        '#markup' => '<p>No photos configured for gallery.</p>',
      ];
    }

    // Prepare settings for JavaScript
    $gallery_settings = [
      'images' => $images,
      'layout' => $config['layout'] ?? 'grid',
      'columns' => (int) ($config['columns'] ?? 3),
      'autoPlay' => $config['auto_play'] ?? FALSE,
      'autoPlaySpeed' => (int) ($config['auto_play_speed'] ?? 5000),
      'showCaptions' => $config['show_captions'] ?? TRUE,
      'showThumbnails' => $config['show_thumbnails'] ?? TRUE,
      'enableLightbox' => $config['enable_lightbox'] ?? TRUE,
      'title' => $config['gallery_title'] ?? 'Photo Gallery',
    ];

    return [
      '#theme' => 'mjm_photo_gallery',
      '#gallery_id' => $gallery_id,
      '#images' => $images,
      '#config' => $config,
      '#attached' => [
        'library' => ['mjm_components/photo-gallery'],
        'drupalSettings' => [
          'mjmPhotoGallery' => [
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
      '#description' => $this->t('Optional title for the photo gallery.'),
    ];

    // Gallery layout settings
    $form['layout_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('Layout Settings'),
      '#open' => TRUE,
    ];

    $form['layout_settings']['layout'] = [
      '#type' => 'select',
      '#title' => $this->t('Gallery Layout'),
      '#default_value' => $config['layout'] ?? 'grid',
      '#options' => [
        'grid' => $this->t('Grid Layout'),
        'masonry' => $this->t('Masonry Layout'),
        'carousel' => $this->t('Carousel/Slider'),
      ],
      '#description' => $this->t('Choose how photos are displayed.'),
    ];

    $form['layout_settings']['columns'] = [
      '#type' => 'select',
      '#title' => $this->t('Number of Columns'),
      '#default_value' => $config['columns'] ?? 3,
      '#options' => [
        1 => '1 Column',
        2 => '2 Columns',
        3 => '3 Columns',
        4 => '4 Columns',
        5 => '5 Columns',
      ],
      '#states' => [
        'visible' => [
          ':input[name="settings[block_form][layout_settings][layout]"]' => [
            ['value' => 'grid'],
            ['value' => 'masonry'],
          ],
        ],
      ],
    ];

    // Gallery features
    $form['features'] = [
      '#type' => 'details',
      '#title' => $this->t('Gallery Features'),
      '#open' => TRUE,
    ];

    $form['features']['enable_lightbox'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Lightbox'),
      '#default_value' => $config['enable_lightbox'] ?? TRUE,
      '#description' => $this->t('Allow clicking photos to view in full-screen lightbox.'),
    ];

    $form['features']['show_captions'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show Captions'),
      '#default_value' => $config['show_captions'] ?? TRUE,
      '#description' => $this->t('Display photo titles and descriptions.'),
    ];

    $form['features']['show_thumbnails'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show Thumbnail Navigation'),
      '#default_value' => $config['show_thumbnails'] ?? TRUE,
      '#description' => $this->t('Display thumbnail navigation in lightbox.'),
      '#states' => [
        'visible' => [
          ':input[name="settings[block_form][features][enable_lightbox]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['features']['auto_play'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Auto-play slideshow'),
      '#default_value' => $config['auto_play'] ?? FALSE,
      '#description' => $this->t('Automatically advance through photos (in carousel mode or lightbox).'),
    ];

    $form['features']['auto_play_speed'] = [
      '#type' => 'number',
      '#title' => $this->t('Auto-play speed (ms)'),
      '#default_value' => $config['auto_play_speed'] ?? 5000,
      '#min' => 2000,
      '#max' => 15000,
      '#step' => 1000,
      '#states' => [
        'visible' => [
          ':input[name="settings[block_form][features][auto_play]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    // Photos section
    $form['photos'] = [
      '#type' => 'details',
      '#title' => $this->t('Photos'),
      '#open' => TRUE,
      '#description' => $this->t('Add multiple photos to create your gallery. You can either upload files or provide URLs to external images.'),
    ];

    // Get existing photos or default to 3 empty slots
    $photos = $config['photos'] ?? [];
    $num_photos = max(3, count($photos) + 1);

    for ($i = 0; $i < $num_photos; $i++) {
      $form['photos'][$i] = [
        '#type' => 'details',
        '#title' => $this->t('Photo @num', ['@num' => $i + 1]),
        '#open' => !empty($photos[$i]) || $i < 3,
      ];

      $form['photos'][$i]['title'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Title'),
        '#default_value' => $photos[$i]['title'] ?? '',
        '#description' => $this->t('Photo title or caption.'),
      ];

      $form['photos'][$i]['description'] = [
        '#type' => 'textarea',
        '#title' => $this->t('Description'),
        '#default_value' => $photos[$i]['description'] ?? '',
        '#rows' => 3,
        '#description' => $this->t('Optional description for this photo.'),
      ];

      $form['photos'][$i]['photographer'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Photographer'),
        '#default_value' => $photos[$i]['photographer'] ?? '',
        '#description' => $this->t('Photo credit/photographer name.'),
      ];

      $form['photos'][$i]['date_taken'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Date Taken'),
        '#default_value' => $photos[$i]['date_taken'] ?? '',
        '#description' => $this->t('When the photo was taken (e.g., "March 2024").'),
      ];

      $form['photos'][$i]['location'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Location'),
        '#default_value' => $photos[$i]['location'] ?? '',
        '#description' => $this->t('Where the photo was taken.'),
      ];

      $form['photos'][$i]['photo_url'] = [
        '#type' => 'url',
        '#title' => $this->t('Photo URL'),
        '#default_value' => $photos[$i]['photo_url'] ?? '',
        '#description' => $this->t('Enter a URL to an external photo (e.g., from DigitalOcean CDN). This takes precedence over file upload.'),
      ];

      $form['photos'][$i]['thumbnail_url'] = [
        '#type' => 'url',
        '#title' => $this->t('Thumbnail URL'),
        '#default_value' => $photos[$i]['thumbnail_url'] ?? '',
        '#description' => $this->t('Optional separate thumbnail URL. If not provided, the main photo will be used.'),
        '#states' => [
          'visible' => [
            ':input[name="settings[block_form][photos][' . $i . '][photo_url]"]' => ['!value' => ''],
          ],
        ],
      ];

      $form['photos'][$i]['photo_file'] = [
        '#type' => 'managed_file',
        '#title' => $this->t('Upload Photo'),
        '#default_value' => $photos[$i]['photo_file'] ?? [],
        '#upload_location' => 'public://mjm-photos/',
        '#upload_validators' => [
          'FileExtension' => ['extensions' => 'jpg jpeg png gif webp'],
          'FileSizeLimit' => ['fileLimit' => 20971520], // 20MB
        ],
        '#description' => $this->t('Upload a photo file. Supported formats: JPG, PNG, GIF, WebP. Max size: 20MB.'),
        '#states' => [
          'visible' => [
            ':input[name="settings[block_form][photos][' . $i . '][photo_url]"]' => ['value' => ''],
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
    $has_photos = FALSE;

    // Check if at least one photo is provided
    if (!empty($values['photos'])) {
      foreach ($values['photos'] as $photo_data) {
        if (!empty($photo_data['photo_url']) || !empty($photo_data['photo_file'])) {
          $has_photos = TRUE;
          break;
        }
      }
    }

    if (!$has_photos) {
      $form_state->setErrorByName('photos', $this->t('Please provide at least one photo for the gallery.'));
    }

    // Validate URLs
    if (!empty($values['photos'])) {
      foreach ($values['photos'] as $index => $photo_data) {
        if (!empty($photo_data['photo_url'])) {
          if (!filter_var($photo_data['photo_url'], FILTER_VALIDATE_URL)) {
            $form_state->setErrorByName("photos][$index][photo_url", $this->t('Please enter a valid URL for photo @num.', ['@num' => $index + 1]));
          }
        }
        if (!empty($photo_data['thumbnail_url'])) {
          if (!filter_var($photo_data['thumbnail_url'], FILTER_VALIDATE_URL)) {
            $form_state->setErrorByName("photos][$index][thumbnail_url", $this->t('Please enter a valid thumbnail URL for photo @num.', ['@num' => $index + 1]));
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
    if (!empty($values['photos'])) {
      foreach ($values['photos'] as $index => $photo_data) {
        if (!empty($photo_data['photo_file'])) {
          $file = File::load($photo_data['photo_file'][0]);
          if ($file) {
            $file->setPermanent();
            $file->save();
          }
        }
      }
    }

    // Filter out empty photos
    $photos = [];
    if (!empty($values['photos'])) {
      foreach ($values['photos'] as $photo_data) {
        if (!empty($photo_data['photo_url']) || !empty($photo_data['photo_file'])) {
          $photos[] = $photo_data;
        }
      }
    }

    $this->setConfigurationValue('gallery_title', $values['gallery_title']);
    $this->setConfigurationValue('layout', $values['layout_settings']['layout']);
    $this->setConfigurationValue('columns', $values['layout_settings']['columns']);
    $this->setConfigurationValue('enable_lightbox', $values['features']['enable_lightbox']);
    $this->setConfigurationValue('show_captions', $values['features']['show_captions']);
    $this->setConfigurationValue('show_thumbnails', $values['features']['show_thumbnails']);
    $this->setConfigurationValue('auto_play', $values['features']['auto_play']);
    $this->setConfigurationValue('auto_play_speed', $values['features']['auto_play_speed']);
    $this->setConfigurationValue('photos', $photos);
  }

}
