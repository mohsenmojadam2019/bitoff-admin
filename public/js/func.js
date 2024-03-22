classNameErrorSelect2 = "select2-selection--single";
TimeOutActionNextStore = 2000;
buttonLoadingText = 'waiting...';
buttonTextSweetAlert = "ok";
textConfirmDelete = "Are you sure ?";
buttonTextConfirm = "ok";
//for reset modal content and sections
objectId = "";
url = "";


function resetFormInput(elementClick) {
    $(elementClick).closest('form').find('input[type=text],input[type=number],input[type=password],textarea,.hiddenEmpty').val("");
    $(elementClick).closest('form').find('select').val("").trigger("change");
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function startConfig(showProgress = false) {
    $('.help-block').remove();
    $("." + classNameErrorSelect2).css(normalCss);
    $("#progressBar").css('width', 0 + '%');
    $("#progressBar").css('background-color', '#3399ff');
    $("input[type=text],input[type=number],select,textarea").css(normalCss);
    (showProgress) ? $("#progressShow").show() : false;

}

formSelected = null;

function ajaxStore(e) {
    e.preventDefault();
    var elementClick = $(this);
    startConfig();
    var captionButton = $(elementClick).val();
    $(elementClick).val(buttonLoadingText);
    if (typeof CKEDITOR != 'undefined') {
        for (instance in CKEDITOR.instances)
            CKEDITOR.instances[instance].updateElement();
    }
    formSelected = elementClick.closest('form');
    var data = new FormData(formSelected[0]);
    data.append('status', $(elementClick).val());
    $.ajax({
        url: formSelected.attr('action'),
        type: 'POST',
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            $(elementClick).val(captionButton)
            resultResponse(response);
            if (elementClick.hasClass('reset-form')) {
                if (objectId != "")
                    getHttpRequestForModal(url, {book_id: objectId}, {size: 'modal-xl'});

            }

            document.dispatchEvent(new CustomEvent('ajaxStoreOnSuccess', {
                detail: response
            }))
        },
        error: function (xhr) {
            $(".ajaxMessage").html("");
            $(elementClick).val(captionButton)
            defaultProgress();
            errorForms(xhr);

            document.dispatchEvent(new CustomEvent('ajaxStoreOnError', {
                detail: xhr
            }))
        }
    });
}

///////////////////////////////////////////////////////////////////////////////result response
function resultResponse(result) {
    switch (parseInt(result.status)) {
        case 200:
            $("#progressBar").css('background-color', "green");
            alertMessage('success', result.msg);
            if ("url" in result) {
                setTimeout(function () {
                    window.location.href = result.url;
                }, TimeOutActionNextStore);
            } else if ("data" in result) {
                $(".showHtml").html(result.data)
            }
            break;
        case 422:
            alertMessage('error', result.msg);
            defaultProgress();
            break;
    }

}

///////////////////////////////////////////////////////////////////////////////show message
function alertMessage(typeMessage, message) {
    var messageShow = "";
    switch (typeMessage) {
        case 'success' :
            Swal.fire({
                title: '<h4>' + message + '</h4>',
                icon: 'success'
            })
            break;
        case 'error' :
            Swal.fire({
                title: '<h4>' + message + '</h4>',
                icon: 'error'
            })
            break;
    }
    $(".ajaxMessage").html(messageShow);
}

////////////////////////////////////////////////////////////////////////////////default progrees
function defaultProgress() {
    $("#progressBar").css('width', '0');
}

/////////////////////////////////////////////////////////////////////////////////error validation form
function errorForms(xhr) {
    if (xhr.status === 422) {
        alertMessage('error', 'fix form errors');
        var errorsValidation = JSON.parse(xhr.responseText).errors;
        $.each(errorsValidation, function (key, value) {
            var errArray = key.split('.');
            var getElement = "";
            var errorHtml = "<div class='help-block text-danger'>" + value + "</div>";
            if (typeof errArray[1] === 'undefined') {
                getElement = "[name='" + key + "']";
                showHtmlValidation(getElement, errorHtml)
            } else if (!$.isNumeric(errArray[1])) {
                getElement = "[name='" + (errArray[0] + "[" + errArray[1] + "]']");
                showHtmlValidation(getElement, errorHtml)
            } else if ($.isNumeric(errArray[1])) {
                getElement = "[name^='" + errArray[0] + "']";
                showHtmlValidation(getElement, errorHtml, indexArr = errArray[1])
            }
        });
    } else {
        alertMessage('error', xhr.responseJSON.msg ?? xhr.statusText ?? 'Something went wrong');
    }
}

