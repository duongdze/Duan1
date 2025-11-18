document.addEventListener('DOMContentLoaded', function () {
    // Initialize Quill editors
    var quillDesc = new Quill('#editor-description', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                [{
                    'size': ['small', false, 'large', 'huge']
                }],
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                ['link'],
                ['clean']
            ]
        }
    });

    var quillPolicy = new Quill('#editor-policy', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                [{
                    'size': ['small', false, 'large', 'huge']
                }],
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                ['link'],
                ['clean']
            ]
        }
    });

    // Set initial contents from hidden inputs if present
    var inputDesc = document.getElementById('input-description');
    if (inputDesc && inputDesc.value) quillDesc.root.innerHTML = inputDesc.value;
    var inputPolicy = document.getElementById('input-policy');
    if (inputPolicy && inputPolicy.value) quillPolicy.root.innerHTML = inputPolicy.value;

    var form = document.querySelector('form[action="?action=tours/update"]');
    if (form) {
        form.addEventListener('submit', function (e) {
            document.getElementById('input-description').value = quillDesc.root.innerHTML;
            document.getElementById('input-policy').value = quillPolicy.root.innerHTML;
        });
    }

    // Image preview
    var imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function (e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function (event) {
                    var preview = document.createElement('img');
                    preview.src = event.target.result;
                    preview.className = 'form-image-preview';

                    var previewContainer = document.querySelector('#image').previousElementSibling;
                    if (previewContainer) {
                        previewContainer.innerHTML = '';
                        previewContainer.appendChild(preview);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
});