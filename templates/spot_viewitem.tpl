<!--affichage des articles-->
<br>

<div id="content">

<!-- menu -->
<div style="display: block;
float: right;
text-align:right;
margin-top: 0;
margin-right: 10px;
background-color: #e9e9e9;
border: 1px solid #cccccc;
padding: 5px;">
<span><{$nav}></span><span> &gt; <{$nav_bar}></span><span> <{$selectpage}></span><span> <{$selectcat}></span>
<{if $perm_submit}>
| <{$perm_submit}>
<{/if}>
</div>
    <script type="text/javascript">

        $(document).ready(function () {
            $('#example4').pager('dt', {
                navId: 'nav4',
                linkText: <{$tdmspot_somaire}>,
                linkWrap: '<li></li>',
                prevText: 'Previous',
                nextText: 'Next'

            });
        })
    </script>

<br style="clear: both;">  <br>
<{if $tdmspot_somaire}>
<div id="nav4" style="border: 1px solid #CCC;
    float: right;
    padding: 20px;
    padding-left: 30px;
    margin-left: 10px;
    margin-right: 10px;"><{$smarty.const._MD_TDMSPOT_SOMAIRE}>: <br></div>
<br style="clear: both;">  <br>
<{/if}>

<{foreach item=tpitem from=$tpitem}>

<{if $tdmspot_present}>
<div class="outer" style="padding:5px; background: #F0F0F0">
<table><tr><td width="5px;">
<img src="<{$tpitem.user_avatarurl}>" style="padding: 5px; background: #000;">
</td><td style="padding-left:30px" valign="top">
<div style="float: right; top: 1px;">
<{$tpitem.user_rankimage}><br>
<{$tpitem.user_ranktitle}>
</div>

<h4><{$smarty.const._MD_TDMSPOT_POSTEDBY}><a href="<{$smarty.const.XOOPS_URL}>/userinfo.php?uid=<{$tpitem.uid}>"><{$tpitem.user_name}> <{$tpitem.user_uname}></a></h4>
<{$smarty.const._MD_TDMSPOT_MEM_REGISTER}><{$tpitem.user_joindate}><br>
<{if $tpitem.user_websiteurl}>
<{$smarty.const._MD_TDMSPOT_MEM_URL}> <{$tpitem.user_websiteurl}><br><{/if}>
<{$tpitem.user_extrainfo}><br><br>
<{$tpitem.user_signature}>

</td></tr></table>
</div>
<br style="clear: both;">  <br>
<{/if}>


        <div class="outer" style="padding:5px;">

        <div style="float: right; padding:5px;">
        <{$tpitem.moyen}>
        </div>

            <div class="itemTitle">
            <h3><{$tpitem.title}></h3>
            </div>

             <div class="itemInfo">
             <span class="itemPoster"><{$smarty.const._MD_TDMSPOT_POSTEDBY}> <{$tpitem.postername}></span>,
             <span class="itemPostDate"><{$smarty.const._MD_TDMSPOT_LE}> <{$tpitem.indate}></span>
             (<span class="itemStats"><{$tpitem.hits}> <{$smarty.const._MD_TDMSPOT_HITS}></span>)
              </div>
              <{if $tpitem.file}>
               <span style="background: url(<{$smarty.const.TDMSPOT_IMAGES_URL}>/file.png) no-repeat left; padding-left: 15px;">&nbsp;<a href="<{$tpitem.file_url}>" rel="nofollow"><{$tpitem.file}></a></span>
              <{/if}>
              <hr>
              <div id="example4" class="itemBody">
        <{if $tpitem.img}>
        <div style="float: right; padding:5px;">
        <{$tpitem.img}>
        </div>
        <{/if}>



              <p class="itemText"><{$tpitem.text}></p>

             <br style="clear: both;"> <br>
              </div>


            <div class="itemFoot">
            <{if $perm_vote}>
            <a href="javascript:;"><img height="16px" src="<{$smarty.const.TDMSPOT_IMAGES_URL}>/good-mark.png" onclick="AddVote(<{$tpitem.id}>, '<{$smarty.const.TDMSPOT_URL}>');return false;" title="<{$smarty.const._MD_TDMSPOT_VOTEADD}>"></a> <a href="javascript:;"><img height="16px" src="<{$smarty.const.TDMSPOT_IMAGES_URL}>/bad-mark.png" onclick="RemoveVote(<{$tpitem.id}>, '<{$smarty.const.TDMSPOT_URL}>');return false;" title="<{$smarty.const._MD_TDMSPOT_VOTEREMOVE}>"></a>
            -
            <{/if}>
            <span style="background: url(<{$smarty.const.TDMSPOT_IMAGES_URL}>/chart.png) no-repeat left; padding-left: 15px;">&nbsp;<{$smarty.const._MD_TDMSPOT_NOTES}> <{$tpitem.counts}>/<{$tpitem.votes}> <{$smarty.const._MD_TDMSPOT_VOTES}> </span>
            -
            <span style="background: url(<{$smarty.const.TDMSPOT_IMAGES_URL}>/comment.png) no-repeat left; padding-left: 15px;">&nbsp;<{$smarty.const._MD_TDMSPOT_COMMENTS}> <{$tpitem.comments}></span>

            <{if $perm_admin}>
            -<{$perm_admin}>
            <{/if}>

            <{if $perm_export}>
            - <a href="<{$tpitem.pdf}>"><img height="16px" src="<{$smarty.const.TDMSPOT_IMAGES_URL}>/pdf.png" alt="<{$smarty.const._MD_TDMSPOT_EXPPDF}>" title="<{$smarty.const._MD_TDMSPOT_EXPPDF}>"></a> <a href="<{$tpitem.print}>"><img height="16px" src="<{$smarty.const.TDMSPOT_IMAGES_URL}>/print.png" alt="<{$smarty.const._MD_TDMSPOT_EXPPRINT}>" title="<{$smarty.const._MD_TDMSPOT_EXPPRINT}>"></a>
            <{/if}>

            </div>

            </div>
            <br>
                <{/foreach}>

        <!-- Display navigation  -->
        <{if $tdmspot_nextprev}>
        <table><tr><td align="left">
        <{$prev_page}>
    </td><td align="right">
    <{$next_page}>
    </td></tr></table>
    <{/if}>

    <br><br>


    <!-- Start resume -->
