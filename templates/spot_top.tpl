<{foreach item=tpblock from=$page.tpblock}>
        <table  cellspacing="0">
        <!-- Start center center -->
        <{if $tpblock.side == "spot_topcenter"}>
            <tr>
            <td colspan="2">
            <div class="xo-blockszone">
            <div class="xo-block">
            <{if $tpblock.title}>
            <div class="xo-blocktitle"><{$tpblock.title}></div>
            <{/if}>
            <div class="xo-blockcontent"><{$tpblock.content}></div>
            </div></div>
            </td>
            </tr>
            <{/if}>
            <tr>
            <!-- Start top left -->
            <{if $tpblock.side == "spot_topleft"}>
            <td width="50%">
            <div class="xo-blockszone">
            <div class="xo-block">
            <{if $tpblock.title}>
            <div class="xo-blocktitle"><{$tpblock.title}></div>
            <{/if}>
            <div class="xo-blockcontent"><{$tpblock.content}></div>
            </div></div>
            </td>
            </div>
            <{else}>
            <td width="50%"></td>
            <{/if}>
            <!-- Start top right -->
            <{if $tpblock.side == "spot_topright"}>
            <td width="50%">
            <div class="xo-blockszone">
            <div class="xo-block">
            <{if $tpblock.title}>
            <div class="xo-blocktitle"><{$tpblock.title}></div>
            <{/if}>
            <div class="xo-blockcontent"><{$tpblock.content}></div>
            </div></div>
            </td>
            <{else}>
            <td width="50%"></td>
            <{/if}>
            </tr>
            </table><br>

        <!-- End center top blocks loop -->

<{/foreach}>
