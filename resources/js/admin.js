const checkValidItem = function(selected_time, ele) {
    let check_flag = true;
    ele.each(function() {
        if($(this).val() == selected_time) {
            check_flag = false
        }
    })
    return check_flag;
}
const removeItem = function(removed_time, ele) {
    ele.each(function() {
        if($(this).val() == removed_time) {
            $(this).remove()
        }
    })
}