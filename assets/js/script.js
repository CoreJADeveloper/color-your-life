/**
 * Created by Lenovo on 24-Mar-18.
 */
jQuery(document).ready(function($){
    $('.left-color-field').wpColorPicker({
        change: function (event, ui) {
            var element = event.target;
            var color = ui.color.toString();

            $('.color-your-life .left-roller').css('background', color);
            $('.color-your-life .left').css('background', color);
        },
    });
    $('.middle-color-field').wpColorPicker({
        change: function (event, ui) {
            var element = event.target;
            var color = ui.color.toString();

            $('.color-your-life .middle-roller').css('background', color);
            $('.color-your-life .middle').css('background', color);
        },
    });
    $('.right-color-field').wpColorPicker({
        change: function (event, ui) {
            var element = event.target;
            var color = ui.color.toString();

            $('.color-your-life .right-roller').css('background', color);
            $('.color-your-life .right').css('background', color);
        },
    });
});