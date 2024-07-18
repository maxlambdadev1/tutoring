const checkValidItem = function (selected_time, ele) {
    let check_flag = true;
    ele.each(function () {
        if ($(this).val() == selected_time) {
            check_flag = false
        }
    })
    return check_flag;
}
const removeItem = function (removed_time, ele) {
    ele.each(function () {
        if ($(this).val() == removed_time) {
            $(this).remove()
        }
    })
}
$(document).ready(function () {
        toastr.options = {
            'closeButton': true,
            'debug': false,
            'newestOnTop': false,
            'progressBar': false,
            'positionClass': 'toast-top-right',
            'preventDuplicates': false,
            'showDuration': '1000',
            'hideDuration': '1000',
            'timeOut': '5000',
            'extendedTimeOut': '1000',
            'showEasing': 'swing',
            'hideEasing': 'linear',
            'showMethod': 'fadeIn',
            'hideMethod': 'fadeOut',
        }
    $(document).on('showToastrMessage', event => {
        let data = event.detail[0];
        toastr[data.status](data.message, data.title ?? '');
        if (data.flag) setTimeout(() => {
            location.reload();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }, 3000);
    })

});