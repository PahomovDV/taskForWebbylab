<!--Grid row-->
<main class="py-4">
    <div class="container">
        <input id="uploadDocument" type="file" name="file" style="display: none"/>
        <div id="output"></div>
        <div class="row">
            <div class="col-md-12">
                <table id="stats-table" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Year Release</th>
                        <th>Format</th>
                        <th>Actors</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add new film</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Please fill in all fields to add a new movie</p>
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <span class="input-group-text">Title</span>
                        </div>
                        <input type="text" class="form-change form-control form-averaged title" aria-label="Title" value="" required>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <span class="input-group-text">Year Release</span>
                        </div>
                        <input type="text" class="form-change form-control form-averaged release-year" aria-label="Release Year " value="" required>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <span class="input-group-text">Format</span>
                        </div>
                        <input type="text" class="form-change form-control form-averaged format" aria-label="Format" value="" required>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <span class="input-group-text">Stars</span>
                        </div>
                        <input type="text" class="form-change form-control form-averaged stars" aria-label="Stars" value="" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-save">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</main>
