<!-- menu -->
<br>

<div id="content">

<div style="display: block;
float: right;
text-align:right;
margin-top: 0;
margin-right: 10px;
background-color: #e9e9e9;
border: 1px solid #cccccc;
padding: 5px;">
<span><{$nav}></span><span> > <{$nav_bar}></span><span> <{$selectpage}><{$pageform.elements.page.body}></span><span> <{$selectcat}><{$catform.elements.pid.body}></span>
<{if $perm_submit}>
| <{$perm_submit}>
<{/if}>
</div>
<br style="clear: both;">  <br>

<{$display_cat}>

<div>
<{foreach item=page from=$page}>
<{if $page.title}>
<h3><{$page.title}></h3>
<{/if}>
<br>

<{include file="db:spot_top.tpl" tpblock=$page.tpblock}>
<{include file="db:spot_item.tpl" tpitem=$page.tpitem}>
<{include file="db:spot_bottom.tpl" tpblock=$page.tpblock}>

<{$page.content}>
<{/foreach}>

</div>

<br><br>
<{if $perm_social}>
<!-- AddThis Button BEGIN -->
<div style="float:left;"><a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;pub=xa-4ac5feea790b0936"><img src="//s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"></a><script type="text/javascript" src="//s7.addthis.com/js/250/addthis_widget.js#pub=xa-4ac5feea790b0936"></script></div>
<!-- AddThis Button END -->
<{/if}>
<{if $perm_rss}>
<div style="float:right;"><{$perm_rss}></div>
<{/if}>
<br style="clear: both;">
