<div id="modal-choose-folder" class="modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Choose Folder</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <div id="jstree"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-choose-folder" id="btn-choose-folder" disabled>Choose File</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script id="choose-folder-modal">
        $(function () {
            'use strict';

            var selectedFolder = '';
            let $modal = $('#modal-choose-folder');

            $modal.on('show.bs.modal', function (e) {
                $('#jstree').jstree({
                    'core': {
                        'multiple' : false,
                        'data': function (node, cb) {
                            var $tree = this;

                            var path = node.id === '#' ? '' : node.id;

                            $.ajax({
                                url: '{{ route('api.videos.folder.index') }}',
                                type: 'GET',
                                dataType: 'json',
                                data: {
                                    path: path
                                },
                                success: function (response) {
                                    var contents = response.data;
                                    contents = $.map(contents, function (file, index) {
                                        return {
                                            id: file.path,
                                            text: file.name,
                                            icon: file.type == 'folder' ? 'far fa-folder' : 'far fa-file',
                                            children: file.type == 'folder',
                                            state: {
                                                disabled: file.type == 'folder'
                                            }
                                        }
                                    });
                                    cb.call($tree, contents);
                                },
                                error: function () {
                                    cb.call($tree, []);
                                }
                            });
                        }
                    }
                });

                $('#jstree').on("changed.jstree", function (e, data) {
                    selectedFolder = data.selected[0];
                    $modal.find('.btn-choose-folder').prop('disabled', false);
                });

            });

            $modal.find('.btn-choose-folder').click(function (e) {
                e.preventDefault();
                $modal.trigger('folder_choose', [selectedFolder]);
                $modal.modal('hide');
            });
        });
    </script>
@endpush
