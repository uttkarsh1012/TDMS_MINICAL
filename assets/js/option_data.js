$(document).ready(function () {
    $('#option_list').DataTable();
    $('#add_option_model_button').on('click', function () {
        $('#add_option_model form')[0].reset();
        $('#update_option').css("display", "none");
        $('#add_option_model').modal('show');
    });
});

$('#add_option').on('click', function () {
    var autoload = 0;
    if ($('#autoload').is(":checked")) {
        autoload = 1;
    }
    var option_data = {
        'option_name': $('input[id="option_name"]').val(),
        'option_value': $('input[id="option_value"]').val(),
        'autoload': autoload
    };
    console.log(option_data);
    $.ajax({
        type: "POST",
        url: getBaseURL() + 'add_option_data',
        data: option_data,
        dataType: "json",
        success: function (data) {
            if (data.success) {
                alert('Options Added!');
                location.reload();
            } else {
                alert(l(data.error));
                console.log(data);
            }
        }
    });
    return false;
});



$(".edit_option_button").on('click', function () {
    var option = this.id;
    $('#add_option').css("display", "none");
    $('#update_option').css("display", "");
    $.ajax({
        type: "POST",
        url: getBaseURL() + 'edit_option_data',
        data: { "option": option },
        dataType: "json",
        success: function (data) {
            if (data.success) {
                $("#option_name").val(data.option[0].option_name);
                $("#option_value").val(data.option[0].option_value);
                $('#add_option_model').modal('show');
            } else {
                alert(data.error);
            }
        }
    });
    return false;
});

$('#update_option').on('click', function () {
    var autoload = 0;
    if ($('#autoload').is(":checked")) {
        autoload = 1;
    }
    var option_data = {
        'option_name': $('input[id="option_name"]').val(),
        'option_value': $('input[id="option_value"]').val(),
        'autoload': autoload
    };
    console.log(option_data);
    $.ajax({
        type: "POST",
        url: getBaseURL() + 'update_option_data',
        data: option_data,
        dataType: "json",
        success: function (data) {
            console.log(data);
            if (data.success) {
                alert('Data is updated!')
                location.reload();
            } else {
                alert(l(data.error));
                console.log(data);
            }
        }
    });
    return false;
});



$(".delete_option_button").on('click', function () {
    var option = this.id;
    if (confirm("Are you sure to delete this option ?")) {
        $.ajax({
            type: "POST",
            url: getBaseURL() + 'delete_option_data',
            data: { 'option_name': option },
            dataType: "json",
            success: function (data) {
                console.log(data)
                if (data.success) {
                    alert(data.msg);
                    location.reload();
                } else {
                    alert(l(data.error));
                    console.log(data);
                }
            }
        });
    }
    return false;
});
