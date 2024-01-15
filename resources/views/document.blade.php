@push('css')

    <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.css"/>
    @vite('resources/css/document.css')
@endpush

@push('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
    @vite('resources/js/document.js')
@endpush

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="fas fa-tachometer-alt mr-1"></i>
            {{ __('Document') }}
        </h2>
    </x-slot>
    {{--Editor--}}
    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div id="editor-container" class="p-6 text-gray-900 dark:text-gray-100">
                    {{--Input Form for Doc Name--}}
                    <div class="rounded">
                        {{--Save Button--}}
                        <div class="rounded float-right">
                            <button id="save"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-save mr-1"></i>
                                Save
                            </button>
                        </div>
                        {{--Clear Button--}}
                        <div class="rounded float-right mr-2">
                            <button id="clear"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-trash mr-1"></i>
                                Clear
                            </button>
                        </div>
                        {{--Export to PDF--}}
                        <div class="rounded float-right mr-2">
                            <button id="export"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-download mr-1"></i>
                                Export
                            </button>
                        </div>
                        {{--Doc Name Input--}}
                        <label for="doc-title"></label>
                        <input id="doc-title"
                               class="bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100 font-bold py-2 px-4 rounded"
                               name="doc-title"
                               type="text" placeholder="Title">
                        {{--Preview--}}
                        <!-- <div class="rounded float-right mr-2">
                            <button id="preview"
                                    class="bg-blue-700 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-camera mr-1"></i>
                                Preview
                            </button>
                        </div> -->
                    </div>
                    {{--Editor--}}
                    <div id="editor-js"></div>
                </div>
            </div>
        </div>
    </div>

    {{--Docs Table--}}
    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table id="docs-data-table" class="display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Created</th>
                                <th>Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{--Preview Modal--}}
    <div class="modal fade" id="preview-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title mt-1 select-modal-label" id="modal-title-notification"></h6> 

                    <button type="button" class="btn-close text-dark me-1" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fa fa-close" style="font-size:20px"></i></span>
                    </button>
                </div>
                <div class="modal-body" id="preview">
                </div>
                <div class="modal-footer">
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" class="btn bg-secondary text-white" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
