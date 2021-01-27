/////////////////////////////////////////////////////////////////////////////////// GP

function cancelSave()
{
    goBack();
}

/////////////////////////////////////////////////////////////////////////////////// GP

function go(goURL)
{
    window.location = goURL;
}

/////////////////////////////////////////////////////////////////////////////////// GP

function goBack()
{
    window.history.back();
}

/////////////////////////////////////////////////////////////////////////////////// GP

    function showHidePassword(element_id)
    {
        var x = document.getElementById(element_id);
        if (x.type === "password") {
            x.type = "text";
            document.getElementById('show-hide-password-toggle').innerHTML ='check_box';
        } else {
            x.type = "password";
            document.getElementById('show-hide-password-toggle').innerHTML ='check_box_outline_blank';
        }
    }

/////////////////////////////////////////////////////////////////////////////////// GP

    function checkBox(input_id,toggle_box)
    {
        var x = document.getElementById(input_id);
        if (x.value === "false") {
            x.value = "true";
            document.getElementById(toggle_box).innerHTML ='check_box';
        } else {
            x.value = "false";
            document.getElementById(toggle_box).innerHTML ='check_box_outline_blank';
        }
    }

/////////////////////////////////////////////////////////////////////////////////// GP

function scrollToTarget(target_id)
    {
        $('body').animate({
            scrollTop: $(target_id).offset().top
        }, 1000);
    }

/////////////////////////////////////////////////////////////////////////////////// GP

    function show(a, b)
    {
      var c = a.target,
        d = new FileReader;
      d.onload = function() {
        var a = d.result,
          c = document.getElementById(b);
        c.src = a, c.style.backgroundImage = "url(" + a + ")"
      }, d.readAsDataURL(c.files[0])
    }

/////////////////////////////////////////////////////////////////////////////////// GP

    function logout(logoutAddress)
    {
        dialog.confirm({
            title: "Logout?",
            message: "Do you want to log out? You may need to log in again to use Author Dashboard.",
            cancel: "Cancel",
            button: "Logout",
            required: true,
            callback: function(value)
            {
                if(value==true)
                {
                    window.location=logoutAddress;
                }
                return false;
            }
        });
    }

/////////////////////////////////////////////////////////////////////////////////// GP

    function showUploadPics(event,showDiv)
    {
        var files = event.target.files; //FileList object
        var output = document.getElementById(showDiv);
        for(var i = 0; i< files.length; i++)
        {
            var file = files[i];
            var picReader = new FileReader();
            picReader.addEventListener("load",function(event){
                var picFile = event.target;
                output.innerHTML += '<img class="upload-box-thumbnail" src="'+picFile.result+'">';
            });
            picReader.readAsDataURL(file);
        }
    }

/////////////////////////////////////////////////////////////////////////////////// GP

    function triggerField(selector)
    {
      $(selector).trigger('click');
    }

/////////////////////////////////////////////////////////////////////////////////// GP

    function deleteFX(selector)
    {
        $(selector).css({"opacity":".3","pointer-events":"none"}).addClass("zoom-out");
    }

/////////////////////////////////////////////////////////////////////////////////// GP

    function deleteArticle(page_id,url,redirect)
    {
        if(page_id == 'locked')
        {
            dialog.alert
            ({
                title:"Article Locked",
                message: "This article is locked and can't be deleted.",
                button: "Ok",
                animation: "fade"
            });
        }
        else dialog.confirm({
                title: "Do you want to delete this article?",
                message: "Deleted article data is irrecoverable if you confirm to proceed. Please be cautious!",
                cancel: "Cancel",
                button: "Delete",
                required: true,
                callback: function(value)
                {
                    //deleteFX('.page-'+page_id);
                    //return true;
                    if(value==true)
                    {
                        $.getJSON(url+page_id,'', function(data, status)
                        {
                            if(status=='success')
                            {
                                if(data.status=='success')
                                {
                                    if(redirect != false) window.location = redirect;
                                    else deleteFX('.page-'+page_id);
                                    return true;
                                }
                                if(data.status=='fail')
                                {
                                    dialog.alert
                                    ({
                                        title: data.title,
                                        message: data.message,
                                        button: "Ok",
                                        animation: "fade",
                                    })
                                }
                            }
                            else
                            {
                                dialog.alert
                                ({
                                    title: "Failed Process",
                                    message: "Unable to delete this article due to an unexpected error. Please Try Again. ",
                                    button: "Ok",
                                    animation: "fade",
                                })

                            }
                        });
                    }
                    return false;
                }
            });
    }

