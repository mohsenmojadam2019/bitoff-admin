var defaultHeaders = {
  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
};

$(document).ready(function () {
    $.ajaxSetup({
        headers: defaultHeaders,
        error: function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.status === 403) {
                Swal.fire(
                    'Beep Beep',
                    'You have no enough permission',
                    'warning'
                )
            }
        }
    });
    $('.select2').select2();
    $('[data-toggle="tooltip"]').tooltip();

    $('.confirm-tr').on('click', function () {

      Swal.fire({
        title: 'Are you sure?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        showLoaderOnConfirm: true,
        preConfirm: (login) => {
          return fetch($(this).attr('data-target'), {method: 'POST', headers: defaultHeaders})
          .then(response => {
            return response.json()
          })
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        Swal.fire({
          title: result.value.message ? 'Confirm Failed' : 'Done',
          text: result.value.message || result.value.tx,
          icon: result.value.message ? 'error' : 'success'
        })
      })

    });


    $('.manual-confirm').on('click', function (e) {

      Swal.fire({
        title: 'Enter transaction id',
        input: 'text',
        inputAttributes: {
          autocapitalize: 'off'
        },
        confirmButtonText: 'Submit',
        showLoaderOnConfirm: true,
        inputValidator: (value) => {
          return new Promise((resolve) => {
            if (value) {
              resolve()
            } else {
              resolve('Enter something')
            }
          })
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        if (!result.value) {return;}
        $.ajax({
          url: $(this).attr('data-target'),
          method: 'POST',
          data: {tx_hash: result.value},
          success: function () {
            Swal.fire({
              title: 'Transaction stored',
              icon: 'success'
            }).then((result) => {
              location.reload();
            });
          },
          error: function (err) {
            Swal.fire({
              title: 'Confirmation failed',
              text: err.responseJSON.message,
              icon: 'error'
            });
          }
      });
      })

    })

});
