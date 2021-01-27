<div class="container">
    <div class="row mt-3 mb-5">
        <div class="col-md-6">
            <div class="search-sort-block">
                <div class="menu-icon">
                    <div class="art-icon hover" onclick="go('<?php echo $url['date']; ?>');">query_builder</div><div>Date</div>
                </div>
                <div class="menu-icon">
                    <div class="art-icon hover" onclick="go('<?php echo $url['update']; ?>');">update</div><div>Update</div>
                </div>
                <div class="menu-icon">
                    <div class="art-icon hover" onclick="go('<?php echo $url['views']; ?>');">visibility</div><div>Views</div>
                </div>
                <div class="menu-icon">
                    <div class="art-icon hover" onclick="go('<?php echo $url['sort']; ?>');">import_export</div><div>Order</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 search-box-block mt-3 mb-3 mt-md-0 mb-md-0">
            <form action="javascript:artSearch('<?php echo base_url(); ?>editor/admin/articles/');" class="cross-center">
                <input type="text" id="art-search-field" class="art-search-box space-left" placeholder="Search" style="margin-left: auto;">
                <i class="art-icon hover" onclick="artSearch('<?php echo base_url(); ?>editor/admin/articles/');">search</i>
            </form>
        </div>
    </div>
    <hr class="d-none d-md-block"/>
    <?php
    $delay = .1;
    $i = 1;
    foreach($rows as $row)
    {
        if($row['image']) $image = base_url().$this->config->item('uploadir').'page-title-pictures/tmb_'.$row['image'];
        else $image = base_url().'images/nopicture.svg';

        switch($row['status'])
        {
            case 1 : {$status_color = 'inprogress '; $delete_status = $row['page-id']; $icon='radio_button_checked';} break;
            case 2 : {$status_color = 'unpublish ';  $delete_status = $row['page-id']; $icon='radio_button_checked';} break;
            case 3 : {$status_color = 'review ';     $delete_status = $row['page-id']; $icon='radio_button_checked';} break;
            case 4 : {$status_color = 'publish ';    $delete_status = $row['page-id']; $icon='radio_button_checked';} break;
            case 5 : {$status_color = 'highlight ';  $delete_status = 'locked'; $icon = 'home';} break;
        }
        ?>
        <div class="row result-row page-<?php echo $row['page-id']; ?>">
            <div class="col-md-9">
                <div class="row cross-center">
                    <div class="col-md-3 col-lg-4 center">
                        <img onclick="go('<?php echo base_url(); ?>editor/admin/edit-article/<?php echo slug($row['title']).'/'.$row['page-id']; ?>');"
                             id="art-btn-<?php echo $row['page-id']; ?>" class="article-search-result-image hover border-color-<?php echo $status_color ;?>"
                             src="<?php echo $image; ?>">
                    </div>
                    <div class="col-md-9 col-lg-8">
                        <div class="mt-4 mb-4 mt-md-0 mb-md-0 text-center text-md-left">
                            <a class="hover" href="<?php echo base_url(); ?>editor/admin/edit-article/<?php echo slug($row['title']).'/'.$row['page-id'];?>">
                                <h4><?php echo totalWords($row['title'],8); ?></h4>
                            </a>
                            <p><?php echo $row['description']; ?></p><br/>
                            <span class="text-color-highlight"><?php echo dateFormat($row['d_n_t'],'d M Y'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 center cross-center mb-4 mb-md-0">
                <i class="art-icon d-md-none d-lg-inline hover" onclick="go('<?php echo base_url(); ?>editor/admin/edit-article/<?php echo slug($row['title']).'/'.$row['page-id']; ?>');">edit</i>
                <i class="art-icon ml-2 d-md-none d-lg-inline hover" onclick="go('<?php echo base_url(); ?>editor/admin/preview-article/<?php echo slug($row['title']).'/'.$row['page-id']; ?>');">launch</i>
                <i class="art-icon ml-2 hover" onclick="deleteArticle('<?php echo $delete_status; ?>','<?php echo base_url(); ?>editor/admin/delete-article/',false);">delete</i>
                <i class="art-icon ml-2 text-color-unpublish hover" onclick="setPublishStatus(<?php echo $row['status']; ?>,'2','<?php echo $row['page-id']; ?>','<?php echo base_url() ;?>editor/admin/set-page-status');">radio_button_checked</i>
                <i class="art-icon ml-2 text-color-inprogress hover" onclick="setPublishStatus(<?php echo $row['status']; ?>,'1','<?php echo $row['page-id']; ?>','<?php echo base_url() ;?>editor/admin/set-page-status');">radio_button_checked</i>
                <i class="art-icon ml-2 text-color-review hover" onclick="setPublishStatus(<?php echo $row['status']; ?>,'3','<?php echo $row['page-id']; ?>','<?php echo base_url() ;?>editor/admin/set-page-status');">radio_button_checked</i>
                <i class="art-icon ml-2 text-color-publish hover" onclick="setPublishStatus(<?php echo $row['status']; ?>,'4','<?php echo $row['page-id']; ?>','<?php echo base_url() ;?>editor/admin/set-page-status');">radio_button_checked</i>
            </div>
        </div>
        <hr class="mb-5 mb-md-3"/>
        <?php $delay+=.1; $i++; } ?>
    <div class="row">
        <div class="col-12 center">
            <span class="black-font"><?php pagi($config); ?></span>
        </div>
    </div>
</div>