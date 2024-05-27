require('./bootstrap');
import { createApp } from 'vue';
import 'bootstrap-icons/font/bootstrap-icons.css'
import "bootstrap/dist/css/bootstrap.min.css"

let app=createApp({})
app.component('survey-index', require('./components/survey/index.vue').default);
app.component('survey-form-layout', require('./components/survey/layoutsurvey.vue').default);
app.component('survey-form', require('./components/survey/surveyform.vue').default);
app.mount("#app")