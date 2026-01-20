$(document).ready(function () {
    $('#post_list').DataTable();
    $('#add_post_model_button').on('click', function () {
        $('#add_post_model form')[0].reset();
        $('#update_data').css("display", "none");
        $('#add_post_model').modal('show');
        $(".post-meta").css("display", "");

    });
});

$('#post_data').on('click', function () {
    var post_data = {
        'post_type': $('input[id="type"]').val(),
        'post_title': $('input[id="title"]').val(),
        'post_content': $('textarea[id="content"]').val(),
        'post_mime_type': $('input[id="mime-type"]').val(),
        'meta': {
            'cusom_field_1': $('input[id="field_1"]').val() ?? '',
            'cusom_field_2': $('input[id="field_2"]').val() ?? '',
            'cusom_field_3': $('input[id="field_3"]').val() ?? '',
        }
    };
    console.log(post_data);
    $.ajax({
        type: "POST",
        url: getBaseURL() + 'add_custom_data',
        data: post_data,
        dataType: "json",
        success: function (data) {
            console.log(data);
            if (data.success) {
                alert('Data is added!')
                location.reload();
            } else {
                alert(l(data.error));
                console.log(data);
            }
        }
    });
    return false;
});


$(".edit_post_button").on('click', function () {
    var post_id = this.id;
    // console.log(post_id, bid);
    $('#post_data').css("display", "none");
    $('#update_data').css("display", "");
    $(".post-meta").css("display", "none");
    $.ajax({
        type: "POST",
        url: getBaseURL() + 'edit_custom_data/' + post_id,
        data: {},
        dataType: "json",
        success: function (data) {
            if (data.success) {
                $("#post_id").val(data.post[0].post_id);
                $("#type").val(data.post[0].post_type);
                $("#title").val(data.post[0].post_title);
                $("#content").val(data.post[0].post_content);
                $("#mime-type").val(data.post[0].post_mime_type);
                $('#add_post_model').modal('show');
            }
        }
    });
    return false;
});

$('#update_data').on('click', function () {
    var post_data = {
        'post_id': $('input[id="post_id"]').val(),
        'post_type': $('input[id="type"]').val(),
        'post_title': $('input[id="title"]').val(),
        'post_content': $('textarea[id="content"]').val(),
        'post_mime_type': $('input[id="mime-type"]').val(),
    };
    console.log(post_data);
    $.ajax({
        type: "POST",
        url: getBaseURL() + 'update_custom_data',
        data: post_data,
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



$(".delete_post_button").on('click', function () {
    var post_id = this.id;
    if (confirm("Are you sure to delete this post ?")) {
        $.ajax({
            type: "POST",
            url: getBaseURL() + 'delete_custom_data/' + post_id,
            data: {},
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
