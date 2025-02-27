/*
 |--------------------------------------------------------------------------
 | Laravel Spark Bootstrap
 |--------------------------------------------------------------------------
 |
 | First, we will load all of the "core" dependencies for Spark which are
 | libraries such as Vue and jQuery. This also loads the Spark helpers
 | for things such as HTTP calls, forms, and form validation errors.
 |
 | Next, we'll create the root Vue application for Spark. This will start
 | the entire application and attach it to the DOM. Of course, you may
 | customize this script as you desire and load your own components.
 |
 */

require("spark-bootstrap");
require("./components/bootstrap");
window.Vue = require("vue");

// import plugin
import VueToastr from "vue-toastr";
// use plugin
Vue.use(VueToastr, {
    /* OverWrite Plugin Options if you need */
    defaultTimeout: 3000,
    defaultProgressBar: false,
    defaultProgressBarValue: 0,
    defaultClassNames: ["animated", "zoomInUp"]
});

let invoice = require("./components/invoice.vue");

let projects = require("./views/project/index.vue");

require("./bootstrap");

import router from "./routes";

var app = new Vue({
    router,
    mixins: [require("spark")],
    components: {
        invoice,
        projects
    }
});
// .$mount('#project');
