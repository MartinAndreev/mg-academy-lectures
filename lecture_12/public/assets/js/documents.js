function Documents() {
    return this;
}

$(document).ready(function () {
    $('.datepicker').datepicker({
        format: 'dd.mm.yyyy'
    });
});