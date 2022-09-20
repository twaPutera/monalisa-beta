$(".form-submit").submit(function (e) {
    e.preventDefault();
    let form = $(this);
    let formData = new FormData(form[0]);
    let url = form.attr('action');
    let method = form.attr('method');
    let enctype = form.attr("enctype");
    $(this).children('input').each(
        function () {
            if ($(this).attr('type') == 'file') {
                if ($(this).attr('multiple')) {
                    for (let i = 0; i < $(this)[0].files.length; i++) {
                        formData.append($(this).attr('name'), $(this)[0].files[i]);
                    }
                } else {
                    formData.append($(this).attr('name'), $(this)[0].files[0]);
                }
            } else if ($(this).attr('type') == 'checkbox') {
                let arrayChecked = [];
                $('input[name="' + $(this).attr('name') + '"]:checked').each(function () {
                    arrayChecked.push($(this).val());
                })
                if ($(this).is(':checked')) {
                    formData.append($(this).attr('name'), arrayChecked);
                }
            } else if ($(this).attr('type') == 'radio') {
                if ($(this).is(':checked')) {
                    formData.append($(this).attr('name'), $(this).val());
                }
            } else {
                formData.append($(this).attr("name"), $(this).val());
            }
        }
    );

    $.ajax({
        url: url,
        type: method,
        enctype: enctype,
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {
            $(".backdrop").show();
        },
        success: function (response) {
            $(".backdrop").hide();
            if (response.success) {
                // let fileinput = form.find(".fileinput");
                // console.log(fileinput);
                // if (fileinput.length > 0) {
                //     fileinput.fileinput("clear");
                // }
                // showToaster(response.message, 'Berhasil');
            } else {
                console.log(response);
                // showToaster(response.error, "Error");
            }
            $("body").trigger("_EventAjaxSuccess", [form, response]);
        },
        error: function (response) {
            console.log(response);
            $(".backdrop").hide();
            let errors = response.responseJSON.errors;
            $("body").trigger("_EventAjaxErrors", [form, errors]);
            // for (let key in errors) {
            //     let element = form.find(`[name=${key}]`);
            //     clearValidation(element);
            //     showValidation(element, errors[key][0]);
            // }
        }
    });
});

const formConfirmSubmit = () => {
    $(".form-confirm").submit(function (e) {
        e.preventDefault();
        let form = $(this);
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.value) {
                let formData = new FormData(form[0]);
                let url = form.attr("action");
                let method = form.attr("method");
                let enctype = form.attr("enctype");
                $.ajax({
                    url: url,
                    type: method,
                    enctype: enctype,
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function () {
                        $(".backdrop").show();
                    },
                    success: function (response) {
                        $(".backdrop").hide();
                        if (response.success) {
                            $("body").trigger("_EventAjaxSuccess", [
                                form,
                                response,
                            ]);
                        } else {
                            console.log(response);
                            // showToaster(response.error, "Error");
                        }
                    },
                    error: function (response) {
                        $(".backdrop").hide();
                    },
                });
            }
        });
    });
}

const showAlert = (title, message, type) => {
    Swal.fire({
        title: title,
        text: message,
        icon: type,
        confirmButtonText: "Ok",
    });
}

$(document).ready(function() {
    $(".dt_table").on("draw.dt", function () {
        formConfirmSubmit();
    });
});

const showValidation = (element, message) => {
    $(element).addClass("is-invalid");
    $(element).parent().append(`<div class="invalid-feedback">${message}</div>`);
}

const clearValidation = (element) => {
    $(element).removeClass("is-invalid");
    $(element).parent().find(".invalid-feedback").remove();
}

$('.modal').on('hidden.bs.modal', function (e) {
    let modal = $(this);
    modal.find(".invalid-feedback").remove();
    modal.find(".is-invalid").removeClass("is-invalid");
});

$('.inputimage input[type="file"').change(function () {
    let file = $(this)[0].files[0];
    let reader = new FileReader();
    reader.onload = function () {
        $(".inputimage img").attr("src", reader.result);
    }
    reader.readAsDataURL(file);
});

const openModalByClass = (classname) => {
    $(`.${classname}`).modal("show");
};