<{if $tpitem_blsimil != "" OR $tpitem_blposter != ""}>
<table class="outer" style="padding: 5px;">
    <tr>
    <{if $tpitem_blsimil != ""}>
    <td width="50%" align="left" valign="top">
        <div align="center"><span style="background: url(<{$smarty.const.TDMSPOT_IMAGES_URL}>/simil.png) no-repeat left; padding-left: 15px;">&nbsp;<b><{$smarty.const._MI_TDMSPOT_BLSIMIL}></b></span></div>
        <ul>
            <{foreach item=tpitem_blsimil from=$tpitem_blsimil}>
            <li><a href="<{$tpitem_blsimil.link}>"><{$tpitem_blsimil.title}></a> (<{$tpitem_blsimil.indate}>)</li>
            <{/foreach}>
        </ul>
    </td>
    <{/if}>
    <{if $tpitem_blposter != ""}>
    <td width="50%" align="left" valign="top">
        <div align="center"><span style="background: url(<{$smarty.const.TDMSPOT_IMAGES_URL}>/poster.png) no-repeat left; padding-left: 15px;">&nbsp;<b><{$smarty.const._MI_TDMSPOT_BLPOSTER}></b></span></div>
        <ul>
            <{foreach item=tpitem_blposter from=$tpitem_blposter}>
            <li><a href="<{$tpitem_blposter.link}>"><{$tpitem_blposter.title}></a> (<{$tpitem_blposter.indate}>)</li>
            <{/foreach}>
        </ul>
    </td>
    <{/if}>
    </tr>
</table>
<br>
<{/if}>

<br><br>
<{if $perm_social}>
<!-- AddThis Button BEGIN -->
<div style="float:left;"><a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;pub=xa-4ac5feea790b0936"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"></a><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pub=xa-4ac5feea790b0936"></script></div>
<!-- AddThis Button END -->
<{/if}>
<{if $perm_rss}>
<div style="float:right;"><{$perm_rss}></div>
<{/if}>
<br style="clear: both;">

<br>

    <!--affichage des commentaires-->
<div style="text-align: center; padding: 3px; margin: 3px;">
  <{$commentsnav}>
  <{$lang_notice}>
</div>
<!-- start comments loop -->
<div style="margin: 3px; padding: 3px;">
<{if $comment_mode == "flat"}>
  <{include file="db:system_comments_flat.tpl"}>
<{elseif $comment_mode == "thread"}>
  <{include file="db:system_comments_thread.tpl"}>
<{elseif $comment_mode == "nest"}>
  <{include file="db:system_comments_nest.tpl"}>
<{/if}>
</div>
<!-- end comments loop -->

<!-- div du content a fermer  -->
</div>
