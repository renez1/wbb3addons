    {if $this->user->getPermission('user.board.canViewWbb3addonsBox02')}
		<div class="border" id="box{$boxID}">
		    <div class="containerHead">
                {if !$wbb3addonsBox02Opened}
    		    	<div class="containerIcon">
    		    		<a href="javascript: void(0)" onclick="openList('wbb3addonsBox02', true)"><img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="wbb3addonsBox02Image" alt="" /></a>
                    </div>
                {/if}
                <div class="containerContent">{lang}{@$wbb3addonsBox02Title}{/lang}</div>
            </div>
            <div id="wbb3addonsBox02">{@$wbb3addonsBox02Value}</div>
        </div>
        <script type="text/javascript">
		//<![CDATA[
		if('{@$item.Status}' != '') initList('wbb3addonsBox02', {@$item.Status});
		//]]>
		</script>
    {/if}
