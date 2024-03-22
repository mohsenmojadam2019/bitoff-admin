<script>
 $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /*
    * Show Custom Modal
    */
    function showModal(configModal) {
        var modalSize = ("size" in configModal) ? configModal.size : 'modal-xl';
        var modalBody = ("body" in configModal) ? configModal.body : '';
        var modalTitle = ("title" in configModal) ? configModal.title : '';
        var modalHeader = ("header" in configModal) ? configModal.header : ''

        $('.modal-dialog').addClass(modalSize);
        $('.modal-body').html(modalBody);
        $('.modal-title').html(modalTitle);
        $('.modal-add-heder').html(modalHeader);


        $('#general-modal').modal({backdrop: 'static', keyboard: false});

    }

    /*
    *  Clone By Sample Class
     */
    function cloneHtml(sampleClass) {
        var targetElements = $('.' + sampleClass);

        var lastElements = targetElements.length

        targetElements.eq(lastElements - 1).after("<div class='row " + sampleClass + "'>" + targetElements.html() + "</div>");

        targetElements.eq(lastElements).find(['input[type=text]']).val('');

        targetElements.eq(lastElements).find('textarea').html('');

        targetElements.eq(lastElements).removeClass('hasDatepicker');

        targetElements.eq(lastElements).find(['select']).val('').trigger('change');

    }

    /*
    *  Delete Last Html Row
     */
    function deleteLastHtml(sampleClass) {
        targetElement = $('.' + sampleClass);
        if ($('.' + sampleClass).length > 1) {
            $(targetElement).eq($('.' + sampleClass).length - 1).remove();
        } else {
            errorAlert('این مورد قابل حذف نمی باشد !')
        }
    }


    function successAlert(message) {
        Swal.fire({
            title: '<h4>' + message + '</h4>',
            icon: 'success'
        })
    }

    function errorAlert(message) {
        Swal.fire({
            title: '<h4>' + message + '</h4>',
            icon: 'error'
        })
    }

    function httpGetRequest(url, loading = true, modalLoading = false) {
        return $.get(url).fail(function (error) {
            httpError(error)
        })
    }

    function httpDeleteRequest(url, data = {}) {
        data['_method'] = 'Delete';
        return $.post(url, data).fail(function (error) {
            httpError(error)
            removeModalLoading();
            removeContentLoading();
        });
    }

    function resetForm(submitButton) {
        submitButton.closest('form').find('.text-danger').remove();
    }

    function httpFormPostRequest(submitButton) {
        resetForm(submitButton);

        var data = new FormData($(submitButton).closest('form')[0])
        return $.ajax({
            data: data,
            processData: false,
            contentType: false,
            url: submitButton.closest('form').attr('action'),
            type: 'POST',
            document
        }).fail(function (error) {
            removeModalLoading();
            removeContentLoading();
            httpError(error)
            removeModalLoading();
            removeContentLoading();
            if (htmlButton != "") {
                buttonRemoveLoading(targetButtonLoading)
            }

        })
    }


    function httpError(error) {
        if (error.status === 403) {
            errorAlert('Access Denied !');
        } else if (error.status === 422) {
            errorAlert('fix problem on form ');
            var errors = JSON.parse(error.responseText).errors
            showError(errors);

        } else {
            errorAlert('Server Error');
        }
    }

    /*
    *  Post Ajax Request By Data
     */
    function httpPostRequest(url, data) {
        return $.post(url, data).fail(function (error) {
            removeModalLoading();
            removeContentLoading();
        })
    }

    function showError(errors) {
        $.each(errors, function (key, value) {
            var errArray = key.split('.');
            var htmlElement;

            if (typeof errArray[1] === 'undefined') {
                htmlElement = $("[name='" + key + "']");
                htmlElement.parent().after(createHtmlError(value))
            } else if ($.isNumeric(errArray[1])) {
                htmlElement = $("[name^='" + errArray[0] + "']");
                htmlElement.eq(errArray[1]).parent().after(createHtmlError(value));
            }
        })
    }

    function createHtmlError(descError, errorClass = 'text-danger') {
        return "<div class='" + errorClass + "'>" + descError + "</div>";
    }

    function setModalLoading() {
        var loadHtml = "<div class='overlay d-flex justify-content-center align-items-center'><i class='fas fa-2x fa-sync fa-spin'></i></div>"
        $('.modal-content').prepend(loadHtml)
    }

    function removeModalLoading() {
        $('.modal .overlay').remove();
        $('.overlay').remove();
    }

    function createSelect2() {
        setTimeout(function () {
            $('.select2').select2()
        }, 800);
    }

    function createSelect2Search(url, removeLast = true) {
        var countSelect2 = $('span.select2').length;
        setTimeout(function () {
            if (removeLast) {
                $('span.select2').eq(countSelect2 - 1).remove();
                $('select.select2').eq(countSelect2 - 1).removeClass('select2-hidden-accessible');
                $('select.select2').eq(countSelect2 - 1).html('');
            }

            $('.select2').select2({
                minimumInputLength: 2,
                ajax: {
                    url: url,
                    dataType: 'json',
                    type: "GET",
                    processResults: function (data) {
                        return {
                            results: data.data
                        };
                    }
                }
            })
        }, 500);
    }

    function setContentLoading() {
        var loadHtml = "<div class='overlay dark'><span class='text-warning'>" + "please waiting..." + "</span><i class='fas fa-2x fa-sync fa-spin'></i></div>";
        $('.wrapper').prepend(loadHtml);
    }

    function removeContentLoading() {
        $('.overlay').remove();
    }

    scrollWith = 0;

    function customScroll(scrollClass = 'modal-body', withScroll = 300) {
        scrollWith = scrollWith + withScroll;
        $("." + scrollClass).animate({
            scrollTop: scrollWith
        });
    }


    /**
     * Set number format
     */
    $(document).on('keyup', '.number-format', function () {
        var elementKeyUp = $(this);
        elementKeyUp.val(numberFormat(elementKeyUp.val()))

    });

    /**
     * @param Number
     * @returns {string}
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

    htmlButton = '';
    targetButtonLoading = ''

    function buttonLoading(targetElement) {
        htmlButton = targetElement.html();
        targetButtonLoading = targetElement;
        targetElement.html(" <i class='fa fa-spinner fa-spin'></i>لطفا منتظر بمانید...");
    }

    function buttonRemoveLoading(targetElement) {
        targetElement.html(htmlButton)
    }




    function toPersianNum(num, dontTrim) {

        var i = 0,

            dontTrim = dontTrim || false,

            num = dontTrim ? num.toString() : num.toString().trim(),
            len = num.length,

            res = '',
            pos,

            persianNumbers = typeof persianNumber == 'undefined' ?
                ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'] :
                persianNumbers;

        for (; i < len; i++)
            if ((pos = persianNumbers[num.charAt(i)]))
                res += pos;
            else
                res += num.charAt(i);

        return res;
    }

    function updateQueryStringParameter(uri, key, value) {
        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.match(re)) {
            return uri.replace(re, '$1' + key + "=" + value + '$2');
        }
        else {
            return uri + separator + key + "=" + value;
        }
    }
</script>
