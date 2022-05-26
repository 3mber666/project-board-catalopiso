jQuery(document).ready(function($) {
    $('[data-action--delete]').click(function () {
        if (!showNotice.warn()) {
            return false;
        }
    });
});