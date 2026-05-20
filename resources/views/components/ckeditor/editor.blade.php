@push('head-scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>
@endpush

<div class="mb-4">
    <label for="editor" class="block text-sm font-medium text-gray-700 mb-1.5">Write Note:</label>
    <div class="ckeditor-wrapper rounded-lg overflow-hidden border border-gray-200">
        <textarea name="{{ $name }}" id="editor" rows="10" placeholder="Write here..."
                  class="block w-full px-3 py-2 text-sm text-gray-900 focus:outline-none"></textarea>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function DisallowNestingTables(editor) {
            editor.model.schema.addChildCheck((context, childDefinition) => {
                if (childDefinition.name == 'table' && Array.from(context.getNames()).includes('table')) {
                    return false;
                }
            });
        }
        ClassicEditor.create(document.querySelector('#editor'), {
            extraPlugins: [DisallowNestingTables],
            toolbar: ['heading', 'bold', 'italic', '|', 'link', 'insertTable', 'numberedList', 'bulletedList', '|', 'undo', 'redo'],
            table: {
                toolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
            }
        }).catch(error => {
            console.error(error);
        });
    });
</script>