/////////////////////////////////////////////////////////////////////////////////// AUTHOR / EDITOR

    function setPublishStatus(currentStatus,newStatus,pageId,url)
    {
        dialog.confirm({
            title: "Change Status?",
            message: "Are you sure you want to change the status of this article?",
            cancel: "Cancel",
            button: "Change",
            required: true,
            callback: function (value) {
                if(value==true)
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
                            return;
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
            }
        });
    }

/////////////////////////////////////////////////////////////////////////////////// AUTHOR / EDITOR

    function artSearch(searchPage)
        {
            var search = document.getElementById('art-search-field').value
                .toString()
                .toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');

            if(search == '') return false;
            window.location.href = searchPage + search + '/date/standard/1';
        }

/////////////////////////////////////////////////////////////////////////////////// AUTHOR

/*

function publish(pageId,url)
{
    var sendTo = url + pageId;
    var pubBtn = $('#art-btn-'+pageId);
    $.getJSON(sendTo,'', function(data, status)
    {
        if(data.process=='fail' && status=='success')
        {
            dialog.alert
            ({
                title: "Failed Process",
                message: "Unable to process the request due to an unexpected error.",
                button: "Ok",
                animation: "fade"
            });
        }
        else if(data.process =='success' && status=='success')
        {
            if(data.status == 1)
            {
                dialog.alert
                ({
                    title   : "Review Request Withdrawn",
                    message : "Your request for editors review for the article has been withdrawn. Its status has been set back to work in progress.",
                    button  : "Ok",
                    animation: "fade"
                });
                pubBtn.removeClass('text-color-review');
                pubBtn.addClass('text-color-inprogress');
            }
            if(data.status == 2)
            {
                dialog.alert
                ({
                    title   : "Article un-published",
                    message : "Article has been un-published and not available to readers any more.",
                    button  : "Ok",
                    animation: "fade"
                });
                pubBtn.removeClass('text-color-publish');
                pubBtn.addClass('text-color-unpublish');
            }
            if(data.status == 3)
            {
                dialog.alert
                ({
                    title   : "Review Request Submited",
                    message : "Article review request has been submited to editor.",
                    button  : "Ok",
                    animation: "fade"
                });
                pubBtn.removeClass('text-color-inprogress');
                pubBtn.addClass('text-color-review');
            }
            if(data.status == 4)
            {
                dialog.alert
                ({
                    title: "Article Published!",
                    message: "Article has been published and is available for your visitors",
                    button: "Ok",
                    animation: "fade"
                });
                pubBtn.removeClass('text-color-unpublish');
                pubBtn.addClass('text-color-publish');
            }
            if(data.status == 5)
            {
                dialog.alert
                ({
                    title:"Homepage Protection Lock",
                    message: "Homepage can not be un-published or deleted by author.",
                    button: "Ok",
                    animation: "fade"
                });
            }
        }
    });
}

*/
/////////////////////////////////////////////////////////////////////////////////// EDITOR

    function activateTheme(URL,themeON)
    {
        dialog.confirm({
            title: "Activate this theme?",
            message: "Are you sure you want to switch to this theme?",
            cancel: "Cancel",
            button: "Activate",
            required: true,
            callback: function(value)
            {
                if(value==true)
                {
                    $.getJSON(URL,'', function(data, status){
                        if(data.status=='fail' && status=='success')
                        {
                            dialog.alert
                            ({
                                title: "Failed Process",
                                message: "Unable to switch theme due to an unexpected error. Please Try Again.",
                                button: "Ok",
                                animation: "fade"
                            })
                        }
                        else if(data.status=='success' && data.current == themeON && status=='success')
                        {
                            dialog.alert
                            ({
                                title: "Already Active",
                                message: "This theme is already active. Please choose another theme.",
                                button: "Ok",
                                animation: "fade"
                            });
                            return;
                        }
                        else if(data.status=='success' && status=='success')
                        {
                            dialog.alert
                            ({
                                title: "Theme Activated!",
                                message: "The theme has been successfully activated.",
                                button: "Ok",
                                animation: "fade"
                            });
                            $('#switch-'+data.current).html('power_off');
                            $('#switch-'+themeON).html('power');
                        }
                    });
                }
                return false;
            }
        });
    }

