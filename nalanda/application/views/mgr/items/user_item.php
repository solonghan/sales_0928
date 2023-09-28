<tr data-id="<?= $item['id'] ?>">
    <td><?= $item['id'] ?></td>
    <td><?= $item['username'] ?></td>
    <td><?= $item['atid'] ?></td>
    <td><? switch ($item['occupation']) {
            case 'student':
                echo '學生';
                break;
            case 'office_work':
                echo '上班族';
                break;
            case 'freelancer':
                echo '自由工作者';
                break;
            case 'teleworker':
                echo '遠距工作者';
                break;
            case 'merchant':
                echo '商家';
                break;
            case 'other':
                echo '其他';
                break;
        } ?></td>
    <td><?= $item['mobile'] ?></td>
    <td>
        <? if ($item['name_verify'] == 'verified') : ?>
            <span class="label" style="background-color: #5cb85c;">已認證</span>
        <? else : ?>
            <span class="label" style="background-color: #e23210;">未認證</span>
        <? endif ?>
    </td>
    <td>
        <input type="checkbox" class="status_switcher" data-size="mini" data-on-color="success" data-off-color="danger" <?= ($item['is_disable'] == "0") ? ' checked' : '' ?>>
    </td>
    <td>
        <button class="btn btn-info btn-xs" onclick="location.href='<?= base_url() ?>mgr/user/detail/<?= $item['id'] ?>';" data-toggle="tooltip" data-original-title="查看用戶檔案"><i class="fa fa-fw fa-info-circle"></i></button>
        <button class="btn btn-primary btn-xs" onclick="location.href='<?= base_url() ?>mgr/mission/add/<?= $item['id'] ?>';" data-toggle="tooltip" data-original-title="發送官方任務通知"><i class="fa fa-fw ti-comment"></i></button>
    </td>
</tr>