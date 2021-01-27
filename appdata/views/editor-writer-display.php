<style>hr:last-child{display: none;}</style>
<div class="container">
    <h4 class="cross-center mt-4 mt-md-0">Author Profile Details
        <div class="menu-icon hover" onclick="go('<?php echo base_url(); ?>editor/admin/edit-author-profile/<?php echo $arow['author-id']; ?>');" style="margin-left: auto;">
            <div class="art-icon">edit</div><div>edit</div>
        </div>
    </h4>
    <hr class="d-none d-md-block"/>
    <div class="row space-top">
        <div class="col-md-6 col-lg-3">
            <img class="author-profile-image border mb-3" src="<?php echo $arow['picture'];?>" style="min-height: 300px;">
        </div>
            <div class="col-md-6 col-lg-3 partition-right">
            <h6 class="text-color-highlight">Name</h6>
            <span><?php echo $arow['name']; ?></span>
            <hr class="d-block d-md-none"/>
            <h6 class="text-color-highlight space-top-1">Joined</h6>
            <span><?php echo dateFormat($arow['d_n_t'],'d M Y');?></span>
            <hr class="d-block d-md-none"/>
            <h6 class="text-color-highlight space-top-1">Article Written</h6>
            <span><?php echo number_format(count($rows)); ?></span>
            <hr class="d-block d-md-none"/>
            <h6 class="text-color-highlight space-top-1">Total Views</h6>
            <?php echo number_format($views); ?>
            <hr class="d-block d-md-none"/>
        </div>
        <div class="col-md-12 col-lg-6">
            <h4 class="text-center text-md-left mt-4 mb-4 mt-md-0 mb-md-2">About</h4>
            <p class="text-justify"><?php echo $arow['about']; ?></p>
        </div>
    </div>
<?php if($rows) { ?>
    <h4 class="text-center text-md-left space-top">Articles Written</h4>
    <hr/>
<?php } ?>
    <?php
    $delay = .1;
    $i = 1;
    foreach($rows as $row)
    {
        if($row['image'])
             $image = base_url($this->config->item('uploadir').'authors/'.$row['author-id'].'/'.$row['image']);
        else $image = base_url($this->config->item('uploadir').'picture-alt.svg');
        switch($row['status'])
        {
            case 1 : {$status_color = 'inprogress '; $delete_status = $row['page-id']; $icon='radio_button_checked';} break;
            case 2 : {$status_color = 'unpublish ';  $delete_status = $row['page-id']; $icon='radio_button_checked';} break;
            case 3 : {$status_color = 'review ';     $delete_status = $row['page-id']; $icon='radio_button_checked';} break;
            case 4 : {$status_color = 'publish ';    $delete_status = $row['page-id']; $icon='radio_button_checked';} break;
            case 5 : {$status_color = 'highlight ';  $delete_status = 'locked'; $icon = 'home';} break;
        }
        if($row['image']) $image = base_url().$this->config->item('uploadir').'page-title-pictures/tmb_'.$row['image'];
        else $image = base_url().'images/nopicture.svg';
?>
        <div class="row result-row">
            <div class="col-md-9">
                <div class="row cross-center">
                    <div class="col-md-3 col-lg-4 center">
                        <img onclick="go('<?php echo base_url(); ?>editor/admin/edit-article/<?php echo slug($row['title']).'/'.$row['page-id']; ?>');"
                             id="art-btn-<?php echo $row['page-id']; ?>" class="article-search-result-image hover border-color-<?php echo $status_color ;?>"
                             src="<?php echo $image ?>">
                    </div>
                    <div class="col-md-9 col-lg-8">
                        <div class="mt-4 mb-4 mt-md-0 mb-md-0 text-center text-md-left">
                            <a class="hover" href="<?php echo base_url(); ?>editor/admin/edit-article/<?php echo slug($row['title']).'/'.$row['page-id'];?>">
                                <h4><?php echo totalWords($row['title'],8); ?></h4>
                            </a>
                            <p><?php echo $row['description'],16; ?></p><br/>
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
</div>