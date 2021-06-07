// GOOGLE ANALYTICS DISABLE MAGICz
// Copyright (C) 2018, Walliser Solutions
// author Lukas Walliser <contact@lukas-walliser.ch>
jQuery(window).ready(function ($) {
    var gaProperty = 'UA-134292005-1';
    var G4Property = 'G-5HP551WJK1';
    if (gaProperty || G4Property) {
        console.log('DSGVO: Handling ' + gaProperty + '  ' + G4Property);
        var disableAnalytics = 'ga-disable-' + gaProperty;
        var disableG4 = 'ga-disable-' + G4Property;
        // Prevent Analytics from helping when cookie is set
        if (document.cookie.indexOf(disableAnalytics + '=true') > -1) {
            window[disableAnalytics] = true;
            window[disableG4] = true;
        }
        // Add Opt-out function here:
        $('.ga-opt-out, a[href*="ga-opt-out"]').bind("click touch", function (e) {
            e.preventDefault();
            if (document.cookie.indexOf(disableAnalytics + '=true') > -1 ||document.cookie.indexOf(disableG4 + '=true') > -1) {
                alert("Google Analytics wurde bereits blockiert.");
            } else {
                alert("Google Analytics blockiert.");
            }
            var d = new Date();
            d.setTime(d.getTime() + 365 * 1000 * 60 * 60 * 24); // in milliseconds
            document.cookie = disableAnalytics + '=true; expires=' + d.toGMTString() + ";path=/";
            document.cookie = disableG4 + '=true; expires=' + d.toGMTString() + ";path=/";
            window[disableAnalytics] = true;
            window[disableG4] = true;
        });

        var gaJsHost = (("https:" == document.location.protocol) ? "https://www." : "http://www.");
        var gaScript = document.createElement('script');
        gaScript.src = gaJsHost + 'googletagmanager.com/gtag/js?id=' + (G4Property ? G4Property : gaProperty);
        document.body.appendChild(gaScript);

        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', gaProperty, {
            'anonymize_ip': true
        });
        gtag('config', G4Property, {
            'anonymize_ip': true
        });
    }
}(jQuery));