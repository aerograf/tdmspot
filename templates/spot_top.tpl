<br style="clear:both;">
<{foreach item=tpblock from=$page.tpblock}>
        <!-- Start center center -->
        <{if $tpblock.side == "spot_topcenter"}>
          <div class="xo-blockszone" style="width:99%;vertical-align:top;float:center;clear:both;">
              <div class="xo-block">
                  <{if $tpblock.title}>
                      <div class="xo-blocktitle"><{$tpblock.title}></div>
                  <{/if}>
                      <div class="xo-blockcontent"><{$tpblock.content}></div>
              </div>
          </div>
        <{/if}>
        <!-- Start top left -->
        <{if $tpblock.side == "spot_topleft"}>
          <div class="xo-blockszone" style="width:49%;vertical-align:top;display:inline-block;float:left;">
              <div class="xo-block">
                  <{if $tpblock.title}>
                      <div class="xo-blocktitle"><{$tpblock.title}></div>
                  <{/if}>
                      <div class="xo-blockcontent"><{$tpblock.content}></div>
               </div>
           </div>
         <{/if}>
         <!-- Start top right -->
         <{if $tpblock.side == "spot_topright"}>
           <div class="xo-blockszone" style="width:49%;vertical-align:top;display:inline-block;float:right;">
               <div class="xo-block">
                   <{if $tpblock.title}>
                       <div class="xo-blocktitle"><{$tpblock.title}></div>
                   <{/if}>
                       <div class="xo-blockcontent"><{$tpblock.content}></div>
                </div>
            </div>
          <{/if}>
    <!-- End center top blocks loop -->
<{/foreach}>
<br style="clear:both;">