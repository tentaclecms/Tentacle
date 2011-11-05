<? load::view('admin/template-header', array('title' => 'Edit '.$get_page->title, 'assets' => 'application'));?>
<? load::view('admin/template-sidebar');?>
<div id="wrap">
	<script type="text/javascript">
		$(function(){
			$('#edit_page').sisyphus({
				//onSaveCallback: function() {},
				//onRestoreCallback: function() {},
				//onReleaseDataCallback: function() {}
			});
		}); 
	</script>
	<form action="<?= BASE_URL ?>action/update_page/" method="post" class="form-stacked" id='edit_page'>
		<input type="hidden" name="page-or-post" value='page' />
		<div class="has-right-sidebar">
			<div class="contet-sidebar has-tabs">
				<div class="table-heading">
					<h3 class="regular">Page Settings</h3>
					<input type="button" value="Preview" class="btn small primary alignright" />
				</div>
				<div class="table-content">
					<fieldset>
						<dl>
							<dt>
								<label for="status">Status</label>
							</dt>
							<dd>
								<select name="status" id="status" size="1">
									<option value="draft" <? if ($get_page->status == 'draft') echo 'selected' ?>>Draft</option>
									<option value="live" <? if ($get_page->status == 'live') echo 'selected' ?>>Live</option>
								</select>
							</dd>
							<dt>
								<label for="parent_page">Parent page</label>
							</dt>
							<dd>
								<select id="parent_page" name="parent_page">
									<option value="1">Select</option>
								</select>
							</dd>
							<dt>
								<label for="page_template">Page template</label>
							</dt>
							<dd>
								<select id="page_template" name="page_template" onchange="location = this.options[this.selectedIndex].value;">
									<option value="<?= BASE_URL ?>action/render_admin/page/default" <? if ($get_page->template == 'default') echo 'selected' ?>>Default</option>
									<? $templates = get_templates( get_option( 'appearance' ) ); 
									foreach ( $templates as $template ):
									?>
										<option value="<?= BASE_URL ?>action/render_admin/page/<?= $template->template_id ?>" <? if ($get_page->template == $template->template_id) echo 'selected' ?>><?= $template->template_name ?></option>
									<? endforeach; ?>
								</select>
							</dd>
						</dl>
					</fieldset>
					<input type="hidden" value="admin/content_update_page" name="history">
					<div class="textleft actions">
						<button type="submit" class="btn large primary">Save</button>
						<a class="red button-secondary" href="#">Move to trash</a><!--<a href="#review">Save for Review</a>-->
					</div>
				</div>
			</div>
			<div id="post-body">
				<div id="post-body-content">
					<?php if( $note = note::get('page_add') or $note = note::get('page_update')): ?>
						<script type="text/javascript">
							$(document).ready(function() {
								jQuery.noticeAdd({
									text : '<?= $note['content'];?>',
									stay : false,
									type : '<?= $note['type']; ?>'
								});
							});
						</script>
					<?php endif; ?>
					<h1><img src="<?=ADMIN_URL;?>images/icons/icon_pages_32.png" alt="" /> Update <small><?= $get_page->title ?></small></h1>
					<ul class="tabs" data-tabs="tabs" >
						<li class="active"><a href="#content">Content</a></li>
						<li class=""><a href="#options">Options</a></li>
					</ul>
					<div class="tab-content tab-body" id="my-tab-content">
						<div id="content" class="active tab-pane">
							<? //clean_out($get_page_meta ) ?>
							<input type="text" name="title" placeholder='Title' value='<?= $get_page->title ?>' class='xlarge' required='required' />
							<p>Permalink: http://www.sitename/com/path/ <a href="#">Edit</a></p>
							<p>
								<textarea name="content" cols="40" rows="5" class="markItUp" placeholder='Content'><?= $get_page->content ?></textarea>
							</p>
							<div class="clear"></div>
							<div id="scaffold">
								<?
								define( 'SCAFFOLD' , 'TRUE' );

								if ( session::get( 'template' ) ) {

									include(THEMES_DIR.'/default/'.session::get('template').'.php');

									load::library ( 'file' );

									$scaffold = new Scaffold ();
									$scaffold->processThis( $data );
								}
								?>
								<div class="clear"></div>
							</div>
						</div>
						<div id="options" class="tab-pane">
							<fieldset>
								<div class="clearfix">
									<label>Breadcrumb title</label>
									<div class="input">
										<input type="text" placeholder="Edit title" name='bread_crumb' value='<?= $get_page_meta->bread_crumb ?>' />
										<span class="help-block">This title will appear in the breadcrumb trail.</span>
									</div>
								</div>
								<div class="clearfix">
									<label>Meta Keywords</label>
									<div class="input">
										<input type="text" placeholder="Keywords" name='meta_keywords' value='<?= $get_page_meta->meta_keywords ?>' />
										<span class="help-block">Separate each keyword with a comma ( , )</span>
									</div>
								</div>
								<div class="clearfix">
									<label>Meta Description</label>
									<div class="input">
										<textarea name="meta_description" cols="40" rows="5" placeholder='Enter your comments here...'><?= $get_page_meta->meta_description ?></textarea>
										<span class="help-block">A short summary of the page's content</span>
									</div>
								</div>
								<div class="clearfix">
									<label>Tags</label>
									<div class="input">
										<input type="text" class="tags" name="tags" id="tags" value='<?= $get_page_meta->tags ?>' />
										<span class="help-block">Separate each keyword with a comma ( , )</span>
									</div>
								</div>
								<div class="clearfix">
									<label>Meta Robot Tags</label>
									<div class="input">
										<ul class="inputs-list">
											<li>
												<label>
													<input type="checkbox" name='meta_robot[]' value='no_index'>
													Noindex: Tell search engines not to index this webpage.</label>
											</li>
											<li>
												<label>
													<input type="checkbox" name='meta_robot[]' value='no_follow'>
													Nofollow: Tell search engines not to spider this webpage.</label>
											</li>
										</ul>
									</div>
								</div>
								<div class="clearfix">
									<label>Discussion</label>
									<div class="input">
										<ul class="inputs-list">
											<li>
												<label>
													<input type="checkbox" name='discussion[]' value='discussion'>
													Allow comments</label>
											</li>
											<li>
												<label>
													<input type="checkbox" name='discussion[]' value='trackback'>
													Allow trackbacks and pingbacks on this page.</label>
											</li>
										</ul>
									</div>
								</div>
							</fieldset>
							<div class="clear"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<!-- #wrap -->
<? load::view('admin/template-footer');?>