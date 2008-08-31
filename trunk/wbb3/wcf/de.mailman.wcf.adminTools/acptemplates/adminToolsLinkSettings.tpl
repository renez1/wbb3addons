{* $Id$ *}
{include file='header'}
<script type="text/javascript">
//<![CDATA[
function saveForm(fName) {
    document.forms[fName].submit();
}

function deleteLink() {
    if(confirm('{lang}wcf.acp.adminTools.confirm.delete{/lang}')) {
        document.forms['flModify'].elements['linkAction'].value = 'delete';
        document.forms['flModify'].submit();
    } else {
        return false;
    }
}
//]]>
</script>
<div class="mainHeadline">
    <img src="{@RELATIVE_WCF_DIR}icon/adminToolsLinkSettingsL.png" alt="" />
    <div class="headlineContainer">
        <h2>{lang}wcf.acp.menu.link.adminTools.linkSettings{/lang}</h2>
    </div>
</div>
{if $errorField}
    <p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}
<div class="border content">
    <div class="container-1">
        <fieldset>
            <legend>{lang}wcf.acp.adminTools.linkSettings.legend{/lang}</legend>
<!-- SELECT ******************************************** -->
            <form method="post" name="flSelect" action="index.php?form=AdminToolsLinkSettings">
                <div class="formElement" id="menuItemIDDiv">
                    <div class="formFieldLabel">
                        <label for="menuItemID">{lang}wcf.acp.adminTools.linkSettings.select{/lang}</label>
                    </div>
                    <div class="formField">
                        <select name="menuItemID" id="menuItemID" onChange="this.form.submit();">
                            <option value="0">{lang}wcf.acp.adminTools.linkSettings.new{/lang}</option>
                            {if $links|count}
                                {foreach from=$links item=link}
                                    <option value="{@$link.menuItemID}"{if $link.menuItemID == $menuItemID} selected="selected"{/if}>{lang}{@$link.menuItem}{/lang}</option>
                                {/foreach}
                            {/if}
                        </select>
                        ({#$links|count})
                    </div>
                    <div class="formFieldDesc hidden" id="menuItemIDHelpMessage">
                        {lang}wcf.acp.adminTools.linkSettings.select.description{/lang}
                    </div>
                </div>
                <script type="text/javascript">//<![CDATA[
                    inlineHelp.register('menuItemID');
                //]]></script>
                <input type="hidden" name="linkAction" value="select">
                <input type="hidden" name="packageID" value="{@PACKAGE_ID}">
                {@SID_INPUT_TAG}
            </form>

<!-- ADD/MODIFY **************************************** -->
            <form method="post" name="flModify" action="index.php?form=AdminToolsLinkSettings">
                <div class="formElement" id="menuItemDiv">
                    <div class="formFieldLabel">
                        <label for="menuItem">{lang}wcf.acp.adminTools.linkSettings.menuItem{/lang}</label>
                    </div>
                    <div class="formField">
                        <input class="inputText" type="text" id="menuItem" name="menuItem" value="{if $linkCur.menuItem|isset}{@$linkCur.menuItem}{/if}" />
                        {if $errorField == 'menuItem'}
                            <p class="innerError">
                                {if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
                                {if $errorType == 'exists'}{lang}wcf.acp.adminTools.error.duplicateEntry{/lang}{/if}
                            </p>
                        {/if}
                    </div>
                    <div class="formFieldDesc hidden" id="menuItemHelpMessage">
                        {lang}wcf.acp.adminTools.linkSettings.menuItem.description{/lang}
                    </div>
                </div>
                <script type="text/javascript">//<![CDATA[
                    inlineHelp.register('menuItem');
                //]]></script>

                <div class="formElement" id="menuItemLinkDiv">
                    <div class="formFieldLabel">
                        <label for="menuItemLink">{lang}wcf.acp.adminTools.linkSettings.menuItemLink{/lang}</label>
                    </div>
                    <div class="formField">
                        <input class="inputText" type="text" id="menuItemLink" name="menuItemLink" value="{if $linkCur.url|isset}{@$linkCur.url}{/if}" />
                        {if $errorField == 'menuItemLink'}
                            <p class="innerError">
                                {if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
                            </p>
                        {/if}
                    </div>
                    <div class="formFieldDesc hidden" id="menuItemLinkHelpMessage">
                        {lang}wcf.acp.adminTools.linkSettings.menuItemLink.description{/lang}
                    </div>
                </div>
                <script type="text/javascript">//<![CDATA[
                    inlineHelp.register('menuItemLink');
                //]]></script>

                <div class="formElement" id="showOrderDiv">
                    <div class="formFieldLabel">
                        <label for="showOrder">{lang}wcf.acp.adminTools.linkSettings.showOrder{/lang}</label>
                    </div>
                    <div class="formField">
                        <input class="inputText" type="text" id="showOrder" name="showOrder" value="{if $linkCur.showOrder|isset}{#$linkCur.showOrder}{/if}" />
                        {if $errorField == 'showOrder'}
                            <p class="innerError">
                                {if $errorType == 'empty'}{lang}wcf.acp.adminTools.error.notNumeric{/lang}{/if}
                            </p>
                        {/if}
                    </div>
                    <div class="formFieldDesc hidden" id="showOrderHelpMessage">
                        {lang}wcf.acp.adminTools.linkSettings.showOrder.description{/lang}
                    </div>
                </div>
                <script type="text/javascript">//<![CDATA[
                    inlineHelp.register('showOrder');
                //]]></script>

                <div class="formElement" id="linkTargetDiv">
                    <div class="formFieldLabel">
                        <label for="linkTarget">{lang}wcf.acp.adminTools.linkSettings.linkTarget{/lang}</label>
                    </div>
                    <div class="formField">
                        <select name="linkTarget" id="linkTarget">
                            <option value="_iframe"{if $linkCur.target|isset && $linkCur.target == '_iframe'} selected="selected"{/if}>{lang}wcf.acp.adminTools.linkSettings.linkTarget.iframe{/lang}</option>
                            <option value="_self"{if $linkCur.target|isset && $linkCur.target == '_self'} selected="selected"{/if}>{lang}wcf.acp.adminTools.linkSettings.linkTarget.self{/lang}</option>
                            <option value="_blank"{if $linkCur.target|isset && $linkCur.target == '_blank'} selected="selected"{/if}>{lang}wcf.acp.adminTools.linkSettings.linkTarget.blank{/lang}</option>
                        </select>
                    </div>
                    <div class="formFieldDesc hidden" id="linkTargetHelpMessage">
                        {lang}wcf.acp.adminTools.linkSettings.linkTarget.description{/lang}
                    </div>
                </div>
                <script type="text/javascript">//<![CDATA[
                    inlineHelp.register('linkTarget');
                //]]></script>

                <input type="hidden" name="linkAction" value="modify">
                <input type="hidden" name="packageID" value="{@PACKAGE_ID}">
                <input type="hidden" name="menuItemID" value="{$menuItemID}">
                {@SID_INPUT_TAG}

                <div class="smallButtons">
                    <ul>
                            {if !$menuItemID|empty}
                                <li><a href="javascript:void(0);" onClick="deleteLink();"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" /> <span>{lang}wcf.acp.adminTools.txt.delete{/lang}</span></a></li>
                            {/if}
                        <li><a href="javascript:void(0);" onClick="saveForm('flModify');"><img src="{@RELATIVE_WCF_DIR}icon/submitS.png" alt="" /> <span>{lang}wcf.acp.adminTools.txt.save{/lang}</span></a></li>
                    </ul>
                </div>
            </form>
        </fieldset>

<!-- iFRAME ******************************************** -->
        <fieldset>
            <legend>{lang}wcf.acp.adminTools.linkSettings.iframe.legend{/lang}</legend>
            <form method="post" name="flIframe" action="index.php?form=AdminToolsLinkSettings">
                <div class="formElement" id="iFrameWidthDiv">
                    <div class="formFieldLabel">
                        <label for="iFrameWidth">{lang}wcf.acp.adminTools.linkSettings.iframe.width{/lang}</label>
                    </div>
                    <div class="formField">
                        <input class="inputText" type="text" id="iFrameWidth" name="width" value="{if $iFrame.width|isset}{@$iFrame.width}{/if}" />
                    </div>
                    <div class="formFieldDesc hidden" id="iFrameWidthHelpMessage">
                        {lang}wcf.acp.adminTools.linkSettings.iframe.width.description{/lang}
                    </div>
                </div>
                <script type="text/javascript">//<![CDATA[
                    inlineHelp.register('iFrameWidth');
                //]]></script>

                <div class="formElement" id="iFrameHeightDiv">
                    <div class="formFieldLabel">
                        <label for="iFrameHeight">{lang}wcf.acp.adminTools.linkSettings.iframe.height{/lang}</label>
                    </div>
                    <div class="formField">
                        <input class="inputText" type="text" id="iFrameHeight" name="height" value="{if $iFrame.height|isset}{@$iFrame.height}{/if}" />
                    </div>
                    <div class="formFieldDesc hidden" id="iFrameHeightHelpMessage">
                        {lang}wcf.acp.adminTools.linkSettings.iframe.height.description{/lang}
                    </div>
                </div>
                <script type="text/javascript">//<![CDATA[
                    inlineHelp.register('iFrameHeight');
                //]]></script>

                <div class="formElement" id="iFrameBorderWidthDiv">
                    <div class="formFieldLabel">
                        <label for="iFrameBorderWidth">{lang}wcf.acp.adminTools.linkSettings.iframe.borderWidth{/lang}</label>
                    </div>
                    <div class="formField">
                        <input class="inputText" type="text" id="iFrameBorderWidth" name="borderWidth" value="{if $iFrame.borderWidth|isset}{@$iFrame.borderWidth}{/if}" />
                    </div>
                    <div class="formFieldDesc hidden" id="iFrameBorderWidthHelpMessage">
                        {lang}wcf.acp.adminTools.linkSettings.iframe.borderWidth.description{/lang}
                    </div>
                </div>
                <script type="text/javascript">//<![CDATA[
                    inlineHelp.register('iFrameBorderWidth');
                //]]></script>

                <div class="formElement" id="iFrameBorderColorDiv">
                    <div class="formFieldLabel">
                        <label for="iFrameBorderColor">{lang}wcf.acp.adminTools.linkSettings.iframe.borderColor{/lang}</label>
                    </div>
                    <div class="formField">
                        <input class="inputText" type="text" id="iFrameBorderColor" name="borderColor" value="{if $iFrame.borderColor|isset}{@$iFrame.borderColor}{/if}" />
                    </div>
                    <div class="formFieldDesc hidden" id="iFrameBorderColorHelpMessage">
                        {lang}wcf.acp.adminTools.linkSettings.iframe.borderColor.description{/lang}
                    </div>
                </div>
                <script type="text/javascript">//<![CDATA[
                    inlineHelp.register('iFrameBorderColor');
                //]]></script>

                <div class="formElement" id="iFrameBorderStyleDiv">
                    <div class="formFieldLabel">
                        <label for="iFrameBorderStyle">{lang}wcf.acp.adminTools.linkSettings.iframe.borderStyle{/lang}</label>
                    </div>
                    <div class="formField">
                        <select name="borderStyle" id="iFrameBorderStyle">
                            <option value="solid;"{if $iFrame.borderStyle|isset && $iFrame.borderStyle == 'solid;'} selected="selected"{/if}>{lang}wcf.acp.adminTools.linkSettings.iframe.borderStyle.solid{/lang}</option>
                            <option value="dotted;"{if $iFrame.borderStyle|isset && $iFrame.borderStyle == 'dotted;'} selected="selected"{/if}>{lang}wcf.acp.adminTools.linkSettings.iframe.borderStyle.dotted{/lang}</option>
                            <option value="dashed;"{if $iFrame.borderStyle|isset && $iFrame.borderStyle == 'dashed;'} selected="selected"{/if}>{lang}wcf.acp.adminTools.linkSettings.iframe.borderStyle.dashed{/lang}</option>
                            <option value="double;"{if $iFrame.borderStyle|isset && $iFrame.borderStyle == 'double;'} selected="selected"{/if}>{lang}wcf.acp.adminTools.linkSettings.iframe.borderStyle.double{/lang}</option>
                            <option value="groove;"{if $iFrame.borderStyle|isset && $iFrame.borderStyle == 'groove;'} selected="selected"{/if}>{lang}wcf.acp.adminTools.linkSettings.iframe.borderStyle.groove{/lang}</option>
                            <option value="ridge;"{if $iFrame.borderStyle|isset && $iFrame.borderStyle == 'ridge;'} selected="selected"{/if}>{lang}wcf.acp.adminTools.linkSettings.iframe.borderStyle.ridge{/lang}</option>
                            <option value="inset;"{if $iFrame.borderStyle|isset && $iFrame.borderStyle == 'inset;'} selected="selected"{/if}>{lang}wcf.acp.adminTools.linkSettings.iframe.borderStyle.inset{/lang}</option>
                            <option value="outset;"{if $iFrame.borderStyle|isset && $iFrame.borderStyle == 'outset;'} selected="selected"{/if}>{lang}wcf.acp.adminTools.linkSettings.iframe.borderStyle.outset{/lang}</option>
                        </select>
                    </div>
                    <div class="formFieldDesc hidden" id="iFrameBorderStyleHelpMessage">
                        {lang}wcf.acp.adminTools.linkSettings.iframe.borderStyle.description{/lang}
                    </div>
                </div>
                <script type="text/javascript">//<![CDATA[
                    inlineHelp.register('iFrameBorderStyle');
                //]]></script>

                <input type="hidden" name="linkAction" value="iFrame">
                <input type="hidden" name="packageID" value="{@PACKAGE_ID}">
                {@SID_INPUT_TAG}

                <div class="smallButtons">
                    <ul>
                        <li><a href="javascript:void(0);" onClick="saveForm('flIframe');"><img src="{@RELATIVE_WCF_DIR}icon/submitS.png" alt="" /> <span>{lang}wcf.acp.adminTools.txt.save{/lang}</span></a></li>
                    </ul>
                </div>
            </form>
        </fieldset>
    </div>
</div>

{include file='footer'}
