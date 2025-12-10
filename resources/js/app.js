import './bootstrap';
import * as bootstrap from 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import $ from 'jquery';
window.$ = window.jQuery = $;
import select2 from 'select2';
select2(window, $);

$(document).ready(function() {
    console.log("jQuery is ready!");
    $('.select2').select2();
});
