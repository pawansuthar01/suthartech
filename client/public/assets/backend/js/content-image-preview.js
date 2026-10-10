document.querySelectorAll('input[type="file"][name="image"]').forEach(input => {
    input.addEventListener('change', () => {
        const file = input.files?.[0];
        const preview = input.form?.querySelector('[data-image-preview-output]');
        if (!file || !preview) return;

        const removeImage = input.form.querySelector('[name="remove_image"]');
        if (removeImage) removeImage.checked = false;
        if (preview.dataset.objectUrl) URL.revokeObjectURL(preview.dataset.objectUrl);
        preview.dataset.objectUrl = URL.createObjectURL(file);
        preview.src = preview.dataset.objectUrl;
        preview.hidden = false;
    });
});
