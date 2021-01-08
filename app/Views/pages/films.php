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

    <div id="films-modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add new film</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="films-form">
                    <div class="modal-body">
                        <p>Please fill in all fields to add a new movie</p>
                        <div class="form-group mb-3">
                            <label for="films-input-title">Title</label>
                            <input type="text" id="films-input-title" name="title" class="form-change form-control form-averaged" aria-label="Title" value="">
                        </div>
                        <div class="form-group mb-3">
                            <label for="films-input-year">Year Release</label>
                            <input type="text" id="films-input-year" name="year" class="form-change form-control form-averaged" aria-label="Release Year" value="">
                        </div>
                        <div class="form-group mb-3">
                            <label for="films-select-format">Format</label>
                            <select id="films-select-format" class="form-control form-averaged">
                                <option>VHS</option>
                                <option>DVD</option>
                                <option>Blu-Ray</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="films-input-stars">Stars</label>
                            <input type="text" id="films-input-stars" name="stars" class="form-change form-control form-averaged" aria-label="Stars" value="">
                            <small id="actorsHelp" class="form-text text-muted">Use commas to separate</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-save">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="error-modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">This film already added!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="confirmation-modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Are you sure?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" id="deleteFilm" class="btn btn-secondary">Yes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
</main>
