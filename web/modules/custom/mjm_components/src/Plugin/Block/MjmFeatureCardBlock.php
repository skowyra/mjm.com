<?php

namespace Drupal\mjm_components\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;

/**
 * Provides a 'MJM Feature Card' Block.
 *
 * @Block(
 *   id = "mjm_feature_card_block",
 *   admin_label = @Translation("MJM Feature Card"),
 *   category = @Translation("MJM Components"),
 * )
 */
class MjmFeatureCardBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();

    $image_render = NULL;
    
    // Check for uploaded file first
    if (!empty($config['image'])) {
      $file = File::load($config['image'][0]);
      if ($file) {
        $image_render = [
          '#theme' => 'image_style',
          '#style_name' => 'medium',
          '#uri' => $file->getFileUri(),
          '#alt' => $config['title'] ?? '',
          '#title' => $config['title'] ?? '',
        ];
      }
    }
    // If no uploaded file, check for external URL
    elseif (!empty($config['image_url'])) {
      $image_render = [
        '#theme' => 'image',
        '#uri' => $config['image_url'],
        '#alt' => $config['title'] ?? '',
        '#title' => $config['title'] ?? '',
        '#attributes' => [
          'loading' => 'lazy',
          'decoding' => 'async',
        ],
      ];
    }

    $link_url = NULL;
    if (!empty($config['link_url'])) {
      $url_input = trim($config['link_url']);
      
      // Handle different URL formats
      if (strpos($url_input, 'http://') === 0 || strpos($url_input, 'https://') === 0) {
        // Full URL with protocol
        $link_url = Url::fromUri($url_input);
      } elseif (strpos($url_input, 'mailto:') === 0 || strpos($url_input, 'tel:') === 0) {
        // Email or phone links
        $link_url = Url::fromUri($url_input);
      } elseif (strpos($url_input, '//') === 0) {
        // Protocol-relative URL
        $link_url = Url::fromUri('https:' . $url_input);
      } elseif (strpos($url_input, '/') === 0 || strpos($url_input, '?') === 0 || strpos($url_input, '#') === 0) {
        // Internal path
        $link_url = Url::fromUserInput($url_input);
      } else {
        // Assume it's an external domain without protocol
        $link_url = Url::fromUri('https://' . $url_input);
      }
    }

    return [
      '#theme' => 'mjm_feature_card_block',
      '#title' => $config['title'] ?? '',
      '#image' => $image_render,
      '#link' => $link_url,
      '#description' => $config['description'] ?? '',
      '#icon' => $config['icon'] ?? '',
      '#cache' => [
        'max-age' => 0,
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
      '#title' => $this->t('Feature Title'),
      '#default_value' => $config['title'] ?? '',
      '#required' => TRUE,
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Feature Description'),
      '#default_value' => $config['description'] ?? '',
      '#rows' => 4,
    ];

    $form['icon'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Icon Class'),
      '#default_value' => $config['icon'] ?? '',
      '#description' => $this->t('Font Awesome icon class (e.g., fas fa-star) or leave empty to use image'),
    ];

    $form['image'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Feature Image/Icon'),
      '#default_value' => $config['image'] ?? NULL,
      '#upload_location' => 'public://mjm_features/',
      '#upload_validators' => [
        'FileExtension' => [
          'extensions' => 'png gif jpg jpeg svg',
        ],
        'FileSizeLimit' => [
          'fileLimit' => 26214400, // 25MB in bytes
        ],
      ],
      '#description' => $this->t('Upload an image file OR use the Image URL field below. Leave both empty to use Font Awesome icon.'),
    ];

    $form['image_url'] = [
      '#type' => 'url',
      '#title' => $this->t('Image URL'),
      '#default_value' => $config['image_url'] ?? '',
      '#description' => $this->t('External image URL (e.g., from DigitalOcean CDN). This will be used if no file is uploaded above.'),
    ];

    $form['link_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Link URL'),
      '#default_value' => $config['link_url'] ?? '',
      '#description' => $this->t('Examples: /about, https://example.com, google.com, mailto:info@example.com'),
    ];

    $form['link_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Link Text'),
      '#default_value' => $config['link_text'] ?? 'Learn More',
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
    $this->configuration['icon'] = $values['icon'];
    $this->configuration['image'] = $values['image'];
    $this->configuration['image_url'] = $values['image_url'];
    $this->configuration['link_url'] = $values['link_url'];
    $this->configuration['link_text'] = $values['link_text'];

    // Make the uploaded file permanent
    if (!empty($values['image'])) {
      $file = File::load($values['image'][0]);
      if ($file) {
        $file->setPermanent();
        $file->save();
      }
    }
  }

}
