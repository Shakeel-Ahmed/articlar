
function setPublishStatus(currentStatus,newStatus,pageId,url)
{
    var pubBtn = $('#art-btn-'+pageId);
    var sendTo = url + '/' + pageId + '/' + newStatus;
    $.get(sendTo,'', function(data, status)
    {
        if(data=='false' && status=='success')
        {
            dialog.alert
            ({
                title: "Failed Process",
                message: "Unable to publish this article due to an unexpected error. Please Try Again.",
                button: "Ok",
                animation: "fade"
            });
        }
        if(data=='denied' && status=='success')
        {
            dialog.alert
            ({
                title: "Article Locked",
                message: "Article is locked and its status cannot be changed.",
                button: "Ok",
                animation: "fade"
            });
        }
        if(data=='already' && status=='success')
        {
            dialog.alert
            ({
                title: "Already Set",
                message: "Article is already set to this status.",
                button: "Ok",
                animation: "fade"
            });
        }
        if(data =='true' && status=='success')
        {
            var levels  = ["Under Progress","Unpublish","Under Review","Published","Homepage"];
            var classes = ["border-color-inprogress","border-color-unpublish","border-color-review","border-color-publish","border-color-highlight"];
            dialog.alert
            ({
                title   : 'Publish Status Changed',
                message : 'Page publish status has been changed to <br/>' + levels[newStatus-1],
                button  : 'Ok',
                animation: "fade"
            });
            pubBtn.removeClass('border-color-inprogress border-color-unpublish border-color-review border-color-publish border-color-highlight');
            pubBtn.addClass(classes[newStatus-1]);
        }
    });
}
