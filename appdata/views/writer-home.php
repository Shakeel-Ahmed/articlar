<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 col-lg-2 partition-right d-none d-md-block">
            <div class="cross-center">
                <i class="art-icon" onclick="go('<?php echo base_url(); ?>author/admin');">gavel</i><div class="home-headings-text space-left-1">Actions</div>
            </div>
            <hr/>
            <div>
                <div onclick="go('<?php echo base_url(); ?>author/admin/create-new-article');" class="cross-center index-row hover space-bottom-1"><i class="art-icon">library_add</i><span class="space-left-1">New Article</span></div>
                <div onclick="go('<?php echo base_url(); ?>author/admin/articles/datasearch/date/standard/1');" class="cross-center index-row hover space-bottom-1"><i class="art-icon">pages</i><span class="space-left-1">My Articles</span></div>
                <div onclick="go('<?php echo base_url(); ?>author/admin/articles/datasearch/updated/standard/1');" class="cross-center index-row hover space-bottom-1"><i class="art-icon">update</i><span class="space-left-1">Last Updated</span></div>
                <div onclick="go('<?php echo base_url(); ?>author/admin/articles/datasearch/views/standard/1');" class="cross-center index-row hover space-bottom-1"><i class="art-icon">visibility</i><span class="space-left-1">Most Viewed</span></div>
                <div onclick="go('<?php echo base_url(); ?>author/admin/edit-my-profile/<?php echo $_SESSION['author-id']; ?>');" class="cross-center index-row hover space-bottom-1"><i class="art-icon">person</i><span class="space-left-1">Edit Profile</span></div>
                <div onclick="logout('<?php echo base_url(); ?>author/logout/');" class="cross-center index-row hover space-bottom-1"><i class="art-icon">exit_to_app</i><span class="space-left-1">Logout</span></div>
            </div>
        </div>
        <div class="col-md-8 col-lg-10 mt-3 mt-md-0">
            <div class="row">
                <div class="col-lg-6 space-bottom-1 partition-right">
                    <div class="cross-center">
                        <i class="art-icon" onclick="go('<?php echo base_url(); ?>author/admin');">announcement</i><div class="home-headings-text space-left-1">Last 5 Articles</div>
                    </div>
                    <hr class="d-none d-md-block"/>
                    <div class="card space-top-1">
                        <?php $i = 1; foreach ($latest as $row){?>
                            <div class="index-row cross-center page-<?php echo $row['page-id']; ?>"><span class="hover" onclick="go('<?php echo base_url(); ?>author/admin/preview-article/<?php echo slug($row['title']).'/'.$row['page-id']; ?>');"><?php echo $i.'. '.totalWords($row['title'],4); ?></span>
                            <div style="margin-left: auto;">
                                <i class="art-icon hover" onclick="go('<?php echo base_url(); ?>author/admin/edit-article/<?php echo slug($row['title']).'/'.$row['page-id']; ?>');">edit</i>
                                <i class="art-icon hover" onclick="go('<?php echo base_url(); ?>author/admin/preview-article/<?php echo slug($row['title']).'/'.$row['page-id']; ?>');">launch</i>
                            </div>
                        </div>
                        <?php $i++;} ?>
                    </div>
                </div>
                <div class="col-lg-6 space-bottom-1">
                    <div class="cross-center">
                        <i class="art-icon text-color-review hover" onclick="go('<?php echo base_url(); ?>author/admin');">rate_review</i><div class="home-headings-text space-left-1">Under Review</div>
                    </div>
                    <hr/>
                    <div class="card space-top-1">
                        <?php $i = 1; foreach ($review as $row) { ?>
                            <div class="index-row cross-center page-<?php echo $row['page-id']; ?>"><span class="hover" onclick="go('<?php echo base_url(); ?>author/admin/preview-article/<?php echo slug($row['title']).'/'.$row['page-id']; ?>');"><?php echo $i.'. '.totalWords($row['title'],4); ?></span>
                            <div style="margin-left: auto;">
                                <i class="art-icon hover" onclick="go('<?php echo base_url(); ?>author/admin/edit-article/<?php echo slug($row['title']).'/'.$row['page-id']; ?>');">edit</i>
                                <i class="art-icon hover" onclick="go('<?php echo base_url(); ?>author/admin/preview-article/<?php echo slug($row['title']).'/'.$row['page-id']; ?>');">launch</i>
                            </div>
                        </div>
                        <?php $i++;} ?>
                    </div>
                </div>
                <div class="col-lg-6 space-bottom-1 partition-right">
                    <div class="cross-center">
                        <i class="art-icon text-color-publish hover" onclick="go('<?php echo base_url(); ?>author/admin');">speaker_notes</i><div class="home-headings-text space-left-1">Published Articles</div>
                    </div>
                    <hr/>
                    <div class="card space-top-1">
                        <?php $i = 1; foreach ($publish as $row) { ?>
                            <div class="index-row cross-center page-<?php echo $row['page-id']; ?>"><span class="hover" onclick="go('<?php echo base_url(); ?>author/admin/preview-article/<?php echo slug($row['title']).'/'.$row['page-id']; ?>');"><?php echo $i.'. '.totalWords($row['title'],4); ?></span>
                            <div style="margin-left: auto;">
                                <i class="art-icon hover" onclick="go('<?php echo base_url(); ?>author/admin/edit-article/<?php echo slug($row['title']).'/'.$row['page-id']; ?>');">edit</i>
                                <i class="art-icon hover" onclick="go('<?php echo base_url(); ?>author/admin/preview-article/<?php echo slug($row['title']).'/'.$row['page-id']; ?>');">launch</i>
                            </div>
                        </div>
                        <?php $i++;} ?>
                    </div>
                </div>
                <div class="col-lg-6 space-bottom-1">
                    <div class="cross-center">
                        <i class="art-icon text-color-unpublish hover" onclick="go('<?php echo base_url(); ?>author/admin');">speaker_notes_off</i><div class="home-headings-text space-left-1">Cold Cabinet Articles</div>
                    </div>
                    <hr/>
                    <div class="card space-top-1">
                        <?php $i = 1; foreach ($unpublish as $row) { ?>
                        <div class="index-row cross-center page-<?php echo $row['page-id']; ?>"><span class="hover" onclick="go('<?php echo base_url(); ?>author/admin/preview-article/<?php echo slug($row['title']).'/'.$row['page-id']; ?>');"><?php echo $i.'. '.totalWords($row['title'],4); ?></span>
                            <div style="margin-left: auto;">
                                <i class="art-icon hover" onclick="go('<?php echo base_url(); ?>author/admin/edit-article/<?php echo slug($row['title']).'/'.$row['page-id']; ?>');">edit</i>
                                <i class="art-icon hover" onclick="go('<?php echo base_url(); ?>author/admin/preview-article/<?php echo slug($row['title']).'/'.$row['page-id']; ?>');">launch</i>
                            </div>
                        </div>
                        <?php $i++;} ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>