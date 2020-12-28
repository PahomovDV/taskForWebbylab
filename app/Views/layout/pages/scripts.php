<script src="<?=PUBLIC_ROOT?>js/jquery.min.js" type="text/javascript"></script>
<script src="<?=PUBLIC_ROOT?>js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="<?=PUBLIC_ROOT?>js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="<?=PUBLIC_ROOT?>js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        var table = $('#stats-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            order: [[ 1, "asc" ]],
            dom: 'r<"row mb-3"<"col-md-12 col-lg-6"B>><"row"<"col-md-12 col-lg-6"l><"col-md-12 col-lg-6"f>><"row"<"col-12"t>><"row"<"col-md-12 col-lg-6"i><"col-md-12 col-lg-6"p>>',
            buttons: [
                {
                    text :'Add',
                    className: 'btn btn-primary btn-lg btn-add',
                },
                {
                    text :'Upload',
                    className: 'btn btn-primary btn-lg btn-upload',
                },
                {
                    text :'Delete All',
                    className: 'btn btn-primary btn-lg btn-delete-all',
                }
            ],
            ajax: {
                url: "getFilms",
            },
            columns: [
                {data: 'id', name: 'id', searchable: false, orderable: true},
                {data: 'title', name: 'title',searchable: true, orderable: true},
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

        $('.btn-upload').on('click', function(){
            $('#uploadDocument').trigger('click');
        });

        $('.btn-delete-all').on('click', function(){
            $.ajax({
                url: "deleteFilms",
                success: function () {
                    table.ajax.reload();
                }
            });
        });

        $(document).on('click', '.btn-delete', function(){
            $.ajax({
                url: "deleteFilmById",
                data: {
                    id : this.id
                },
                success: function (success) {
                    table.ajax.reload();
                }
            });
        });

        $('#uploadDocument').on('change', function () {
            var fileInput = document.getElementById('uploadDocument');
            var file = fileInput.files[0];
            var formData = new FormData();
            formData.append('file', file);
            $.ajax({
                url: "uploadFilms",
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    table.ajax.reload();
                }
            });
        });

        $('.btn-add').on('click', function () {
            $(".modal").modal("show");
        });

        $('.btn-save').on('click', function () {
            $.ajax({
                url: "addFilm",
                data: {
                    title : $('.title').val(),
                    release_year : $('.release-year').val(),
                    format : $('.format').val(),
                    stars : $('.stars').val(),
                },
                success: function (success) {
                    table.ajax.reload();
                    $(".modal").modal("hide");
                }
            })
        });

    });
</script>