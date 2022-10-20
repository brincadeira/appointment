function getDoctors()
{
    $.ajax({
        type: "GET",
        url: 'doctors.php',
        success: function (response) {
            response = JSON.parse(response);
            var html = "";            
            if(response.length) {
                $.each(response, function(key,value) {
                    html += '<option value="' + value.Id + '">' + value.FullName + '</option>';
                });
            } else {
                html += '<div class="alert alert-warning">Запись не производится!</div>';
                $("#form").html(html);
            }
            $("#doctors-list").html(html);
        }
    });    
}

function submitForm() 
{
    $("#btnSubmit").on("click", function() {
        var $this 		    = $("#btnSubmit");
        var $caption        = $this.html();
        var form 			= "#form";
        var formData        = $(form).serializeArray();
        var route 			= $(form).attr('action');

        $.ajax({
            type: "POST",
            url: route,
            data: formData,
            beforeSend: function () {
                $this.attr('disabled', true).html("Отправка...");
            },
            success: function (response) {
                $this.attr('disabled', false).html($caption);
                response = JSON.parse(response);
                $('#myModal').modal('show');
                $("#result").html(response);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#myModal').modal('show');
                $("#result").html('Произошла ошибка.');
            }
        });
    });
}

$(document).ready(function() {
    getDoctors();
    submitForm();     
});