////////////////////////////////////////////////////////////////////////////////show html error validation
function showHtmlValidation(getElement, errorHtml, indexArr = "") {

    if (!$(getElement).hasClass('select2')) {
        if (indexArr != "") {
            formSelected.find(getElement).eq(indexArr).parent().append(errorHtml);
            formSelected.find(getElement).eq(indexArr).css(errorCss)
        } else {
            formSelected.find(getElement).parent().append(errorHtml);
            formSelected.find(getElement).css(errorCss)
        }
    } else {
        formSelected.find(getElement).eq(indexArr).parent().append(errorHtml);
        formSelected.find(getElement).eq(indexArr).parent().find('.' + classNameErrorSelect2).css(errorCss)
    }
}

$(document).on('click', '.timeOutChangePage', function (e) {
    e.preventDefault();
    var hrefLink = $(this).attr('href');
    alertMessage('alert', 'waiting...');
    setTimeout(function () {
        window.location.href = hrefLink;
    }, TimeOutActionNextStore);

});

////////////////////////////////////////////////////////////////////////delete ajax

function ajaxDelete(url, elementDelete) {
    swal({
        title: textConfirmDelete,
        text: "",
        icon: "warning",
        buttons: buttonTextConfirm,
    })
        .then((willDelete) => {
            if (willDelete) {
                var id = elementDelete.closest('tr').attr('data-id');
                var path = url.replace('?', id);
                $.ajax({
                    'url': path,
                    type: 'DELETE',
                    success(response) {
                        swal(response.msg, {
                            icon: "success",
                        });
                        elementDelete.closest('tr').slideUp(1000)
                    }
                });

            }
        });
};

///////////////////////////////////////////////////////////////////////ajax store use
$(document).on('click', '.ajax-form-request', ajaxStore);

////////////////////////////////////////////////////////////////////////http request and get response and put in modal
function getHttpRequestForModal(url, parameter = {}, modalConfig = {}) {
    modalSize = modalConfig.hasOwnProperty('size') ? modalConfig.size : 'modal-lg';
    modalHeader = modalConfig.hasOwnProperty('header') ? modalConfig.header : '';
    createModal({header: modalHeader, size: modalSize});
    $.get(url, parameter, function (response) {
        if (parseInt(response.status) == 100) {
            $(".modal-body").html(response.htmlForModal);
            if ($("#myModal .modal-body").find('.ck-editor').length) {
                CKEDITOR.replace($('.ck-editor').attr('name'), {
                    filebrowserUploadUrl: '/upload_ck'
                });
            }
            $('#myModal').modal({backdrop: 'static', keyboard: false});

            setTimeout(function () {
                $('#myModal').find('.select2').select2()
            }, 1000)
        }

    });


}

/////////////////////////////////////////////////////////////////////////////////modal creator
function createModal(option = {}) {
    modalSize = option.hasOwnProperty('size') ? option.size : 'modal-sm';
    modalHeader = option.hasOwnProperty('header') ? option.header : '';

    if ($("#myModal").length == 0) {
        var modalHtml = '  <div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false">\n' +
            '        <div class="modal-dialog ' + option.size + '">\n' +
            '            <div class="modal-content">\n' +
            '                <div class="modal-header">\n' +
            '                    <h4 class="modal-title">' + option.header + '</h4>\n' +
            '                </div>\n' +
            '                <div class="modal-body">\n' +
            '                </div>\n' +
            '               <div class="modal-footer">\n' +
            '                  <button type="button" class="btn btn-danger" data-dismiss="modal">انصراف</button>\n' +
            '               </div>' +
            '            </div>\n' +
            '        </div>\n' +
            '    </div>';
        $("#createModal").html(modalHtml);
        $("#myModal").modal('toggle');
    } else {
        $("#myModal").find('.modal-dialog').addClass(modalSize);
        $("#myModal").find('.modal-header').html(modalHeader);
        if (!$("#myModal").hasClass("show")) {
            $("#myModal").modal('show');
        }

    }

}

/////////////////////////////////////////////////////////////////////////////////css validation form and create div show error
var errorCss = {"border-width": "1px", "border-color": "#d2322d", "border-style": "solid"}
var normalCss = {"border-width": "1px", "border-color": "#75787D", "border-style": "solid"}

//////////////////////////////////////////////////////////////////////////////// empty content modal next hide modal

$(document).on('hidden.bs.modal', '#myModal', function () {
    $('#myModal .modal-body').html("")
});


$(document).on('keyup', '.number-format', function () {
    var elementKeyUp = $(this);
    elementKeyUp.val(numberFormat(elementKeyUp.val()))

});

/**
 * Format number
 *
 * @param Number
 * @returns {*}
 */
function numberFormat(Number) {
    Number += '';
    Number = Number.replace(',', '');
    Number = Number.replace(',', '');
    Number = Number.replace(',', '');
    Number = Number.replace(',', '');
    Number = Number.replace(',', '');
    Number = Number.replace(',', '');
    x = Number.split('.');
    y = x[0];
    z = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(y))
        y = y.replace(rgx, '$1' + ',' + '$2');
    return y + z;
}



