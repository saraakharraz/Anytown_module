(function (Drupal,once) {
 Drupal.behaviors.forecastToggle = {
    attach: function(context,settings) {
        once('forecast-toggle', 'div.weather_page--forecast', context).forEach(function (el) {
            const long=el.querySelector('.long');
            const short=el.querySelector('.short');
            long.classList.add('visually-hidden');

            // Create and configure a button to toggle between thet wo.
            const toggleButton = document.createElement('button');
            toggleButton.textContent = 'Toggle extended forecast';
            toggleButton.addEventListener('click', function () {
            long.classList.toggle('visually-hidden');
            short.classList.toggle('visually-hidden');
        });
            // Append the button to the page.
            document.querySelector('.weather_page--forecast').appendChild(toggleButton);
        });
    }
 };
})(Drupal,once);