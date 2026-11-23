
document.addEventListener('DOMContentLoaded', function () {
    const textareas = document.querySelectorAll('textarea.js-quill-editor');

    textareas.forEach(function (textarea) {
        if (textarea.dataset.quillInitialized) {
            return;
        }

        textarea.dataset.quillInitialized = 'true';

        const editorContainer = document.createElement('div');
        editorContainer.style.minHeight = '350px';

        textarea.parentNode.insertBefore(editorContainer, textarea);
        textarea.style.display = 'none';

        const quill = new Quill(editorContainer, {
            theme: 'snow',
            placeholder: 'Начните писать статью...',
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    [{ align: [] }],
                    ['blockquote', 'link', 'image'],
                    ['clean']
                ],
                resize: {
                    tools: ['left', 'center', 'right', 'full', 'edit'],
                    parchment: {
                        image: {
                            attribute: ['width'],
                            limit: {
                                minWidth: 100
                            }
                        }
                    }
                }
            }
        });


const toolbar = quill.getModule('toolbar');

toolbar.addHandler('image', function () {
    const input = document.createElement('input');

    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/jpeg,image/png,image/webp,image/gif');
    input.click();

    input.onchange = async function () {
        const file = input.files[0];

        if (!file) {
            return;
        }

        const formData = new FormData();
        formData.append('image', file);

        try {
            const response = await fetch('/admin/blog/upload-image', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (!response.ok) {
                alert(result.error || 'Ошибка загрузки изображения');
                return;
            }

            const range = quill.getSelection(true);

            quill.insertEmbed(range.index, 'image', result.url);
            quill.setSelection(range.index + 1);

        } catch (error) {
            alert('Не удалось загрузить изображение');
            console.error(error);
        }
    };
});


        quill.root.innerHTML = textarea.value;

        const form = textarea.closest('form');

        if (form) {
            form.addEventListener('submit', function () {
                textarea.value = quill.root.innerHTML;
            });
        }
    });
});
