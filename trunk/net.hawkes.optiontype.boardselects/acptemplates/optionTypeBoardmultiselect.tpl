<select name="values[{$optionData.optionName}][]" id="{$optionData.optionName}" multiple="multiple" size="10">
{htmloptions options=$options selected=$optionData.optionValue disableEncoding=true}
</select>
