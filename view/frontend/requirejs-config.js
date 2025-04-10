var config = {
    'paths': {
        'dmpt': 'Hikmadh_AbandonedCart/js/dmpt',
        'stick-to-me' : 'Hikmadh_AbandonedCart/js/stick-to-me'
    },
    'shim': {
        'dmpt': {
            exports: '_dmTrack',
            deps: ['jquery']
        },
        'stick-to-me': {
            deps: ['jquery']
        }
    }
};
