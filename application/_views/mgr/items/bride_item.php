<tr data-id="<?=$item['id'] ?>">
    <td><?=$item['id'] ?></td>
    <td><?=$item['title']?><br></td>
    <td><?=$item['sub_title'] ?></td>
    <td><img src="<?=base_url().$item['cover_img']?>" width="40" height="60"></td>
    <td class="td_left">
        <span>Total row : <?=$item['row_num']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br></span>
        <?php $cou = 0; ?>
        <?php foreach ($item['imgs'] as $value): ?>
        <?php $cou++; ?>
        <span>第<?=$cou?>列使用<?=$value['type']?><br></span>
        <?php endforeach; ?>
    </td>
    <td><?=$item['create_date'] ?></td>
    <td>
        <!-- edit -->
        <button onclick="location.href='<?=base_url()."mgr/bride_mgr/edit/".$item['id'] ?>'" class="btn btn-primary btn-xs" data-toggle="tooltip" data-original-title="編輯"><span class="fa fa-fw ti-pencil"></span></button>
        <!-- delete -->
        <button class="btn btn-danger btn-xs del-btn pull-right" data-toggle="tooltip" data-original-title="刪除"><span class="fa fa-fw fa-minus-square-o"></span></button>
    </td>
</tr>