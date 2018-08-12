// var editor = $('#editor').ckeditor({
//     extraPlugins: 'embed,autoembed,image2',
//     height: 300,
//     customConfig:'http://localhost:8000/vendor/unisharp/laravel-ckeditor/config.js',
//     filebrowserImageBrowseUrl: 'http://localhost:8000/laravel-filemanager?type=Images',
//     filebrowserImageUploadUrl: 'http://localhost:8000/laravel-filemanager/upload?type=Images&_token={{csrf_token()}}',
//     filebrowserBrowseUrl: 'http://localhost:8000/laravel-filemanager?type=Files',
//     filebrowserUploadUrl: 'http://localhost:8000/laravel-filemanager/upload?type=Files&_token={{csrf_token()}}'
// });



/*tinymce.init({
 selector: '.editor',
 theme: 'modern',
 height: 400,
 plugins: 'table link image media',
 menubar: 'edit format table insert media',
 toolbar: 'undo redo | styleselect | bold italic underline| alignleft alignright aligncenter alignjustify | fontselect fontsizeselect | cut copy paste | bullist numlist | indent outdent | table | image',
 image_prepend_url: "http://mtftravex.co.id/public/img/upload/",
 image_advtab: true,
 style_formats: [
 {title: 'Image Left', selector: 'img', styles: {
 'float' : 'left',
 'margin': '0 10px 0 10px'
 }},
 {title: 'Image Right', selector: 'img', styles: {
 'float' : 'right',
 'margin': '0 10px 0 10px'
 }}
 ]

 });*/

function notificationMessage(message, type) {
    toastr.options.positionClass = "toast-top-full-width";
    onclick:null;
    toastr.options.closeButton = true;
    toastr.options.showDuration = "300";
    toastr.options.hideDuration = "1000";
    toastr.options.timeOut = "5000";
    toastr.options.extendedTimeOut = "1000";
    toastr.options.showEasing = "swing";
    toastr.options.hideEasing = "linear";
    toastr.options.showMethod = "slideDown";
    toastr.options.hideMethod = "slideUp";
    toastr[type](message, type);
}

function runWaitMe(renderEffect, effect, text) {
    $(renderEffect).waitMe({
        effect: effect,
        text: text,
        bg: 'rgba(255,255,255,0.7)',
        color: '#000',
        maxSize: '',
        onClose: function () {
        }
    });
}

function createSelect2(url,element,token) {
    $.ajax({
        url:url,
        method:"GET",
        beforeSend:function (xhr) {
            xhr.setRequestHeader("Authorization","Bearer "+token);
        },
        success:function (response) {
            console.log(response);
            $('#'+element).children('option:not(:first)').remove().end();
            $('#'+element).select2({
                data: response.result
            });
        }
    });
}

function popUpConfirmation(url,tableId,message,msgProcess,msgSuccess,token) {
    swal({
        title: message,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then(function () {
        runWaitMe('body','ios',msgProcess);
        $.ajax({
            url:url,
            method: "POST",
            data:{
                _token:token
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('body').waitMe('hide');
                notificationMessage(errorThrown, 'error');
                if(tableId !=''){
                    $('#'+tableId).bootgrid('reload');
                }
            },
            success: function (s) {
                $('body').waitMe('hide');
                notificationMessage(msgSuccess,'success');
                if(tableId ==''){
                    location.reload();
                }else{
                    $('#'+tableId).bootgrid('reload');
                }

            }
        })
    })
}

// function popUpConfirmation(id,url,tableId,message,msgProcess) {
//     BootstrapDialog.show({
//         title: 'Confirmation Dialog',
//         message: message,
//         cssClass: 'confirmation-dialog',
//         draggable: true,
//         buttons: [{
//             label: 'Yes',
//             icon: 'glyphicon glyphicon-send',
//             cssClass: 'btn-primary',
//             autospin: true,
//             action: function (dialog) {
//                 runWaitMe('.page-container','win8_linear', msgProcess);
//                 $.ajax({
//                     url: url,
//                     type: "GET",
//                     error: function (XMLHttpRequest, textStatus, errorThrown) {
//                         dialog.close();
//                         $('.page-container').waitMe('hide');
//                         notificationMessage(errorThrown, 'error');
//                     },
//                     success: function (s) {
//                         dialog.close();
//                         $('.page-container').waitMe('hide');
//                         $('#'+tableId).bootgrid('reload');
//                     },
//                     complete: function () {
//                         dialog.close();
//                     }
//                 });
//             }
//         }, {
//             label: 'Cancel',
//             action: function (dialog) {
//                 dialog.close();
//             }
//         }]
//     });
// }
