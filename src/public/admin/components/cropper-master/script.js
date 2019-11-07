$(document).ready(function () {
    var $canvas = $('#canvas');
    context = $canvas.get(0).getContext('2d');

    $('#img_file').on('change', function () {
        if (this.files && this.files[0]) {
            if (this.files[0].type.match(/^image\//)) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var img = new Image();
                    img.onload = function () {
                        context.canvas.width = img.width;
                        context.canvas.height = img.height;
                        context.drawImage(img, 0, 0);
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataUrl(this.files[0])
            }
        }
    });
});
