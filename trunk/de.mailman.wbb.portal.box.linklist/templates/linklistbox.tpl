		<div class="border" id="box{$boxID}">
		    <div class="containerHead">
                {if $linkListBoxlocked != true}
    		    	<div class="containerIcon">
    		    		<a href="javascript: void(0)" onclick="openList('linklistbox', true)">
                    	<img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="linklistboxImage" alt="" /></a>
                    </div>
                {/if}
                <div class="containerContent">{lang}wbb.portal.box.linklistbox.title{/lang}</div>
            </div>
            <div class="container-1" id="linklistbox">
                <div class="containerContent"><table cellpadding="0" cellspacing="0" style="width:100%; padding:0; margin:0;">
                    {foreach from=$linkListBoxLinks item=$link}
                        {if $link.TYPE == 'SPACER'}
                            <tr><td style="width:100%;">{@$link.SPACER}</td></tr>
                        {else}
                            {if $link.PERM|empty || $this->user->getPermission($link.PERM)}
                                <tr><td style="width:100%; margin:0; padding-bottom:{if $linkListBoxSpacer > 0}{$linkListBoxSpacer}{else}0{/if}px; padding-right:5px;">
                                    <div class="smallButtons" style="margin:0; padding:0;">
                                        <ul>
                                            <li style="width:100%; padding:0; margin:0; float:left;"><a href="{@$link.URL}"{if !$link.TARGET|empty} target="{@$link.TARGET}"{/if}>{if !$link.IMG|empty}<img src="{@$link.IMG}" alt="" />{/if} <span>{lang}{@$link.TITLE}{/lang}</span></a></li>
                                        </ul>
                                    </div>
                                </td></tr>
                            {/if}
                        {/if}
                    {/foreach}
                </table></div>
            </div>
        </div>
        <script type="text/javascript">
		//<![CDATA[
		if('{@$item.Status}' != '') initList('linklistbox', {@$item.Status});
		//]]>
		</script>
