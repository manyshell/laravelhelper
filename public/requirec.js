require.config({
    baseUrl: "/components/",
    map: {
        '*': {
            'css': 'require-css-master/css'
        }
    },
    paths: {
        "jquery"        : ["jquery/dist/jquery.min"],
        "jquery-ui"     : ["jquery-ui-bootstrap/assets/js/jquery-ui-1.10.0.custom.min"],
        "bootstrap"     : ["bootstrap/js/bootstrap.min"],
        "sweetalert"    : ["sweetalert-master/dist/sweetalert-dev"],
        "moment"        : ["moment/min/moment.min"],
        "GTcommon"      : ["GTcommon/GTcommon"],
        "marquee"       : ["aamirafridi-jQuery.Marquee/jquery.marquee.min"]
    },
    shim: {
        "bootstrap": {
            deps:[
                'jquery',
                'css!/components/bootstrap/css/bootstrap.min'
            ]
        },
        "marquee": {
            deps:['jquery']
        },
        "GTcommon": {
            deps:['jquery', 'sweetalert']
        },
        "jquery-ui" : {
            deps:['jquery'],
            exports:"jquery-ui"
        }
    }
});
