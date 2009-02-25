{* $Id$ *}
{include file="documentHeader"}
<head>
    <title>{lang}wcf.wysiwyg.view.wysiwyg{/lang} - {PAGE_TITLE}</title>

    {include file='headInclude' sandbox=false}
    <script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/TabbedPane.class.js"></script>
    {if $canUseBBCodes}{include file="wysiwyg"}{/if}
</head>
<body>
{include file='header' sandbox=false}

<div id="main">
    <div class="mainHeadline">
        <img src="{@RELATIVE_WBB_DIR}icon/externalWysiwygEditorL.png" alt="" />
        <div class="headlineContainer">
            <h2>{lang}wbb.externalWysiwygEditor.title{/lang}</h2>
            <p>{lang}wbb.externalWysiwygEditor.description{/lang}</p>
        </div>
    </div>

    <form method="post" action="index.php?form=ExternalWysiwygEditor{@SID_ARG_2ND}">
        <div class="border tabMenuContent">
            <div class="container-1">
                {if !$preview|empty}
                    <fieldset>
                        <legend>{lang}wcf.message.preview{/lang}</legend>
                        <div>
                            {@$preview}
                        </div>
                    </fieldset>
                {/if}
                <fieldset>
                    <legend>{lang}wbb.externalWysiwygEditor.title{/lang}</legend>
                    <div class="formElement" id="editor">
                        <div class="formField">
                            <textarea name="text" id="text" rows="20" cols="40">{$text}</textarea>
                            {if $errorField == 'text'}
                                <p class="innerError">
                                    {if $errorType == 'censoredWordsFound'}{lang}wcf.message.error.censoredWordsFound{/lang}{/if}
                                </p>
                            {/if}
                        </div>
                    </div>
                    {include file="messageFormTabs"}
                </fieldset>
                <div class="formSubmit">
                    {@SID_INPUT_TAG}
                    <input type="submit" name="send" accesskey="s" value="{lang}wcf.global.button.preview{/lang}" />
                    <input type="reset" name="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
                    <input type="hidden" name="permissionType" value="{$permissionType}" />
                </div>
            </div>
        </div>
    </form>
</div>
{include file='footer' sandbox=false}
</body>
</html>
