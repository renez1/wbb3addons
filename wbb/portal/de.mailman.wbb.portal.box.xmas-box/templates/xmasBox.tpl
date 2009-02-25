		{if XMASBOX_SNOW}
		    <script type="text/javascript" src="{@RELATIVE_WBB_DIR}js/schnee.js"></script>
		{/if}
		<div class="border" id="box{$boxID}">
            {if XMASBOX_HEADER}
		        <div class="containerHead">
                    {if !XMASBOX_BOXOPENED}
        		    	<div class="containerIcon">
        		    		<a href="javascript: void(0)" onclick="openList('xmasBox', true)"><img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="xmasBoxImage" alt="" /></a>
                        </div>
                    {/if}
                    <div class="containerContent">{lang}{XMASBOX_TITLE}{/lang}</div>
                </div>
            {/if}
            <div id="xmasBox" class="container-1" style="height: 256px; margin: 0;padding: 5px 0;position: relative;background: url({@RELATIVE_WBB_DIR}images/{XMASBOX_BACKGROUND}) no-repeat 50% 50%;padding: 0;">
                <img src="{@RELATIVE_WBB_DIR}images/{XMASBOX_ANSAGER}" id="speaker" alt="{XMASBOX_ANSAGER}" style="margin: 0;" />
                <div id="bubble1" style="margin: 0; padding: 0;position: absolute;z-index: 98;top: 25px; left: 180px;background: transparent url({@RELATIVE_WBB_DIR}images/bubble.png) no-repeat 0 30px;opacity: .8;-moz-opacity: .8;filter:Alpha(opacity=80);">
                    <div id="bubble2" style="margin: 0 0 0 40px;color: #000;padding: 30px 15px 40px;background-color: #fff;border: 1px solid #fff;border-radius: 25px;-moz-border-radius: 25px;">
                        {if XMASBOX_BUBBLETOPTEXT != '' && XMASBOX_BUBBLETEXT}
                            <p style="text-align: center;margin: 0 0 5px;padding: 0;color: #000;font-family: 'Comic Sans MS' ,'Century Schoolbook L', sans-serif;font-size: 16px;">
                                {lang}{XMASBOX_BUBBLETOPTEXT}{/lang}
                            </p>
                        {/if}
                        <p style="text-align: center;margin: 0; padding: 0;">
                            <img src="{@RELATIVE_WBB_DIR}images/languages/{XMASBOX_VALUE}.png" alt="{lang}wbb.portal.box.xmasbox.lang{/lang} {XMASBOX_LANG}" style="font-size: 10px;" />{if XMASBOX_COMBINATION} <span style="margin-top: -4px;color: #f00;font-family: 'Comic Sans MS' ,'Century Schoolbook L', sans-serif;font-size: 16px;font-weight: bold;"> *</span>{/if}
                        </p>
                        {if XMASBOX_BUBBLEBOTTOMTEXT != '' && XMASBOX_BUBBLETEXT}
                            <p style="text-align: center;margin: 5px 0 0;padding: 0;color: #000;font-family: 'Comic Sans MS' ,'Century Schoolbook L', sans-serif;font-size: 16px;">
                                {lang}{XMASBOX_BUBBLEBOTTOMTEXT}{/lang}
                            </p>
                        {/if}
                    </div>
                </div>
                <div id="lang" style="margin: 0; padding: 3px;position: absolute; bottom: 0; right: 0;background-color: #fff;opacity: .8;-moz-opacity: .8;filter:Alpha(opacity=80);color: #000;" class="smallFont">
                    {if XMASBOX_COMBINATION}<span style="color: #f00;font-weight: bold;">*</span> {/if}{lang}wbb.portal.box.xmasbox.lang{/lang} {XMASBOX_LANG}
                </div>
            </div>
        </div>
        {if !XMASBOX_BOXOPENED}
            <script type="text/javascript" >
	        //<![CDATA[
	        if('{@$item.Status}' != '') initList('xmasBox', {@$item.Status});
	        //]]>
	        </script>
	    {/if}
