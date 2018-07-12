<?php

namespace Drupal\video_migrate\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class APISettingsForm.
 */
class APISettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'video_migrate.api_settings',

      // Video migrate configs.
      'migrate_plus.migration.video_migrate_tags',
      'migrate_plus.migration.video_migrate_providers',
      'migrate_plus.migration.video_migrate_playlists',
      'migrate_plus.migration.video_migrate_videos',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'api_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('video_migrate.api_settings');

    $form['api_url'] = [
      '#type' => 'url',
      '#title' => $this->t('API url'),
      '#default_value' => $config->get('api_url'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $apiUrl = $form_state->getValue('api_url');

    // Updating source url migrate configs.
    $this->updateSourceUrlConfig('tags', $apiUrl);
    $this->updateSourceUrlConfig('providers', $apiUrl);
    $this->updateSourceUrlConfig('playlists', $apiUrl);
    $this->updateSourceUrlConfig('videos', $apiUrl);

    // Update the api settings config.
    $this->config('video_migrate.api_settings')
      ->set('api_url', $apiUrl)
      ->save();
  }

  /**
   * A helper method to update the source ulr value of any video migrate migrations configs
   *  base on the $name param.
   *
   * @param $name
   * @param $url
   */
  protected function updateSourceUrlConfig($name, $url) {
    $migrateConfig = "migrate_plus.migration.video_migrate_{$name}";
    $sourceUrl = "{$url}/$name";

    $this->config($migrateConfig)
      ->set('source.urls', $sourceUrl)
      ->save();
  }

}
