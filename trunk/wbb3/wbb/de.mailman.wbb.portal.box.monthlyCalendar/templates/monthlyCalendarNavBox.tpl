                {if MONTHLYCALENDARBOX_SHOW_NAV || MONTHLYCALENDARBOX_SHOW_FORM}
                    <div class="border" style="padding: 0 0 1px; margin: 1px 0; text-align: center; vertical-align: middle;">
                        <form method="get" action="index.php#monthlyCalendarBox">
                            {if MONTHLYCALENDARBOX_SHOW_NAV}
                                <a href="index.php?page={@$redirTo}&amp;mcM={$mcM - 1}&amp;mcY={$mcY}{@SID_ARG_2ND}#monthlyCalendarBox"><img src="{@RELATIVE_WBB_DIR}icon/mcbPreviousS.png" alt="" title="{lang}wbb.portal.box.monthlyCalendar.prevMonth{/lang}" style="vertical-align: middle; margin: 0; padding: 0;" /></a>
                                <a href="index.php?page={@$redirTo}&amp;mcM={$mcM + 1}&amp;mcY={$mcY}{@SID_ARG_2ND}#monthlyCalendarBox"><img src="{@RELATIVE_WBB_DIR}icon/mcbNextS.png" alt="" title="{lang}wbb.portal.box.monthlyCalendar.nextMonth{/lang}" style="vertical-align: middle; margin: 0; padding: 0;" /></a>
                            {/if}
                            {if MONTHLYCALENDARBOX_SHOW_FORM}
                                <select name="mcM" style="margin: 1px; 0; padding:0; vertical-align: middle; border: none;">
                                {foreach from=$months item=month key=k}
                                    <option value="{$k}"{if $mcM == $k} selected="selected"{/if}>{@$month}</option>
                                {/foreach}
                                </select>
                                <input type="text" name="mcY" value="{$mcY}" maxlength="4" class="smallFont" style="width: 30px; margin: 0; padding:0; vertical-align: middle; border: none;" />
                                <input type="image" class="inputImage" src="{@RELATIVE_WCF_DIR}icon/submitS.png" style="vertical-align: middle; margin: 0; padding:0;" />
                                <input type="hidden" name="page" value="{@$redirTo}" />
                                {@SID_INPUT_TAG}
                            {/if}
                        </form>
                    </div>
                {/if}
