    {if $this->user->getPermission('user.board.canViewWbb3addonsBox03')}
		<div class="border" id="box{$boxID}">
		    <div class="containerHead">
                {if !$wbb3addonsBox03Opened}
    		    	<div class="containerIcon">
    		    		<a href="javascript: void(0)" onclick="openList('wbb3addonsBox03', true)"><img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="wbb3addonsBox03Image" alt="" /></a>
                    </div>
                {/if}
                <div class="containerContent">{lang}{@$wbb3addonsBox03Title}{/lang}</div>
            </div>
            <div id="wbb3addonsBox03">{@$wbb3addonsBox03Value}</div>
        </div>
        <script type="text/javascript">
		//<![CDATA[
		if('{@$item.Status}' != '') initList('wbb3addonsBox03', {@$item.Status});
		//]]>
		</script>
    {/if}
