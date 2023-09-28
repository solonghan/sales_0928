<div class="form-group">
      <label for="input-text" class="col-sm-2 control-label">
      <?php
      echo $item[0];
      if ($item[4])
            echo "<span class='text text-danger'>*</span>";
      ?>
      </label>
      <div class="col-sm-10">
            <input data-multi="false" data-ratio="<?= $item[6] ?>" data-related="<?= $item[1] ?>" data-folder="<?=$item[7] ?>" class="img_upload" type="file" id="imgupload_<?= $item[1] ?>" style="display: none;" accept="image/*">
            <? if (isset($can_edit) && ( ! $can_edit)): ?>
            <? else: ?>
            <button type="button" class="btn btn-sm btn-info" onclick="imgupload_<?= $item[1] ?>.click();">選擇照片</button>
            <button id="delphoto_<?= $item[1] ?>" type="button" class="btn btn-sm btn-danger" onclick="delete_photo('<?= $item[1] ?>');" <?=($item[3] == "") ? ' style="display:none;"' : "" ?>>刪除照片</button>
            <? endif; ?>
            <input type="hidden" name="<?= $item[1] ?>" id="<?= $item[1] ?>" value="<?= $item[3] ?>">
            <div id="img_<?= $item[1] ?>" style="width: 256px; margin-top: 6px; background-color: #FFF; border:1px solid #DDD; padding: 2px; border-radius: 2px;<?= ($item[3] == "") ? ' display:none;' : "" ?>">
                  <img src="<?= ($item[3] != "") ? ((strpos($item[3], "http") !== FALSE) ? $item[3] : base_url() . $item[3]) : "" ?>" style="width: 250px;">
            </div>
            <?php if ($row === FALSE): ?>
            <button type="button" class="btn btn-sm btn-danger pull-right" onclick="del_grp('<?= $del_grp_id ?>');" <?=($item[3] == "") ? ' "' : "" ?>>刪除整列</button>
            <?php endif; ?>
      </div>
</div>