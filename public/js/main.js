$(document).ready(function() {
    function findFilm(){
        let res;
        $.ajax({
            type: "POST",
            url: root + "findFilm",
            async: false,
            data: {
                title : function () {
                    return $('#films-input-title').val();
                }
            },
            success: function(response) {
                res = response[0];
            },
        });
        return res;
    }

    function addFilm(){
        $.ajax({
            url: root + "addFilm",
            data: {
                title: $('#films-input-title').val(),
                release_year: $('#films-input-year').val(),
                format: $('#films-select-format option:selected').val(),
                stars: $('#films-input-stars').val(),
            },
            success: function (success) {
                table.ajax.reload();
            }
        });
    }

    function deleteFilmById(id){
        $.ajax({
            url: root + "deleteFilmById",
            data: {
                id: id
            },
            success: function (success) {
                $("#confirmation-modal").modal("hide");
                table.ajax.reload();
            }
        });
    }

    function deleteFilms(){
        $.ajax({
            url: root + "deleteFilms",
            success: function () {
                table.ajax.reload();
            }
        });
    }

    $.validator.addMethod("unique", function(value, element) {
        var array = value.split(',').map(function(item) {
            return item.toLowerCase().replace(/^[\w]|\s[\w]|[а-яА-ЯЁё\w]|\s[а-яА-ЯЁё\w]/g, function(letter) {
                return letter.toUpperCase().trim();
            });
        });
        return new Set(array).size === array.length
    }, 'Please enter a unique actors');

    $('#films-form').validate({
        rules : {
            title:{
                required : true,
            },
            year: {
                required : true,
                min : 1890,
                max : 2021,
            },
            stars: {
                required : true,
                unique : true,
            },
            format : {
                required : true,
            }
        },
        errorElement: 'div',
        errorClass: 'alert alert-danger',
        submitHandler: function(form) {
            if(findFilm() === 'false'){
                addFilm();
                $("#films-modal").modal("hide");
            } else {
                $("#films-modal").modal("hide");
                $("#error-modal").modal("show");
            }
        }
    });

    var table = $('#stats-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        searching: true,
        order: [[1, "asc"]],
        dom: 'r<"row mb-3"<"col-md-12 col-lg-6"B>><"row"<"col-md-12 col-lg-6"l><"col-md-12 col-lg-6"f>><"row"<"col-12"t>><"row"<"col-md-12 col-lg-6"i><"col-md-12 col-lg-6"p>>',
        buttons: [
            {
                text: 'Add',
                className: 'btn btn-primary btn-lg btn-add',
            },
            {
                text: 'Upload',
                className: 'btn btn-primary btn-lg btn-upload',
            },
            {
                text: 'Delete All',
                className: 'btn btn-primary btn-lg btn-delete-all',
            }
        ],
        ajax: {
            url: root + "getFilms",
        },
        columns: [
            {data: 'id', name: 'id', searchable: false, orderable: true},
            {data: 'title', name: 'title', searchable: true, orderable: true},
            {data: 'release_year', name: 'release_year', searchable: false, orderable: true},
            {data: 'format', name: 'format', searchable: false, orderable: true},
            {data: 'stars', name: 'stars', searchable: true, orderable: false},
            {
                name: 'delete_button',
                searchable: false,
                orderable: false,
                render: function (data, type, row) {
                    return '<button class="btn btn-primary btn-delete" id="' + row.id + '">DELETE</button>';
                }
            }
        ],
    });

    $(document).on('hidden.bs.modal', '#films-modal',function () {
        $(this).find('form').trigger('reset');
    });

    $(document).on('click', '.btn-delete', function () {
        $("#confirmation-modal").modal("show");
        $('#deleteFilm').attr('data-id', this.id);
    });

    $('#deleteFilm').on('click', function () {
        let id = this.dataset.id;
        deleteFilmById(id);
    });

    $('.btn-upload').on('click', function () {
        $('#uploadDocument').trigger('click');
    });

    $('.btn-delete-all').on('click', function () {
        deleteFilms();
    });

    $('.btn-add').on('click', function () {
        $("#films-modal").modal("show");
    });

    $('#uploadDocument').on('change', function () {
        var fileInput = document.getElementById('uploadDocument');
        var file = fileInput.files[0];
        var formData = new FormData();
        formData.append('file', file);
        $.ajax({
            url: root + "uploadFilms",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                $("#uploadDocument").val('');
                table.ajax.reload();
            }
        });
    });
});