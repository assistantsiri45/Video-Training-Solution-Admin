<div id="modal-choose-rulebook" class="modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Choose Rulebook</h5>
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
                <button type="button" class="btn btn-info btn-refresh-rulebook" id="btn-refresh-rulebook">Refresh</button>
                <button type="button" class="btn btn-primary btn-choose-rulebook" id="btn-choose-rulebook" disabled>Choose Rulebook</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script id="choose-rulebook-modal">
        $(function () {
            'use strict';

            var selectedRulebook = '';
            let $modal = $('#modal-choose-rulebook');

            $modal.on('show.bs.modal', function (e) {
                $('#jstree').jstree({
                    'core': {
                        'multiple' : false,
                        'data': function (node, cb) {
                            var $tree = this;

                            var path = node.id === '#' ? '' : node.id;

                            $.ajax({
                                url: '{{ route('api.s3videos.folder.index') }}',
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

                $('#jstree').jstree(true).refresh(); // refresh the tree after opening as tree loads only once

                $('#jstree').on("changed.jstree", function (e, data) {
                    if(data?.node){
                        const parent = data.node.parent != '#' ? data.node.parent + '/' : '/';
                        const text = data.node.text;
                        selectedRulebook = parent + text;
                        $modal.find('.btn-choose-rulebook').prop('disabled', false);
                    }
                });

            });

            $modal.find('.btn-choose-rulebook').click(function (e) {
                e.preventDefault();
                $modal.trigger('rulebook_choose', [selectedRulebook]);
                $modal.modal('hide');
            });
            $modal.find('.btn-refresh-rulebook').click(function (e) {
                e.preventDefault();
                $('#jstree').jstree(true).refresh(); 
            });
        });
    </script>
@endpush