define([
    'jquery'
], function ($) {
    'use strict';
    return function (target) {
        $.validator.addMethod(
            'validate-emails-semicolon-separated',
            function (value) {
                let emailsArray = value.replace(/;$/, '').split(';');
                const emailRegex = new RegExp(
                    [
                        '^(([^<>()\\[\\]\\.,;:\\s@"]+(\\.[^<>()\\[\\]\\.,;:\\s@"]+)*)|',
                        '(".+"))@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\])|',
                        '(([a-zA-Z\\-0-9]+\\.)+[a-zA-Z]{2,}))$'
                    ].join('')
                );

                return emailsArray.every( emailAddress => {
                        return emailRegex.test(String(emailAddress).toLowerCase());
                    }
                );
            },
            $.mage.__('Please enter a valid email addresses string. Make sure that they are semicolon separated.')
        );
        return target;
    };
});
