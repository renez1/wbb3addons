  					{assign var=backupData value=$markedItemsData.$mode}
  								<th colspan="2" class="columnFilename"><div><a href="#">{lang}wcf.acp.admintools.lostandfound.filename{/lang}</a></div></th>                                
								{if $activeSubTabMenuItem == 'database'}<th class="columnUsername"><div><a href="#">{lang}wcf.acp.admintools.lostandfound.username{/lang}</a></div></th>{/if}
								{if $activeSubTabMenuItem == 'filesystem'}<th class="columnFilesize"><div><a href="#">{lang}wcf.acp.admintools.lostandfound.filesize{/lang}</a></div></th>{/if}
								{if $activeSubTabMenuItem == 'filesystem'}<th class="columnTime"><div><a href="#">{lang}wcf.acp.admintools.lostandfound.time{/lang}</a></div></th>{/if}
                            </tr>
                          </thead>
                          <tbody>
                		{foreach from=$itemData key=key item=item}								
                              	<tr class="{cycle}" id="avatarsRow{$item.itemID}">                              	  
                              	<td class="columnMarkItems">
									<label><input id="avatarsMark{$item.itemID}" type="checkbox" /></label>
								</td>								
								<td class="columnIcon">
									<img id="avatarsEdit{$item.itemID}" src="{@RELATIVE_WCF_DIR}icon/lostAndFoundAvatarItemM.png" alt="" />									
									{cycle print=false}
										<script type="text/javascript">
									//<![CDATA[
									itemData[{@$item.itemID}] = new Object();
									{if $backupData.$mode|isset}																																				
										itemData[{@$item.itemID}]['isMarked'] = 1;
									{/if}
									itemData[{@$item.itemID}]['class'] = '{cycle}';									
									itemData[{@$item.itemID}]['filename'] = '{@$item.filename}';									
									//]]>									
								</script>
								</td>     								               
								<td class="columnFilename">																
									{@$item.filename}
								</td>								
								{if $activeSubTabMenuItem == 'database'}
								<td class="columnUsername">
									{$item.username}
								</td>
								{/if}
								{if $activeSubTabMenuItem == 'filesystem'}
								<td class="columnFilesize">
									{$item.filesize}
								</td>
								<td class="columnTime">
									{$item.time|date}
								</td>
								{/if}
                                </tr>
                                {/foreach}