<br>
<!-- menu -->
<div style="display: block;
float: right;
text-align:right;
margin-top: 0;
margin-right: 10px;
background-color: #e9e9e9;
border: 1px solid #cccccc;
padding: 5px;">
<form name="xd_select_header" style="text-align: right;" method="post" action="genre.php">
<{$smarty.const._MD_TDMSPOT_TRIPARC}> <{$numitem}> <{$smarty.const._MD_TDMSPOT_TRIITEM}>

| <{$smarty.const._MD_TDMSPOT_TRIBY}> <{$selecttris}>
| <{$smarty.const._MD_TDMSPOT_TRIVIEW}>  <{$selectview}>
</form>

<br>
<span><{$nav}></span><span> &gt; <{$nav_bar}></span><span> <{$selectpage}></span><span> <{$selectcat}></span>
<{if $perm_submit}>
| <{$perm_submit}>
<{/if}>
</div>

<br style="clear: both;">  <br>

<{$display_cat}>

<!-- off menu -->

<br><br>
<!-- Start resume -->
<{if $tpitem_blindate != "" OR $tpitem_blcounts != "" OR $tpitem_blhits != ""}>
<table class="outer" style="padding: 5px;">
    <tr>
    <{if $tpitem_blindate != ""}>
    <td width="33%" align="left" valign="top">
        <div align="center"><span style="background: url(<{$smarty.const.TDMSPOT_IMAGES_URL}>/date.png) no-repeat left; padding-left: 15px;">&nbsp;<b><{$smarty.const._MI_TDMSPOT_BLINDATE}></b></span></div>
        <ul>
            <{foreach item=tpitem_blindate from=$tpitem_blindate}>
            <li><a href="<{$tpitem_blindate.link}>"><{$tpitem_blindate.title}></a> (<{$tpitem_blindate.indate}>)</li>
            <{/foreach}>
        </ul>
    </td>
    <{/if}>
    <{if $tpitem_blcounts != ""}>
    <td width="33%" align="left" valign="top">
        <div align="center"><span style="background: url(<{$smarty.const.TDMSPOT_IMAGES_URL}>/counts.png) no-repeat left; padding-left: 15px;">&nbsp;<b><{$smarty.const._MI_TDMSPOT_BLCOUNTS}></b></span></div>
        <ul>
            <{foreach item=tpitem_blcounts from=$tpitem_blcounts}>
            <li><a href="<{$tpitem_blcounts.link}>"><{$tpitem_blcounts.title}></a> (<{$tpitem_blcounts.counts}>)</li>
            <{/foreach}>
        </ul>
    </td>
    <{/if}>
    <{if $tpitem_blhits != ""}>
    <td width="33%" align="left" valign="top">
        <div align="center"><span style="background: url(<{$smarty.const.TDMSPOT_IMAGES_URL}>/hits.png) no-repeat left; padding-left: 15px;">&nbsp;<b><{$smarty.const._MI_TDMSPOT_BLHITS}></b></span></div>
        <ul>
            <{foreach item=tpitem_blhits from=$tpitem_blhits}>
            <li><a href="<{$tpitem_blhits.link}>"><{$tpitem_blhits.title}></a> (<{$tpitem_blhits.hits}>)</li>
            <{/foreach}>
        </ul>
    </td>
    <{/if}>
    </tr>
</table>
<br><br>
<{/if}>


<table><tr>
<td>

<{include file="db:spot_item.tpl" tpitem=$tpitem}>

</td>
</tr></table>

<br>
<div align="right"><{$nav_page}></div>
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
