<div class="section">
  <div class="row">
    <div class="col-12">
      <h2>Your Articles</h2>
    </div>
    <div class="col-12 text-small">
      <a href="<?php echo base_url("editor/admin/articles/list/$order/$current"); ?>"><i class="icon-small sa-list" style="pointer-events: none; vertical-align: middle"></i></a>&nbsp;
      <a href="<?php echo base_url("editor/admin/articles/cube/$order/$current"); ?>"><i class="icon-small sa-list-cube" style="vertical-align: middle"></i></a>&nbsp;
      By &nbsp;<a href="<?php echo base_url("editor/admin/articles/$display/sort-by-date");?>">Date</a>&nbsp; | &nbsp;
      <a href="<?php echo base_url("editor/admin/articles/$display/sort-by-updated");?>">Updated</a>&nbsp; | &nbsp;
      <a href="<?php echo base_url("editor/admin/articles/$display/sort-by-title");?>">Title</a>
    </div>
  </div>
  <?php $delay = .1;foreach($rows as $row) { ?>
    <div id="<?php echo $row['page-id']; ?>" class="row list-view-box cross-center fadein" style="animation-delay: <?php echo $delay.'s'; ?>;">
      <div class="col-6">
        <h1 style="opacity: .75; font-size: 20px;"><?php echo $row['title']; ?></h1>
      </div>
     <div class="col-2">
        <div class="text-small">
            <?php echo dateFormat($row['d_n_t'],'d M Y'); ?>
        </div>
     </div>
     <div class="col-4 spread center">
            <a href="<?php echo base_url('editor/admin/edit-article/'.dash_url($row['title']).'/'.$row['page-id']); ?>"><i class="icon-small sa-edit"></i></a>
            <a href="javascript:deleteArticle('<?php echo $row['page-id']; ?>','<?php echo base_url('editor/admin/delete-article/'); ?>');"><i class="icon-small sa-delete"></i></a>
            <a href="javascript:setHome('<?php echo base_url('editor/admin/set-home-page/'.$row['page-id']); ?>');"><i class="icon-small sa-home"></i></a>
            <a href="javascript:unpublishArticle('<?php echo base_url('editor/admin/unpublish-article/'.dash_url($row['title'].'/'.$row['page-id'])); ?>');"><span class="icon-small sa-publish" style="background-color: #AA0000;"></span></a>
            <a href="javascript:publishArticle('<?php echo base_url('editor/admin/publish-article/'.$row['page-id']); ?>');"><span class="icon-small sa-publish"></span></a>
            <a href="<?php echo base_url('editor/admin/view-article/'.dash_url($row['title']).'/'.$row['page-id']); ?>"><i class="icon-small sa-view" onclick="window.location='';" style="cursor: pointer;"></i></a>
    </div>
    </div>
    <?php $delay+=.1;} ?>
    <div class="row">
      <div class="col-12 center">
        <span class="black-font"><?php pagi($results,$current,$config); ?></span>
      </div>
    </div>
</div>