(function ($) {

    var aThemesAddonsCountdown = function ($scope, $) {
        var element = $scope.find('.countdown-timer');
        var wrapperClass = 'countdown-element';

        var countdown = element.data('end-date');
        var type = element.data('type'); // evergreen or fixed
        var evergreen_hours = element.data('hours');
        var evergreen_minutes = element.data('minutes');
        var evergreen_reset = element.data('reset');

        var labels = {
            days: {
                singular: element.data('label-days-singular'),
                plural: element.data('label-days-plural')
            },
            hours: {
                singular: element.data('label-hours-singular'),
                plural: element.data('label-hours-plural')
            },
            minutes: {
                singular: element.data('label-minutes-singular'),
                plural: element.data('label-minutes-plural')
            },
            seconds: {
                singular: element.data('label-seconds-singular'),
                plural: element.data('label-seconds-plural')
            }
        };

        var expire_action = element.data('expire-action'); // nothing, text, url, template
        var redirect_url = element.data('redirect-url');

        var separator = element.data('separator') === 'yes' ? '<span class="countdown-separator">:</span>' : ' ';

        var createLabelElement = function (value, label, singularLabel, pluralLabel) {
            return '<span class="timer-element">' + value + '</span><span class="label-element">' + (value > 1 ? pluralLabel : singularLabel) + '</span>';
        };

        var createWrapper = function (content) {
            return '<span class="' + wrapperClass + '">' + content + '</span>';
        };

        if (type === 'fixed') {
            var countDownDate = new Date(countdown).getTime();

            var x = setInterval(function () {
                var now = new Date().getTime();
                var distance = countDownDate - now;

                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                days = days < 10 ? '0' + days : days;
                hours = hours < 10 ? '0' + hours : hours;
                minutes = minutes < 10 ? '0' + minutes : minutes;
                seconds = seconds < 10 ? '0' + seconds : seconds;

                var daysLabel = createWrapper(createLabelElement(days, 'Days', labels.days.singular, labels.days.plural));
                var hoursLabel = createWrapper(createLabelElement(hours, 'Hours', labels.hours.singular, labels.hours.plural));
                var minutesLabel = createWrapper(createLabelElement(minutes, 'Minutes', labels.minutes.singular, labels.minutes.plural));
                var secondsLabel = createWrapper(createLabelElement(seconds, 'Seconds', labels.seconds.singular, labels.seconds.plural));

                var countdownText = daysLabel + separator + hoursLabel + separator + minutesLabel + separator + secondsLabel;
                element.html(countdownText);

                if (distance < 0) {
                    clearInterval(x);
                    if ('nothing' === expire_action) {
                        element.html(createWrapper(createLabelElement('00', labels.days.plural, 'Days', labels.days.plural)) + separator +
                            createWrapper(createLabelElement('00', labels.hours.plural, 'Hours', labels.hours.plural)) + separator +
                            createWrapper(createLabelElement('00', labels.minutes.plural, 'Minutes', labels.minutes.plural)) + separator +
                            createWrapper(createLabelElement('00', labels.seconds.plural, 'Seconds', labels.seconds.plural)));
                    } else if ('text' === expire_action || 'template' === expire_action) {
                        $scope.find('.countdown-expired-content').show();
                        $scope.find('.countdown-timer').hide();
                    } else if ('url' === expire_action) {
                        if (!elementorFrontend.isEditMode()) {
                            window.location.href = redirect_url;
                        }
                    }
                }
            }, 1000);

        } else if (type === 'evergreen') {
            var localStorageKey = 'evergreenCountdown';
            var storedCountdown = localStorage.getItem(localStorageKey);

            var countDownDate;
            if (storedCountdown) {
                countDownDate = new Date(storedCountdown);
            } else {
                countDownDate = new Date();
                countDownDate.setHours(countDownDate.getHours() + evergreen_hours);
                countDownDate.setMinutes(countDownDate.getMinutes() + evergreen_minutes);
                localStorage.setItem(localStorageKey, countDownDate);
            }

            var x = setInterval(function () {
                var now = new Date();
                var distance = countDownDate - now;

                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                hours = hours < 10 ? '0' + hours : hours;
                minutes = minutes < 10 ? '0' + minutes : minutes;
                seconds = seconds < 10 ? '0' + seconds : seconds;

                var hoursLabel = createWrapper(createLabelElement(hours, 'Hours', labels.hours.singular, labels.hours.plural));
                var minutesLabel = createWrapper(createLabelElement(minutes, 'Minutes', labels.minutes.singular, labels.minutes.plural));
                var secondsLabel = createWrapper(createLabelElement(seconds, 'Seconds', labels.seconds.singular, labels.seconds.plural));

                var countdownText = hoursLabel + separator + minutesLabel + separator + secondsLabel;
                element.html(countdownText);

                if (distance < 0) {
                    if ('yes' === evergreen_reset) {
                        countDownDate = new Date();
                        countDownDate.setHours(countDownDate.getHours() + evergreen_hours);
                        countDownDate.setMinutes(countDownDate.getMinutes() + evergreen_minutes);
                        localStorage.setItem(localStorageKey, countDownDate);
                    } else {
                        element.html(createWrapper(createLabelElement('00', 'Hours', labels.hours.singular, labels.hours.plural)) + separator +
                            createWrapper(createLabelElement('00', 'Minutes', labels.minutes.singular, labels.minutes.plural)) + separator +
                            createWrapper(createLabelElement('00', 'Seconds', labels.seconds.singular, labels.seconds.plural)));
                    }
                }
            }, 1000);
        }
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/athemes-addons-countdown.default', aThemesAddonsCountdown);
    });

})(jQuery);
