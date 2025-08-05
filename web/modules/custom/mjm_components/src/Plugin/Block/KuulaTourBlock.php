<?php

namespace Drupal\mjm_components\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Kuula Tour' Block.
 *
 * @Block(
 *   id = "kuula_tour_block",
 *   admin_label = @Translation("Kuula Tour"),
 *   category = @Translation("MJM Components"),
 * )
 */
class KuulaTourBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();
    
    return [
      '#theme' => 'kuula_tour_block',
      '#title' => $config['title'] ?? '',
      '#tour_url' => $config['tour_url'] ?? '',
      '#embed_code' => $config['embed_code'] ?? '',
      '#width' => $config['width'] ?? '100%',
      '#height' => $config['height'] ?? '600px',
      '#description' => $config['description'] ?? '',
      '#autoplay' => $config['autoplay'] ?? FALSE,
      '#show_info' => $config['show_info'] ?? TRUE,
      '#show_controls' => $config['show_controls'] ?? TRUE,
      '#attached' => [
        'library' => [
          'mjm_components/kuula-tour',
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
      '#description' => $this->t('Enter a title for the tour (optional).'),
      '#default_value' => $config['title'] ?? '',
    ];

    $form['tour_input_method'] = [
      '#type' => 'radios',
      '#title' => $this->t('Input Method'),
      '#description' => $this->t('Choose how you want to add the Kuula tour.'),
      '#options' => [
        'url' => $this->t('Share URL'),
        'embed' => $this->t('Embed Code'),
      ],
      '#default_value' => $config['tour_input_method'] ?? 'url',
    ];

    $form['tour_url'] = [
      '#type' => 'url',
      '#title' => $this->t('Kuula Tour Share URL'),
      '#description' => $this->t('Enter the Kuula share URL (e.g., https://kuula.co/share/xyz). This will be automatically converted to an embed.'),
      '#default_value' => $config['tour_url'] ?? '',
      '#states' => [
        'visible' => [
          ':input[name="settings[tour_input_method]"]' => ['value' => 'url'],
        ],
        'required' => [
          ':input[name="settings[tour_input_method]"]' => ['value' => 'url'],
        ],
      ],
    ];

    $form['embed_code'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Kuula Embed Code'),
      '#description' => $this->t('Paste the complete embed code from Kuula (iframe code).'),
      '#default_value' => $config['embed_code'] ?? '',
      '#rows' => 4,
      '#states' => [
        'visible' => [
          ':input[name="settings[tour_input_method]"]' => ['value' => 'embed'],
        ],
        'required' => [
          ':input[name="settings[tour_input_method]"]' => ['value' => 'embed'],
        ],
      ],
    ];

    $form['dimensions'] = [
      '#type' => 'details',
      '#title' => $this->t('Dimensions'),
      '#open' => FALSE,
    ];

    $form['dimensions']['width'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Width'),
      '#description' => $this->t('Enter width (e.g., 100%, 800px).'),
      '#default_value' => $config['width'] ?? '100%',
      '#size' => 20,
    ];

    $form['dimensions']['height'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Height'),
      '#description' => $this->t('Enter height (e.g., 600px, 400px).'),
      '#default_value' => $config['height'] ?? '600px',
      '#size' => 20,
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#description' => $this->t('Optional description to display below the tour.'),
      '#default_value' => $config['description'] ?? '',
      '#rows' => 3,
    ];

    $form['options'] = [
      '#type' => 'details',
      '#title' => $this->t('Tour Options'),
      '#open' => FALSE,
    ];

    $form['options']['autoplay'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Autoplay'),
      '#description' => $this->t('Start the tour automatically when loaded.'),
      '#default_value' => $config['autoplay'] ?? FALSE,
    ];

    $form['options']['show_info'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show Info Panel'),
      '#description' => $this->t('Display information panel on the tour.'),
      '#default_value' => $config['show_info'] ?? TRUE,
    ];

    $form['options']['show_controls'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show Controls'),
      '#description' => $this->t('Display navigation controls on the tour.'),
      '#default_value' => $config['show_controls'] ?? TRUE,
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
    $this->configuration['tour_input_method'] = $values['tour_input_method'];
    $this->configuration['tour_url'] = $values['tour_url'];
    $this->configuration['embed_code'] = $values['embed_code'];
    $this->configuration['width'] = $values['dimensions']['width'];
    $this->configuration['height'] = $values['dimensions']['height'];
    $this->configuration['description'] = $values['description'];
    $this->configuration['autoplay'] = $values['options']['autoplay'];
    $this->configuration['show_info'] = $values['options']['show_info'];
    $this->configuration['show_controls'] = $values['options']['show_controls'];
  }

  /**
   * {@inheritdoc}
   */
  public function blockValidate($form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    
    if ($values['tour_input_method'] === 'url') {
      $tour_url = $values['tour_url'];
      if (!empty($tour_url) && !preg_match('/kuula\.co/', $tour_url)) {
        $form_state->setErrorByName('tour_url', $this->t('Please enter a valid Kuula URL (must contain kuula.co).'));
      }
    }
    
    if ($values['tour_input_method'] === 'embed') {
      $embed_code = $values['embed_code'];
      if (!empty($embed_code) && !preg_match('/<iframe[^>]*kuula\.co[^>]*>/', $embed_code)) {
        $form_state->setErrorByName('embed_code', $this->t('Please enter a valid Kuula embed code (must contain an iframe with kuula.co).'));
      }
    }
  }

}
