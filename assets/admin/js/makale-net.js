(function ($) {
    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
    };

    tippy('.tippy');

    $('#mn-pagination').pagination({
        pages: $('#mn-pagination').attr('data-count'),
        cssStyle: 'mn-light-theme',
        hrefTextPrefix: window.location.href + '&sayfa=',
        prevText: mnObject.paginationPrev,
        nextText: mnObject.paginationNext,
        currentPage: getUrlParameter('sayfa'),
    });

    $('.mn-select').select2();

    // $('.mn-select').on('select2:select', function (e) {
    //     var currentUrl = window.location.href.split('?')[0],
    //         data = e.params.data;
    //     window.location.href = currentUrl + '?page=' + getUrlParameter('page') + '&kategori=' + data.id;
    // });

    const swalWithBootstrapButtons = Swal.mixin({
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false,
    });

    $('.onizle').on('click', function () {
        var fiyat = $(this).parent().parent().find('.fiyat').text(),
            makaleID = $(this).parent().parent().attr('data-id'),
            wrapper = $(this).parent().parent(),
            guncelBakiye = $('.guncel-bakiye'),
            guncelBakiyeVal = guncelBakiye.find('span');

        guncelBakiye.removeClass('animated heartBeat');

        const swalWithBootstrapButtons = Swal.mixin({
            confirmButtonClass: 'btn btn-success m-r-10',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false,
        });

        Swal({
            html: $(this).parent().find('.preview').html(),
            showCancelButton: true,
            width: 650,
            confirmButtonText: mnObject.swalBuy,
            cancelButtonText: mnObject.swalClose,
            cancelButtonColor: '#f44336',
            confirmButtonColor: '#28a745',
            showCloseButton: true,
            customClass: 'onizle-modal'
        }).then((result) => {
            if (result.value) {
                Swal({
                    showCancelButton: true,
                    confirmButtonText: mnObject.swalBuy,
                    cancelButtonText: mnObject.swalClose,
                    cancelButtonColor: '#f44336',
                    confirmButtonColor: '#28a745',
                    showLoaderOnConfirm: true,
                    html: mnObject.swalBakiyeOnayBefore + '<strong> ' + fiyat + ' </strong>' + mnObject.swalBakiyeOnayAfter,
                    preConfirm: function () {
                        return new Promise(function (resolve, reject) {
                            var data = {
                                action: 'makale_net_satin_al',
                                ID: makaleID
                            };

                            $.ajax({
                                type: 'POST',
                                url: mnObject.ajaxUrl,
                                data: data,
                                dataType: 'json',
                                beforeSend: function (xhr) {

                                },
                                error: function (request, status, error) {
                                    //reject("error message")
                                },
                                success: function (response) {
                                    Swal({
                                        title: response.message,
                                        html: response.html,
                                        type: response.type,
                                        confirmButtonText: mnObject.swalOk,
                                        confirmButtonColor: '#28a745',
                                    });

                                    if (response.type === 'success') {
                                        guncelBakiye.addClass('animated heartBeat');
                                        guncelBakiyeVal.text(response.guncelBakiye);
                                        wrapper.addClass('mn-disabled');
                                    }
                                },
                                complete: function () {

                                },
                            }); // Ajax end
                        })
                    },
                    allowOutsideClick: false
                });
            } else if (
                // Read more about handling dismissals
                result.dismiss === Swal.DismissReason.cancel
            ) {
                // Close modal
            }
        })
    });

    $('.satin-al').on('click', function () {
        var fiyat = $(this).parent().parent().find('.fiyat').text(),
            makaleID = $(this).parent().parent().attr('data-id'),
            wrapper = $(this).parent().parent(),
            guncelBakiye = $('.guncel-bakiye'),
            guncelBakiyeVal = guncelBakiye.find('span');

        guncelBakiye.removeClass('animated heartBeat');

        Swal({
            showCancelButton: true,
            confirmButtonText: mnObject.swalBuy,
            cancelButtonText: mnObject.swalClose,
            cancelButtonColor: '#f44336',
            confirmButtonColor: '#28a745',
            showLoaderOnConfirm: true,
            html: mnObject.swalBakiyeOnayBefore + '<strong> ' + fiyat + ' </strong>' + mnObject.swalBakiyeOnayAfter,
            preConfirm: function () {
                return new Promise(function (resolve, reject) {
                    var data = {
                        action: 'makale_net_satin_al',
                        ID: makaleID
                    };

                    $.ajax({
                        type: 'POST',
                        url: mnObject.ajaxUrl,
                        data: data,
                        dataType: 'json',
                        beforeSend: function (xhr) {

                        },
                        error: function (request, status, error) {
                            //reject("error message")
                        },
                        success: function (response) {
                            Swal({
                                title: response.message,
                                html: response.html,
                                type: response.type,
                                confirmButtonText: mnObject.swalOk,
                                confirmButtonColor: '#28a745',
                            });

                            if (response.type === 'success') {
                                guncelBakiye.addClass('animated heartBeat');
                                guncelBakiyeVal.text(response.guncelBakiye);
                                wrapper.addClass('mn-disabled');
                            }
                        },
                        complete: function () {

                        },
                    }); // Ajax end
                })
            },
            allowOutsideClick: false
        });
    });

    $('.alinan_hazir_onizle').on('click', function () {
        var makaleID = $(this).parent().parent().attr('data-id'),
            wrapper = $(this).parent().parent(),
            makaleIcerik = $(this).parent().find('.preview').html(),
            makaleBaslik = $(this).parent().parent().find('.onizle-baslik').text(),
            userOptions = '',
            type = $(this).attr('data-type');

        $.each(users, function (i, j) {
            userOptions += '<option value="' + i + '">' + j + '</option>';
        });

        const swalWithBootstrapButtons = Swal.mixin({
            confirmButtonClass: 'btn btn-success m-r-10',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false,
        });

        Swal({
            html: $(this).parent().find('.preview').html(),
            showCancelButton: true,
            width: 650,
            confirmButtonText: mnObject.swalPublish,
            cancelButtonText: mnObject.swalClose,
            cancelButtonColor: '#f44336',
            confirmButtonColor: '#28a745',
            showCloseButton: true,
            customClass: 'onizle-modal'
        }).then((result) => {
            if (result.value) {
                Swal({
                    showCancelButton: true,
                    confirmButtonText: mnObject.swalPublish,
                    cancelButtonText: mnObject.swalClose,
                    cancelButtonColor: '#f44336',
                    confirmButtonColor: '#28a745',
                    showLoaderOnConfirm: true,
                    html: '<strong>"' + makaleBaslik + '"</strong> ' + mnObject.swalPublishOnayAfter +
                        '<select class="swal2-select status" name="status" style="display: flex;">' +
                        '<option value="publish">' + mnObject.swalPublishNow + '</option>' +
                        '<option value="draft">' + mnObject.swalDraft + '</option>' +
                        '</select>' +

                        '<select class="swal2-select author" name="author" style="display: flex;">' +
                        userOptions +
                        '</select>',
                    input: 'select',
                    inputOptions: cats,
                    inputPlaceholder: mnObject.swalSelectCat,
                    allowOutsideClick: false,
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                            var data = {
                                action: 'makale_net_makale_yayimla',
                                ID: makaleID,
                                baslik: makaleBaslik,
                                icerik: makaleIcerik,
                                cat: value,
                                status: $('.status').val(),
                                author: $('.author').val(),
                                type: type
                            };

                            if (value === '') {
                                resolve(mnObject.swalMustSelectCat)
                            } else {
                                $.ajax({
                                    type: 'POST',
                                    url: mnObject.ajaxUrl,
                                    data: data,
                                    dataType: 'json',
                                    beforeSend: function (xhr) {

                                    },
                                    error: function (request, status, error) {
                                        //reject("error message")
                                    },
                                    success: function (response) {
                                        if (response.type === 'success') {
                                            Swal({
                                                showCancelButton: true,
                                                cancelButtonText: mnObject.swalClose,
                                                cancelButtonColor: '#f44336',
                                                title: response.message,
                                                html: response.html,
                                                type: response.type,
                                                confirmButtonText: response.button,
                                                confirmButtonColor: '#28a745',
                                                allowOutsideClick: false,
                                            }).then((result) => {
                                                if (result.value) {
                                                    window.location.href = response.url;
                                                } else {
                                                    location.reload();
                                                }
                                            });
                                        } else {
                                            Swal({
                                                title: response.message,
                                                type: response.type,
                                                confirmButtonText: mnObject.swalClose,
                                                confirmButtonColor: '#f44336',
                                            });
                                        }
                                    },
                                    complete: function () {

                                    },
                                }); // Ajax end
                            }
                        })
                    },
                });
            } else if (
                // Read more about handling dismissals
                result.dismiss === Swal.DismissReason.cancel
            ) {
                // Close modal
            }
        })
    });

    $('.alinan_hazir_satin-al').on('click', function () {
        var makaleID = $(this).parent().parent().parent().attr('data-id'),
            wrapper = $(this).parent().parent(),
            makaleIcerik = $(this).parent().parent().find('.preview').html(),
            makaleBaslik = $(this).parent().parent().parent().find('.onizle-baslik').text(),
            userOptions = '',
            type = $(this).attr('data-type');

        $.each(users, function (i, j) {
            userOptions += '<option value="' + i + '">' + j + '</option>';
        });

        Swal({
            showCancelButton: true,
            confirmButtonText: mnObject.swalSend,
            cancelButtonText: mnObject.swalClose,
            cancelButtonColor: '#f44336',
            confirmButtonColor: '#28a745',
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            html: 
                '<strong>"' + makaleBaslik + '"</strong> ' + mnObject.swalPublishOnayAfter +
                '<select class="swal2-select status" name="status" style="display: flex;">' +
                '<option value="publish">' + mnObject.swalPublishNow + '</option>' +
                '<option value="draft">' + mnObject.swalDraft + '</option>' +
                '</select>' +

                '<select class="swal2-select author" name="author" style="display: flex;">' +
                userOptions +
                '</select>',
            input: 'select',
            inputOptions: cats,
            inputPlaceholder: mnObject.swalSelectCat,
            inputValidator: (value) => {
                return new Promise((resolve) => {
                    var data = {
                        action: 'makale_net_makale_yayimla',
                        ID: makaleID,
                        baslik: makaleBaslik,
                        icerik: makaleIcerik,
                        cat: value,
                        status: $('.status').val(),
                        author: $('.author').val(),
                        type: type
                    };

                    if (value === '') {
                        resolve(mnObject.swalMustSelectCat)
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: mnObject.ajaxUrl,
                            data: data,
                            dataType: 'json',
                            beforeSend: function (xhr) {

                            },
                            error: function (request, status, error) {
                                //reject("error message")
                            },
                            success: function (response) {
                                if (response.type === 'success') {
                                    Swal({
                                        showCancelButton: true,
                                        cancelButtonText: mnObject.swalClose,
                                        cancelButtonColor: '#f44336',
                                        title: response.message,
                                        html: response.html,
                                        type: response.type,
                                        confirmButtonText: response.button,
                                        confirmButtonColor: '#28a745',
                                        allowOutsideClick: false,
                                    }).then((result) => {
                                        if (result.value) {
                                            window.location.href = response.url;
                                        } else {
                                            location.reload();
                                        }
                                    });
                                } else {
                                    Swal({
                                        title: response.message,
                                        type: response.type,
                                        confirmButtonText: mnObject.swalClose,
                                        confirmButtonColor: '#f44336',
                                    });
                                }
                            },
                            complete: function () {

                            },
                        }); // Ajax end
                    }
                })
            },
        });
    });

    $('.durum-degistir').on('change', function () {
        var durum = $(this).is(':checked') ? 'true' : 'false',
            makaleID = $(this).parent().parent().attr('data-id'),
            type = $(this).attr('data-type');

        var data = {
            action: 'makale_net_ajax_durum_degistir',
            ID: makaleID,
            durum: durum,
            type: type
        };

        $.ajax({
            type: 'POST',
            url: mnObject.ajaxUrl,
            data: data,
            dataType: 'json',
            beforeSend: function (xhr) {

            },
            error: function (request, status, error) {
                //reject("error message")
            },
            success: function (response) {
                const toast = Swal.mixin({
                    toast: true,
                    position: 'center-center',
                    showConfirmButton: false,
                    timer: 3000
                });

                toast({
                    type: response.type,
                    title: response.message
                })
            },
            complete: function () {

            },
        }); // Ajax end
    });

    $(".mn-select-all").click(function () {
        $('.makaleSec').not(this).not(":disabled").prop('checked', this.checked);
    });

    $('.bulkPublish').on('click', function () {
        var makaleID = $(this).parent().parent().attr('data-id'),
            wrapper = $(this).parent().parent(),
            makaleIcerik = $(this).parent().find('.preview').html(),
            makaleBaslik = $(this).parent().parent().find('.onizle-baslik').text(),
            status = $(this).attr('data-status'),
            type = $(this).attr('data-type'),
            userOptions = '';

        $.each(users, function (i, j) {
            userOptions += '<option value="' + i + '">' + j + '</option>';
        });

        var postsObjects = [];

        $('.makaleSec:checked').each(function () {
            postsObjects.push({
                'makaleID': $(this).parent().parent().attr('data-id'),
                'makaleBaslik': $(this).parent().parent().find('.onizle-baslik').text(),
                'makaleIcerik': $(this).parent().parent().find('.preview').html()
            });
        });

        Swal({
            showCancelButton: true,
            confirmButtonText: mnObject.swalSend,
            cancelButtonText: mnObject.swalClose,
            cancelButtonColor: '#f44336',
            confirmButtonColor: '#28a745',
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            title: status === 'publish' ? mnObject.swalBulkPublishOnay : mnObject.swalBulkDraftOnay,
            html: '<select class="swal2-select author" name="author" style="display: flex;">' +
                userOptions +
                '</select>',
            input: 'select',
            inputOptions: cats,
            inputPlaceholder: mnObject.swalSelectCat,
            inputValidator: (value) => {
                return new Promise((resolve) => {
                    var data = {
                        action: 'makale_net_makale_yayimla_bulk',
                        cat: value,
                        posts: postsObjects,
                        status: status,
                        author: $('.author').val(),
                        type: type
                    };

                    if (value === '') {
                        resolve(mnObject.swalMustSelectCat)
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: mnObject.ajaxUrl,
                            data: data,
                            dataType: 'json',
                            beforeSend: function (xhr) {

                            },
                            error: function (request, status, error) {
                                //reject("error message")
                            },
                            success: function (response) {
                                if (response.type === 'success') {
                                    Swal({
                                        showCancelButton: true,
                                        showConfirmButton: false,
                                        cancelButtonText: mnObject.swalClose,
                                        cancelButtonColor: '#f44336',
                                        title: response.message,
                                        html: response.html,
                                        type: response.type,
                                        allowOutsideClick: false,
                                    }).then((result) => {
                                        if (result.value) {
                                            window.location.href = response.url;
                                        } else {
                                            location.reload();
                                        }
                                    });
                                } else {
                                    Swal({
                                        title: response.message,
                                        type: response.type,
                                        confirmButtonText: mnObject.swalClose,
                                        confirmButtonColor: '#f44336',
                                    });
                                }
                            },
                            complete: function () {

                            },
                        }); // Ajax end
                    }
                })
            },
        });
    });

    $('.bulkStatus').on('click', function () {
        var status = $(this).attr('data-status'),
            type = $(this).attr('data-type');

        var postsObjects = [];

        $('.makaleSec:checked').each(function () {
            postsObjects.push({
                'makaleID': $(this).parent().parent().attr('data-id'),
            });
        });

        Swal({
            showCancelButton: true,
            confirmButtonText: mnObject.swalYes,
            cancelButtonText: mnObject.swalClose,
            cancelButtonColor: '#f44336',
            confirmButtonColor: '#28a745',
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            title: status === 'true' ? mnObject.swalBulkStatusTrueOnay : mnObject.swalBulkStatusFalseOnay,
            inputOptions: cats,
            inputPlaceholder: mnObject.swalSelectCat,
            preConfirm: (value) => {
                return new Promise((resolve) => {
                    var data = {
                        action: 'makale_net_hazir_makale_durum_bulk',
                        status: status,
                        posts: postsObjects,
                        type: type
                    };

                    if (value === '') {
                        resolve(mnObject.swalMustSelectCat)
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: mnObject.ajaxUrl,
                            data: data,
                            dataType: 'json',
                            beforeSend: function (xhr) {

                            },
                            error: function (request, status, error) {
                                //reject("error message")
                            },
                            success: function (response) {
                                if (response.type === 'success') {
                                    Swal({
                                        showCancelButton: true,
                                        showConfirmButton: false,
                                        cancelButtonText: mnObject.swalClose,
                                        cancelButtonColor: '#f44336',
                                        title: response.message,
                                        html: response.html,
                                        type: response.type,
                                        allowOutsideClick: false,
                                    }).then((result) => {
                                        if (result.value) {
                                            window.location.href = response.url;
                                        } else {
                                            location.reload();
                                        }
                                    });
                                } else {
                                    Swal({
                                        title: response.message,
                                        type: response.type,
                                        confirmButtonText: mnObject.swalClose,
                                        confirmButtonColor: '#f44336',
                                    });
                                }
                            },
                            complete: function () {

                            },
                        }); // Ajax end
                    }
                })
            },
        });
    });

    $('#siparisTuru').on('change', function () {
        var info = $('.mn-inline-info');

        if ($(this).val() === '1') {
            info.show();
            info.addClass('animated pulse');
        } else {
            info.hide();
        }
    });

    $('.repeater').repeater({
        isFirstItemUndeletable: true,
        defaultValues: {
            'kelimeSayisi': 200,
            'kullanim': 1
        },
    });

    $('.fake-delete').on('click', function () {
        const toast = Swal.mixin({
            toast: true,
            position: 'center-center',
            showConfirmButton: false,
            timer: 3000
        });

        toast({
            type: 'error',
            title: mnObject.swalCantDeleteFirst
        })
    });

    $('.bulk-baslik').on('click', function () {
        Swal.fire({
            title: mnObject.swalBulkTitle,
            input: 'text',
            inputPlaceholder: mnObject.swalBulkTitlePlaceholder,
            showCancelButton: true,
            confirmButtonText: mnObject.swalApply,
            confirmButtonColor: '#28a745',
            cancelButtonText: mnObject.swalClose,
            cancelButtonColor: '#f44336',
            showLoaderOnConfirm: true,
            inputValidator: (value) => {
                return new Promise((resolve) => {
                    if (value === '') {
                        resolve(mnObject.swalMustTitle)
                    } else {
                        $('input#baslik').val(value);
                        resolve();
                    }
                })
            },
        });
    });

    $('.bulk-kelime-sayisi').on('click', function () {
        var arr = {};

        $("#kelime option").each(function () {
            arr[$(this).val()] = $(this).text();
        });
        Swal.fire({
            title: mnObject.swalBulkKelimeSayisiTitle,
            input: 'select',
            inputOptions: arr,
            showCancelButton: true,
            confirmButtonText: mnObject.swalApply,
            confirmButtonColor: '#28a745',
            cancelButtonText: mnObject.swalClose,
            cancelButtonColor: '#f44336',
            showLoaderOnConfirm: true,
            inputValidator: (value) => {
                return new Promise((resolve) => {
                    $('select#kelime').val(value);
                    resolve();
                })
            },
        });
    });

    $('.bulk-anahtar-kelime').on('click', function () {
        Swal.fire({
            title: mnObject.swalBulkAnahtarKelimeTitle,
            input: 'text',
            inputPlaceholder: mnObject.swalBulkAnahtarKelimePlaceholder,
            showCancelButton: true,
            confirmButtonText: mnObject.swalApply,
            confirmButtonColor: '#28a745',
            cancelButtonText: mnObject.swalClose,
            cancelButtonColor: '#f44336',
            showLoaderOnConfirm: true,
            inputValidator: (value) => {
                return new Promise((resolve) => {
                    if (value === '') {
                        resolve(mnObject.swalMustAnahtarKelime)
                    } else {
                        $('input#anahtarKelime').val(value);
                        resolve();
                    }
                })
            },
        });
    });

    $('.bulk-kullanim').on('click', function () {
        var arr = {};

        $("#kullanim option").each(function () {
            arr[$(this).val()] = $(this).text();
        });
        Swal.fire({
            title: mnObject.swalBulkKullanimTitle,
            input: 'select',
            inputOptions: arr,
            showCancelButton: true,
            confirmButtonText: mnObject.swalApply,
            confirmButtonColor: '#28a745',
            cancelButtonText: mnObject.swalClose,
            cancelButtonColor: '#f44336',
            showLoaderOnConfirm: true,
            inputValidator: (value) => {
                return new Promise((resolve) => {
                    $('select#kullanim').val(value);
                    resolve();
                })
            },
        });
    });

    $('.tag-link').live('click', function () {
        var tagsInput = $(this).parent().find('#anahtarKelime'),
            linkler = $(this).parent().parent().find('#linkler'),
            button = $(this),
            cleanInput = tagsInput.val().replace(/ \,/g, ',').replace(/\, /g, ','),
            inputArray = cleanInput.split(',');

        //console.log(inputArray);

        if (tagsInput.val() !== '') {
            var html = '<div class="tag-links-wrapper">';

            $.each(inputArray, function (i, j) {
                html += '<p>';
                html += '<label for="tag-' + (i + 1) + '"><strong>' + (i + 1) + ': ' + j + '</strong></label>';
                html += '<input type="text" name="link[]" id="tag-' + (i + 1) + '" placeholder="' + mnObject.swalTagLinkPlaceholder + '">';
                html += '</p>';
            });

            html += '<div class="mn-alert-info">' + mnObject.swalTagLinksInfo + '</div>';
            html += '</div>';

            //console.log(html);

            Swal.fire({
                title: mnObject.swalAddTagLinkTitle,
                html: html,
                showCancelButton: true,
                confirmButtonText: mnObject.swalApply,
                confirmButtonColor: '#28a745',
                cancelButtonText: mnObject.swalClose,
                cancelButtonColor: '#f44336',
                focusConfirm: false,
                preConfirm: () => {
                    var url = [];
                    $('input[name="link[]"]').each(function () {
                        url.push($(this).val().replace(/\s/g, ''));
                    });
                    tagsInput.prop("readonly", true);
                    linkler.val(url.join(','));
                },
            });
        } else {
            Swal.fire({
                position: 'center-center',
                type: 'warning',
                title: mnObject.swalMustTagTitle,
                text: mnObject.swalMustTagText,
                showConfirmButton: false,
                timer: 3000
            })
        }
    });

    $('.siparisForm').on('submit', function (e) {
        e.preventDefault();

        $('td').removeClass('mn-validated');

        var form = $(this).serialize(),
            wrapper = $(this);

        $.ajax({
            type: 'POST',
            url: mnObject.ajaxUrl,
            data: form + '&action=makale_net_siparis_ver_fiyat&satinAl=0',
            dataType: 'json',
            beforeSend: function (xhr) {
                wrapper.addClass('mn-loading-div');
            },
            error: function (request, status, error) {
                console.log(request);
                console.log(status);
                console.log(error);
            },
            success: function (response) {
                if (response.type === 'warning') {
                    Swal({
                        showCancelButton: true,
                        cancelButtonText: mnObject.swalClose,
                        cancelButtonColor: '#f44336',
                        confirmButtonText: mnObject.swalConfirm,
                        confirmButtonColor: '#28a745',
                        title: response.title,
                        allowOutsideClick: false,
                        showLoaderOnConfirm: true,
                        type: response.type,
                        html:
                            '<div class="siparis-ozet">' +
                            '<p><span>' + mnObject.swalTeslimTarihi + '</span>' + response.teslimTarihi + '</p>' +
                            '<p><span>' + mnObject.swalFiyati + '</span>' + response.toplamFiyat + '</p>' +
                            '</div>',
                        preConfirm: (value) => {
                            return new Promise((resolve) => {
                                $.ajax({
                                    type: 'POST',
                                    url: mnObject.ajaxUrl,
                                    data: form + '&action=makale_net_siparis_ver_fiyat&satinAl=1',
                                    dataType: 'json',
                                    beforeSend: function (xhr) {

                                    },
                                    error: function (request, status, error) {
                                        console.log(request);
                                        console.log(status);
                                        console.log(error);
                                    },
                                    success: function (response2) {
                                        Swal({
                                            showCancelButton: true,
                                            showConfirmButton: false,
                                            cancelButtonText: mnObject.swalClose,
                                            cancelButtonColor: '#f44336',
                                            confirmButtonText: mnObject.swalConfirm,
                                            confirmButtonColor: '#28a745',
                                            title: response2.title,
                                            allowOutsideClick: false,
                                            showLoaderOnConfirm: true,
                                            type: response2.type,
                                        }).then((result) => {
                                            if (response2.type === 'success')
                                                window.location.href = response2.url;
                                        });
                                    },
                                    complete: function () {

                                    },
                                });
                            })
                        },
                    });
                } else {
                    Swal({
                        showCancelButton: false,
                        confirmButtonText: mnObject.swalOk,
                        confirmButtonColor: '#28a745',
                        title: JSON.parse(response.result)['error_msg'],
                        allowOutsideClick: false,
                        showLoaderOnConfirm: true,
                        type: response.type,
                    });

                    var jsonResult = JSON.parse(response.result)['validation_errors'];
                    //console.log(jsonResult['validation_errors']);
                    $.each(jsonResult, function (i, j) {
                        var input = $('[name="' + i + '"]'),
                            wrapper = input.parent(),
                            alert = wrapper.find('.mn-alert-warning');

                        wrapper.addClass('mn-validated');
                        alert.text(j);
                        //console.log(i + ':' + j);
                    });
                }
            },
            complete: function () {
                wrapper.removeClass('mn-loading-div');
            },
        }); // Ajax end
    });

    $('.arsiv-degistir').on('change', function () {
        var arsiv = $(this).is(':checked') ? 'true' : 'false',
            makaleID = $(this).parent().parent().attr('data-id');

        var data = {
            action: 'makale_net_ajax_arsiv_degistir',
            ID: makaleID,
            arsiv: arsiv,
        };

        $.ajax({
            type: 'POST',
            url: mnObject.ajaxUrl,
            data: data,
            dataType: 'json',
            beforeSend: function (xhr) {

            },
            error: function (request, status, error) {
                //reject("error message")
            },
            success: function (response) {
                const toast = Swal.mixin({
                    toast: true,
                    position: 'center-center',
                    showConfirmButton: false,
                    timer: 3000
                });

                toast({
                    type: response.type,
                    title: response.message
                })
            },
            complete: function () {

            },
        }); // Ajax end
    });

    $('.bulkArsiv').on('click', function () {
        var makaleID = $(this).parent().parent().attr('data-id'),
            arsiv = $(this).attr('data-status');

        var postsObjects = [];

        $('.makaleSec:checked').each(function () {
            postsObjects.push({
                'makaleID': $(this).parent().parent().attr('data-id')
            });
        });

        Swal({
            showCancelButton: true,
            confirmButtonText: mnObject.swalYes,
            cancelButtonText: mnObject.swalClose,
            cancelButtonColor: '#f44336',
            confirmButtonColor: '#28a745',
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            title: arsiv === 'true' ? mnObject.swalBulkArsivOnay : mnObject.swalBulkNoArsivOnay,
            preConfirm: (value) => {
                return new Promise((resolve) => {
                    var data = {
                        action: 'makale_net_hazir_makale_arsiv_bulk',
                        posts: postsObjects,
                        arsiv: arsiv,
                    };

                    if (value === '') {
                        resolve(mnObject.swalMustSelectCat)
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: mnObject.ajaxUrl,
                            data: data,
                            dataType: 'json',
                            beforeSend: function (xhr) {

                            },
                            error: function (request, status, error) {
                                //reject("error message")
                            },
                            success: function (response) {
                                if (response.type === 'success') {
                                    Swal({
                                        showCancelButton: true,
                                        showConfirmButton: false,
                                        cancelButtonText: mnObject.swalClose,
                                        cancelButtonColor: '#f44336',
                                        title: response.message,
                                        html: response.html,
                                        type: response.type,
                                        allowOutsideClick: false,
                                    }).then((result) => {
                                        if (result.value) {
                                            window.location.href = response.url;
                                        } else {
                                            location.reload();
                                        }
                                    });
                                } else {
                                    Swal({
                                        title: response.message,
                                        type: response.type,
                                        confirmButtonText: mnObject.swalClose,
                                        confirmButtonColor: '#f44336',
                                    });
                                }
                            },
                            complete: function () {

                            },
                        }); // Ajax end
                    }
                })
            },
        });
    });
})(jQuery);