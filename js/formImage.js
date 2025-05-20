const imageInput = document.getElementById('imageInput');
const imagePreview = document.getElementById('imagePreview');

imageInput.addEventListener('change', function () {
    const file = this.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function (e) {
            imagePreview.style.backgroundImage = `url('${e.target.result}')`;
            imagePreview.textContent = '';
        };
        reader.readAsDataURL(file);
    } else {
        imagePreview.style.backgroundImage = '';
        imagePreview.textContent = 'Brak Obrazu';
    }
});
