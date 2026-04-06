import './bootstrap';
import * as bootstrap from 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import $ from 'jquery';
window.$ = window.jQuery = $;

$(document).ready(function() {
    // Load Select2 only on pages that use it.
    if ($('.select2').length) {
        import('select2').then((module) => {
            if (typeof module.default === 'function') {
                module.default(window, $);
                $('.select2').select2();
            }
        });
    }

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
