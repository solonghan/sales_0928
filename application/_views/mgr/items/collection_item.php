<tr data-id="<?=$item['id'] ?>">
    <td><?=$item['id'] ?></td>
    <td><img src="<?=base_url().$item['cover_img']?>" width="40" height="60"></td>
    <?php $item['collection_imgs'] = json_decode($item['collection_imgs']); ?>
    <?php if ($item['collection_imgs'][0] != ''): ?>
    <td><img src="<?=base_url().$item['collection_imgs'][0]?>" width="40" height="60"></td>
    <?php else: ?>
    <td></td>
    <?php endif; ?>
    <?php if ($item['collection_imgs'][1] != ''): ?>
    <td><img src="<?=base_url().$item['collection_imgs'][1]?>" width="40" height="60"></td>
    <?php else: ?>
    <td></td>
    <?php endif; ?>
    <?php if ($item['collection_imgs'][2] != ''): ?>
    <td><img src="<?=base_url().$item['collection_imgs'][2]?>" width="40" height="60"></td>
    <?php else: ?>
    <td></td>
    <?php endif; ?>
    <?php if ($item['collection_imgs'][3] != ''): ?>
    <td><img src="<?=base_url().$item['collection_imgs'][3]?>" width="40" height="60"></td>
    <?php else: ?>
    <td></td>
    <?php endif; ?>
    <td><?=$item['title2']?><br></td>
    <td><?=$item['title3'] ?></td>
    <td><?=$item['status']?></td>
    <td><?=$item['description']?></td>
    <td><?=$item['information'] ?></td>
    <td><?=$item['create_date'] ?></td>
    <td>
        <!-- edit -->
        <button onclick="location.href='<?=base_url()."mgr/collection_mgr/edit/".$item['id'] ?>'" class="btn btn-primary btn-xs" data-toggle="tooltip" data-original-title="編輯"><span class="fa fa-fw ti-pencil"></span></button>
        <!-- delete -->
        <button class="btn btn-danger btn-xs del-btn pull-right" data-toggle="tooltip" data-original-title="刪除"><span class="fa fa-fw fa-minus-square-o"></span></button>
    </td>
</tr>