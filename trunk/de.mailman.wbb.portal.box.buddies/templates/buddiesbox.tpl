{if $this->user->userID && $item.buddies|isset}
    {if BUDDIESBOX_SBCOLOR_ACP}
        {assign var='bbSecondBoxColor' value=BUDDIESBOX_SBCOLOR_ACP}
    {else}
        {assign var='bbSecondBoxColor' value=2}
    {/if}
    {assign var='bbFirstBoxColor' value=1}
    {cycle values="$bbFirstBoxColor,$bbSecondBoxColor" print=false advance=false reset=true}
    <div class="border" id="box{$boxID}">
        <div class="containerHead">
        	<div class="containerIcon">
        		<a href="javascript: void(0)" onclick="openList('buddiesbox', true)">
            	<img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="buddiesboxImage" alt="" /></a>
            </div>
            <div class="containerContent">
                {if $this->user->userID}
                    <a href="index.php?form=WhiteListEdit{@SID_ARG_2ND}">{lang}wbb.portal.box.buddiesbox.title{/lang}</a>
                {else}
                    {lang}wbb.portal.box.buddiesbox.title{/lang}
                {/if}
            </div>
        </div>
        <div class="container-1" id="buddiesbox">
        	<div class="containerContent">
                {if $item.buddies|isset}
                    {foreach from=$item.buddies item=$user}
                        <div class="container-{cycle values="$bbFirstBoxColor,$bbSecondBoxColor"}" style="float:none;">
                            <div class="containerIconSmall" style="width:18px;"><img src="{@RELATIVE_WCF_DIR}icon/{$user.img}" alt="" title="{$user.imgTitle}"/></div>
                            <div style="float:right;">
                                {if $this->user->getPermission('user.pm.canUsePm') && $user.pm}
                                    <a href="index.php?form=PMNew&userID={@$user.userID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WBB_DIR}icon/pmNewS.png" alt="" title="{lang}wbb.portal.box.buddiesbox.pm{/lang}"/></a>
                                {else}
                                    <img src="{@RELATIVE_WBB_DIR}icon/pmNotAcceptedS.png" alt="" />
                                {/if}
                                {if BUDDIESBOX_SHOWDEL_ACP}
                                    <a href="index.php?form=WhiteListEdit&remove={@$user.userID}&u={@$this->user->userID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" title="{lang}wcf.user.whitelist.remove{/lang}"/></a>
                                {/if}
                            </div>
                            <div class="containerContent"><a href="index.php?page=User&amp;userID={@$user.userID}{@SID_ARG_2ND}" class="smallFont">{@$user.username}</a></div>
                        </div>
                    {/foreach}
                {else}
                    <div class="smallFont">{lang}wbb.portal.box.buddiesbox.nouser{/lang}</div>
                {/if}
            </div>
        </div>
    </div>
    <script type="text/javascript">
    //<![CDATA[
    initList('buddiesbox', {@$item.Status});
    //]]>
    </script>
{/if}
