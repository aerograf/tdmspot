<br style="clear:both;">
<{foreach item=tpblock from=$page.tpblock}>
        <!-- Start center bottom blocks loop -->
        <!-- Start center bottom -->
        <{if $tpblock.side == "spot_bottomcenter"}>
          <div class="xo-blockszone" style="width:99%;vertical-align:top;display:block;float:center;clear:both;">
              <div class="xo-block">
                  <{if $tpblock.title}>
                     <div class="xo-blocktitle"><{$tpblock.title}></div>
                  <{/if}>
                     <div class="xo-blockcontent"><{$tpblock.content}></div>
              </div>
           </div>
        <{/if}>
        <!-- Start bottom left -->
        <{if $tpblock.side == "spot_bottomleft"}>
          <div class="xo-blockszone" style="width:49%;vertical-align:top;display:inline-block;float:left;">
              <div class="xo-block">
                  <{if $tpblock.title}>
                     <div class="xo-blocktitle"><{$tpblock.title}></div>
                  <{/if}>
                     <div class="xo-blockcontent"><{$tpblock.content}></div>
               </div>
           </div>
        <{/if}>
        <!-- Start bottom right -->
        <{if $tpblock.side == "spot_bottomright"}>
           <div class="xo-blockszone" style="width:49%;vertical-align:top;display:inline-block;float:right;">
               <div class="xo-block">
                   <{if $tpblock.title}>
                       <div class="xo-blocktitle"><{$tpblock.title}></div>
                    <{/if}>
                        <div class="xo-blockcontent"><{$tpblock.content}></div>
                </div>
            </div>
        <{/if}>
        <!-- End center bottom blocks loop -->
<{/foreach}>
<br style="clear:both;">