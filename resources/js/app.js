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
       $('img[loading="lazy"]').each(function () {
        const img = this;

        if (img.complete) {
            $(img).addClass('loaded');
        } else {
            $(img).on('load', function () {
                $(this).addClass('loaded');
            });
        }
    });
});
