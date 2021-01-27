<div class="container">
    <div class="row mt-3 mb-3">
        <div class="col-md-6 vspace-1">
            <div class="search-sort-block">
                <div class="menu-icon">
                    <div class="art-icon hover" onclick="go('<?php echo $url['date']; ?>');">query_builder</div><div>Time</div>
                </div>
                <div class="menu-icon">
                    <div class="art-icon hover" onclick="go('<?php echo $url['update']; ?>');">update</div><div>update</div>
                </div>
                <div class="menu-icon">
                    <div class="art-icon hover" onclick="go('<?php echo $url['views']; ?>');">laptop</div><div>views</div>
                </div>
                <div class="menu-icon">
                    <div class="art-icon hover" onclick="go('<?php echo $url['sort']; ?>');">import_export</div><div>order</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 search-box-block mt-3 mb-3 mt-md-0 mb-md-0">
            <form action="javascript:artSearch('<?php echo base_url(); ?>editor/admin/authors/');" class="cross-center">
                <input type="text" id="art-search-field" class="art-search-box space-left" placeholder="Author Name" style="margin-left: auto;">
                <i class="art-icon hover" onclick="artSearch('<?php echo base_url(); ?>editor/admin/authors/');">search</i>
            </form>
        </div>
    </div>
    <div class="row">
        <?php foreach($rows as $row) {
            if($row['picture']) $author_picture = base_url().$this->config->item('uploadir').'/authors/'.$row['author-id'].'/tmb_'.$row['picture'];
            else $author_picture = base_url().'images/person.svg';
        ?>
            <div class="col-md-6 col-lg-3">
                <div id="<?php echo $row['author-id']; ?>" class="search-result-card space-bottom">
                    <img class="result-image hover" onclick="go('<?php echo base_url(); ?>editor/admin/view-author-profile/<?php echo $row['author-id']; ?>');" src="<?php echo $author_picture; ?>">
                    <h5 class="vspace"><?php echo $row['name']; ?></h5>
                    <h6>Joined</h6>
                    <h6><?php echo dateFormat($row['d_n_t'],'d M Y');?></h6>
                    <div class="search-result-card-menu-box center">
                        <div class="menu-icon hover" onclick="go('<?php echo base_url(); ?>editor/admin/view-author-profile/<?php echo $row['author-id']; ?>')">
                            <div class="art-icon">launch</div><div>open</div>
                        </div>
                        <div class="menu-icon hover" onclick="go('<?php echo base_url(); ?>editor/admin/edit-author-profile/<?php echo $row['author-id']; ?>');">
                            <div class="art-icon">edit</div><div>edit</div>
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