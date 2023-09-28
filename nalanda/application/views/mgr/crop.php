<!-- Cover Modal -->
<div class="modal fade" id="coverModal" role="dialog" aria-labelledby="modalLabel" tabindex="-1">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">照片</h5>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
      </div>
      <div class="modal-body">
        <div class="img-container">
          <img id="coverImage" src="" alt="cover Image">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">取消</button>
        <button id="coverSave" type="button" class="btn btn-primary" data-dismiss="modal">儲存</button>
      </div>
    </div>
  </div>
</div>
<!-- Cover Modal -->

<link rel="stylesheet" href="dist/cropper.css">
<script src="dist/cropper.js"></script>
<script>
    window.addEventListener('DOMContentLoaded', function () {
    /* crop */
        var coverImage = document.getElementById("coverImage");
        var cropper;
        var related_id;
        var ratio = 6 / 4;

        $(document).on("change", ".img_upload", function(){
            related_id = $(this).attr("data-related");

            ratio = parseFloat($(this).attr("data-ratio"));
            if (ratio <= 0) ratio = 1;

            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $("#coverImage").attr('src', e.target.result);
                    $("#coverImage").width("100%");
                }

                reader.readAsDataURL(input.files[0]);

                $("#coverModal").modal({'backdrop':'static'});
                $("#coverModal").modal("show");
            }
            $(this).val("");
        });

        $('#coverModal').on('shown.bs.modal', function () {
            cropper = new Cropper(coverImage, {
                aspectRatio: ratio,
                movable: false,
                rotatable: false,
                scalable: false,
                zoomable: false,
                zoomOnTouch: false,
                zoomOnWheel: false,
            });
        }).on('hidden.bs.modal', function () {
            cropper.destroy();
        });

        $("#coverSave").on('click', function(event) {
            var result = cropper.getCroppedCanvas();
            $('#getCroppedCanvasModal').modal().find('.modal-body').html(result);

            $.ajax({
                url: "<?=base_url() ?>mgr/dashboard/img_upload",
                data: {
                    imageData: result.toDataURL("image/jpeg")
                },
                type:"POST",
                dataType:'text',
                success: function(msg){
                    $("#img_"+related_id+" img").attr("src", "<?=base_url() ?>"+msg);
                    $("#img_"+related_id).show();
                    $("#"+related_id).val(msg);
                    $("#delphoto_"+related_id).show();
                },
                error:function(xhr, ajaxOptions, thrownError){ 
                    alert("照片上傳發生錯誤"); 
                }
            });

            cropper.destroy();
        });

        /* crop end */
    });
</script>