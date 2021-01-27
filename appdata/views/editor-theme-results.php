<div class="container">
    <div class="row mt-4 mb-4 mt-md-0 mb-md-0">
        <div class="col-12">
            <div class="search-sort-block">
                <div class="menu-icon">
                    <div class="art-icon hover" onclick="go('<?php echo base_url(); ?>editor/admin/install-theme');">add_photo_alternate</div><div>Install</div>
                </div>
                <div class="menu-icon space-left-1">
                    <div class="art-icon hover" onclick="go('<?php echo base_url(); ?>editor/admin/template-validator');">assignment_turned_in</div><div>Validate</div>
                </div>
                <div class="menu-icon">
                    <div class="art-icon hover" onclick="go('<?php echo $url['date']; ?>');">query_builder</div><div>Date</div>
                </div>
                <div class="menu-icon">
                    <div class="art-icon hover" onclick="go('<?php echo $url['update']; ?>');">update</div><div>update</div>
                </div>
                <div class="menu-icon">
                    <div class="art-icon hover" onclick="go('<?php echo $url['sort']; ?>');">import_export</div><div>order</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row space-top">
        <?php foreach($rows as $row) {

            if(!file_exists(base_url().'themes/'.$row['theme-id'].'/'.$row['image']) xor !$row['image'])
            {
                $image = base_url().'themes/'.$row['theme-id'].'/'.$row['image'];
            }
            else $image = '/images/notheme.svg';
            if($row['status'] == 'active') {$power = 'on'; $plug = 'power';}
            else {$power = 'off'; $plug = 'power_off'; }
        ?>
            <div id="<?php echo $row['theme-id']; ?>" class="col-md-4 col-lg-3">
                <div class="search-result-card space-bottom">
                    <a href="<?php echo base_url().'themes/'.$row['theme-id'].'/index'; ?>"><img class="result-image" src="<?php echo $image; ?>"></a>
                    <h5 class="vspace"><?php echo $row['title']; ?></h5>
                    <h6><?php echo dateFormat($row['d_n_t'],'d M Y');?></h6>
                    <div class="search-result-card-menu-box center">
                        <div class="menu-icon hover" onclick="activateTheme('<?php echo base_url(); ?>editor/admin/activate-theme/<?php echo $row['theme-id']; ?>','<?php echo $row['theme-id']; ?>')">
                            <div id="switch-<?php echo $row['theme-id']; ?>" class="art-icon"><?php echo $plug; ?></div><div><?php echo $power; ?></div>
                        </div>
                        <div class="menu-icon hover" onclick="go('<?php echo base_url(); ?>editor/admin/update-theme-template/<?php echo $row['theme-id']; ?>')">
                            <div class="art-icon">edit</div><div>edit</div>
                        </div>
                        <div class="menu-icon hover" onclick="deleteTheme('<?php echo base_url('editor/admin/delete-theme/'.$row['theme-id']); ?>','<?php echo $row['theme-id']; ?>')">
                            <div class="art-icon">delete</div><div>Del</div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="row">
        <div class="col-12 center">
            <span class="black-font"><?php pagi($config); ?></span>
        </div>
    </div>
</div>