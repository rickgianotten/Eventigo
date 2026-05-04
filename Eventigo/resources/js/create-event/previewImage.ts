const fileInputEl = document.getElementById('image_upload') as HTMLInputElement;
const previewImageEl = document.getElementById('preview_image') as HTMLImageElement;
const previewImageContainer = document.getElementById('preview_image_conainer');
const uploadImageContainer = document.getElementById('upload_image_container');
const deletePreviewImageBtn = document.getElementById('delete_preview_image_btn');

fileInputEl?.addEventListener('change', (e)=>{
    const files = fileInputEl.files;
    if (files?.[0]) {
        previewImageContainer?.classList.remove('hidden');
        uploadImageContainer?.classList.add('hidden');
        const url = URL.createObjectURL(files[0]);
        previewImageEl.src = url;
    }
})
