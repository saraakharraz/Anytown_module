<?php

declare(strict_types=1);

namespace Drupal\anytown\Controller;

use Drupal\anytown\ForecastClientInterface;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
/**
 * Controller for anytown.weather_page route.
 */
class WeatherPage extends ControllerBase {


  /**
   * Forecast API client.
   *
   * @var \Drupal\anytown\ForecastClientInterface
   */
  private $forecastClient;

  /**
   * WeatherPage controller constructor.
   *
   * @param \Drupal\anytown\ForecastClientInterface $forecast_client
   *   Forecast API client service.
   */
  public function __construct(ForecastClientInterface $forecast_client) {
    $this->forecastClient = $forecast_client;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('anytown.forecast_client')
    );
  }

  /**
   * Builds the response.
   */
  public function build(string $style): array { 
    $style = (in_array($style, ['short', 'extended'])) ? $style : 'short';

    $url = 'https://raw.githubusercontent.com/DrupalizeMe/module-developer-guide-demo-site/main/backups/weather_forecast.json';
    $forecast_data = $this->forecastClient->getForecastData($url);

    $rows = [];
    $highest = 0;
    $lowest = 0;
    if ($forecast_data) {
    
      foreach ($forecast_data as $item) {
        [
          'weekday' => $weekday,
          'description' => $description,
          'high' => $high,
          'low' => $low,
          'icon' => $icon,
        ] = $item;

        $rows[] = [

          $weekday,
   
          [
            'data' => [
              '#markup' => '<img alt="' . $description . '" src="' . $icon . '" width="200" height="200" />',
            ],
          ],
          [
            'data' => [
              '#markup' => $this->t("<em>@description</em> with a high of @high and a low of @low ",['@description'=>$description,'@high'=>$high,'@low'=>$low,]),
            ],
          ],
        ];
        $highest = max($highest, $high);
        $lowest = min($lowest, $low);

      }

      $weather_forecast = [
        '#type' => 'table',
        '#header' => [
          $this->t('Day'),
          '',
          $this->t('Forecast'),
        ],
        '#rows' => $rows,
        '#attributes' => [
          'class' => ['weather_page--forecast-table'],
        ],
      ];

       $short_forecast = [
        '#type' => 'markup',
        '#markup' => $this->t("The high for the weekend is @highest and the low is @lowest.",['@highest'=>$highest,'@lowest'=>$lowest]),
      ];

    }
    else {
     
      $weather_forecast = ['#markup' => $this->t('<p>Could not get the weather forecast. Dress for anything.</p>')];
      $short_forecast = NULL;
    }

    $build = [
      '#theme' => 'weather_page',
      '#attached' => [
        'library' => ['anytown/forecast'],
      ],
      '#weather_intro' => [
        '#markup' => $this->t("<p>Check out this weekend's weather forecast and come prepared. The market is mostly outside, and takes place rain or shine.</p>"),
      ],
      '#weather_forecast' => $weather_forecast,
      '#short_forecast' => $short_forecast,
      '#weather_closures' => [
        '#theme' => 'item_list',
        '#title' => $this->t('Weather related closures'),
        '#items' => [
          $this->t('Ice rink closed until winter - please stay off while we prepare it.'),
          $this->t('Parking behind Apple Lane is still closed from all the rain last weekend.'),
        ],
      ],
    ];

    return $build;
  }

}