/////////////////////////////////////////////////////////////////////////////////// EDITOR

    function deleteTheme(theme_id,element_id)
    {
        dialog.confirm({
            title: "Delete This Theme?",
            message: "Are you sure you want to uninstall the theme from the system?<br/>Please backup before you delete it.",
            cancel: "Cancel",
            button: "Delete",
            required: true,
            callback: function(value)
            {
                if(value==true)
                {
                    $.get(theme_id,'', function(data, status){
                        if((data=='false' || data!='true') && status=='success')
                        {
                            dialog.alert
                            ({
                                title: "Failed Process",
                                message: "Unable to delete theme due to an unexpected error. Please Try Again.",
                                button: "Ok",
                                animation: "fade"
                            })
                        }
                        else if(data=='true' && status=='success')
                        {
                            deleteFX('#'+element_id)
                            //$().css({/*"display":"none",*/"opacity":".2","pointer-events":"none"});
                        }
                    });
                }
                return false;
            }
        });
    }

/////////////////////////////////////////////////////////////////////////////////// EDITOR

    function updateThemeTemplate(url)
    {
        var templateMain = document.getElementById('template').value;
        var templateBlog = document.getElementById('template-blog').value;
        $.post(url,{"trigger":"goBaby","template":templateMain,"template-blog":templateBlog}, function(data, status)
        {
            if(status=='success')
            {
                if(data.status=='success')
                {
                    dialog.alert({title:"Template Updated",message:"The theme template is updated and changes are applied."});
                }
                if(data.status=='error')
                {
                    dialog.alert({title:"Error!",message:"Cannot save the template, it has errors. Please correct errors and then save." + data.message});
                }
            }
            else dialog.alert({title:"Unable to Update",message:"Sorry! Unable to update template due to an error in connection. Please try again."});
        },'json');
    }

/////////////////////////////////////////////////////////////////////////////////// FILEBROWSER

    function reveal(theTab)
    {
        var tabs = ['images','upload','articles','icons'];
        $.each(tabs, function(index,value)
        {
            if(value==theTab) $('#'+theTab).fadeIn().css('display','block');
            else $('#'+value).css('display','none');
        })
    }

/////////////////////////////////////////////////////////////////////////////////// FILEBROWSER

    function uploadTrigger(event)
    {
        event.preventDefault();
        $('#gallery').trigger('click');
        $('#uploadImagePreview').empty();
    }

/////////////////////////////////////////////////////////////////////////////////// FILEBROWSER

    function deleteAsset(location,file,element_id)
    {
        dialog.confirm({
            title: "Delete Asset",
            message: "Are you sure you want to delete this asset?",
            cancel: "No",
            button: "Yes",
            required: true,
            callback: function(value)
            {
                if(value===true)
                {
                    $.post(location,{"picture":file}, function(data, status){
                        if((data=='false' || data=='') && status=='success')
                        {
                            dialog.alert
                            ({
                                title: "Failed Process",
                                message: "Unable to delete the asset due to an unexpected error. Please try again a little later.",
                                button: "Ok",
                                animation: "fade"
                            })
                        }
                        else if(data=='true' && status=='success')
                        {
                            deleteFX('#'+element_id);
                        }
                    });
                }
            }
        });
    }
