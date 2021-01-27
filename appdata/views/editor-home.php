<style>
    .stat-row
    {
        display: flex;
        margin-top: 5px;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <!-- navigation panel -->
        <div class="col-md-4 col-lg-2 partition-right d-none d-md-block">
            <div class="cross-center">
                <i class="art-icon" onclick="go('<?php echo base_url(); ?>editor/admin');">gavel</i><div class="home-headings-text space-left-1">Actions</div>
            </div>
            <hr/>
            <div>
                <div onclick="go('<?php echo base_url(); ?>editor/admin/articles/datasearch/date/latest/1');" class="cross-center index-row hover space-bottom-1"><i class="art-icon">pages</i><span class="space-left-1">Articles</span></div>
                <div onclick="go('<?php echo base_url(); ?>editor/admin/authors/datasearch/created/latest/1');" class="cross-center index-row hover space-bottom-1"><i class="art-icon">supervisor_account</i><span class="space-left-1">Authors</span></div>
                <div onclick="go('<?php echo base_url(); ?>editor/admin/create-author-profile');" class="cross-center index-row hover space-bottom-1"><i class="art-icon">person_add</i><span class="space-left-1">New Author</span></div>
                <div onclick="go('<?php echo base_url(); ?>editor/admin/installed-themes/datasearch/date/latest/1');" class="cross-center index-row hover space-bottom-1"><i class="art-icon">insert_photo</i><span class="space-left-1">Themes</span></div>
                <div onclick="go('<?php echo base_url(); ?>editor/admin/about');" class="cross-center index-row hover space-bottom-1"><i class="art-icon">new_releases</i><span class="space-left-1">About</span></div>
                <div onclick="go('<?php echo base_url(); ?>editor/admin/settings');" class="cross-center index-row hover space-bottom-1"><i class="art-icon">settings</i><span class="space-left-1">Settings</span></div>
                <div onclick="logout('<?php echo base_url(); ?>editor/admin/logout/');" class="cross-center index-row hover space-bottom-1"><i class="art-icon">exit_to_app</i><span class="space-left-1">Logout</span></div>
            </div>
        </div>
        <!-- content panel -->
        <div class="col-md-8 col-lg-10 mt-3 mt-md-0">
            <div class="row">
                <div class="col-lg-6 space-bottom-1 partition-right">
                    <div class="cross-center">
                        <i class="art-icon" onclick="go('<?php echo base_url(); ?>editor/admin');">volume_up</i><div class="home-headings-text space-left-1">Approval Required</div>
                    </div>
                    <hr/>
                    <div class="card space-top-1">
                        <?php $i = 1; foreach ($review as $row) { ?>
                            <div class="index-row cross-center"><?php echo $i.'. '.totalWords($row['title'],4); ?>
                                <div style="margin-left: auto;">
                                    <i class="art-icon hover" onclick="go('<?php echo base_url(); ?>editor/admin/edit-article/<?php echo slug($row['title']).'/'.$row['page-id']; ?>');">edit</i>
                                    <i class="art-icon hover ml-2" onclick="go('<?php echo base_url(); ?>editor/admin/preview-article/<?php echo slug($row['title']).'/'.$row['page-id']; ?>');">launch</i>
                                    <i class="art-icon hover d-none d-md-inline" onclick="deleteArticle('<?php echo $row['page-id']; ?>','<?php echo base_url(); ?>editor/admin/delete-article/');">delete</i>
                                </div>
                            </div>
                        <?php $i++; } ?>
                    </div>
                </div>
                <div class="col-lg-6 space-bottom-1 partition-right">
                    <div class="cross-center">
                        <i class="art-icon">star</i><div class="home-headings-text space-left-1">Top 5 Most Read Articles</div>
                    </div>
                    <hr/>
                    <div class="card space-top-1">
                        <?php $i = 1; foreach ($viewed as $row) { ?>
                            <div class="stat-row"><a class="hover" href="<?php echo base_url(); ?>editor/admin/preview-article/<?php echo slug($row['title']).'/'.$row['page-id']; ?>"><?php echo $i.'. '.totalWords($row['title'],4); ?></a><span style="margin-left: auto;"><?php echo number_format($row['views']); ?></span></div>
                        <?php $i++; } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-lg-4 space-bottom-1 partition-right">
            <div class="cross-center">
                <i class="art-icon">assignment</i><div class="home-headings-text space-left-1">Article Statistics</div>
            </div>
            <div class="space-top">
                <div class="stat-row">Articles<span style="margin-left: auto;"><?php echo number_format($stat_total_articles); ?></span></div>
                <div class="stat-row">Pending Reviews<span style="margin-left: auto;"><?php echo number_format($stat_pending_reviews); ?></span></div>
                <div class="stat-row">Overall Views<span style="margin-left: auto;"><?php echo number_format($stat_overall_views); ?></span></div>
                <div class="stat-row">Authors<span style="margin-left: auto;"><?php echo number_format($stat_total_authors); ?></span></div>
                <?php if($stat_last_article) { ?>
                <div class="stat-row">Last Article<span style="margin-left: auto;"><?php echo dateFormat($stat_last_article,'M d Y'); ?></span></div>
                <?php } ?>
            </div>
        </div>
        <div class="col-lg-8 space-bottom-1 partition-right">
            <div class="cross-center">
                <i class="art-icon">supervisor_account</i><div class="home-headings-text space-left-1">Star Authors</div>
            </div>
            <div class="row space-top">
                <?php foreach($team as $author)
                {
                    if($author['picture']) $picture = base_url().$this->config->item('uploadir').'authors/'.$author['author-id'].'/tmb_'.$author['picture'];
                    else $picture = base_url().'images/person.svg';
                ?>
                <div class="col-6 col-md-3 space-bottom-1">
                    <img class="author-profile-image border hover" onclick="go('<?php echo base_url(); ?>editor/admin/view-author-profile/<?php echo $author['author-id']; ?>');" src="<?php echo $picture ?>">
                    <div class="space-top-1 text-center"><?php echo $author['name']; ?></div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
