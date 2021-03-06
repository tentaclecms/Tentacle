<? load::view('admin/partials/header', array('title' => 'Write a new page', 'assets' => array('application', 'filedrop', user_editor()) ) );?>

<div id="wrap">
    <!--
    <script type="text/javascript">
        $(function(){
            $('form#add_page').sisyphus({
                //onSaveCallback: function() {},
                //onRestoreCallback: function() {},
                //onReleaseDataCallback: function() {}
            });
        });
    </script>
    -->
    <form action="<?= BASE_URL ?>action/add_page/" method="post" class="form-stacked" id='add_page'>
    <input type="hidden" name="page-or-post" value='page' />
    <div class="has-right-sidebar">
    <div class="contet-sidebar has-tabs">
        <div class="table-heading">
            <h3 class="regular">Page Settings</h3>
        </div>
        <div class="table-content">
            <fieldset>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="parent_page">Parent page</label>
                    <select id="parent_page" name="parent_page" class="form-control">
                        <option value="0">None</option>
                        <? foreach ($pages as $page_array):
                            $page = (object)$page_array; ?>
                            <option value="<?= $page->id?>" <? selected( $page->id, $parent_page_id ); ?>><?= offset($page->level, 'list').$page->title;?></option>
                        <? endforeach;?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="page_template">Page template</label>
                    <select id="page_template" name="page_template" onchange="window.location = this.options[this.selectedIndex].value;" class="form-control">
                        <!--<option value="<?= BASE_URL ?>action/render_admin/add_page/default" selected='selected'>Default</option>-->
                        <? $templates = get_templates( ACTIVE_THEME );
                        foreach ( $templates as $template ): ?>
                            <option value="<?= BASE_URL ?>action/render_admin/add_page/<?= $template->template_id ?>" <? selected( session::get( 'template' ), $template->template_id ); ?>><?= $template->template_name ?></option>
                        <? endforeach; ?>
                    </select>
                </div>

                <input type="hidden" value="admin/content_add_page" name="history">
                <button type="submit" class="btn btn-large btn-primary">Save</button><!--<a href="#review">Save for Review</a>-->

            </fieldset>

        </div>
    </div>

    <div id="post-body">
    <div id="post-body-content">
    <h1><img src="<?=ADMIN_URL;?>images/icons/icon_pages_32.png" alt="" /> Write a new page</h1>

      <ul class="nav nav-tabs" id="content-tabs">
          <li class="active"><a href="#content">Content</a></li>
          <li class=""><a href="#options">Options</a></li>
      </ul>

      <div class="tab-content tab-body">

        <div id="content" class="active tab-pane">

            <input type="text" name="title" placeholder='Title' class='xlarge content_title form-control' required='required' id="permalink" />
            <input type="hidden" name="permalink" id="new_uri" />

            <script type="text/javascript" charset="utf-8">
                var page_post = "page";
                var uri = "<?= parent_page_slug( $parent_page_id ); ?>";
            </script>

            <p class="permalink">Permalink: <?= BASE_URL.parent_page_slug( $parent_page_id ) ?><span id="permalink_landing"></span></p>

            <? if(user_editor() == 'wysiwyg'): ?>

            <p><a href="#" id="myButton" >Insert Media</a></p>

            <p class="wysiwyg">
                <textarea cols="100" id="editor" name="content" rows="10" class="editor"></textarea>
            </p>

            <? endif; ?>

            <div class="form-group">
              <input type="text" class="form-control" placeholder="Excerpt" name='excerpt' />
            </div>

            <div id="scaffold" class="blocks">

                <? if ( session::get( 'template' ) != 'index' && session::get( 'template' ) != '' ):

                    $template = THEMES_DIR.'/'.ACTIVE_THEME.'/'.session::get('template').'.php';
                    if( file_exists( $template )): $data = get::yaml( $template );
                        if ( $data != null ):
                          $blocks = new blocks();

                          $blocks->process( $data );
                          $blocks->render();
                        endif;

                    endif;
                endif; ?>

            </div>

        </div>

        <div id="options" class="tab-pane">
            <fieldset>
              <div class="form-group">
                <label>Breadcrumb title</label>
                <input type="text" class="form-control" placeholder="Edit title" name='bread_crumb' />
                <span class="help-block">This title will appear in the breadcrumb trail.</span>
              </div>
              <div class="form-group">
                <label>Meta Keywords</label>
                <input type="text" class="form-control" placeholder="Keywords" name='meta_keywords' />
                <span class="help-block">Separate each keyword with a comma ( , )</span>
              </div>
              <div class="form-group">
                <label>Meta Description</label>
                <textarea name="meta_description" class="form-control" cols="40" rows="5" placeholder='Enter your comments here...'></textarea>
                <span class="help-block">A short summary of the page's content</span>
              </div>
              <div class="form-group">
                <label>Tags</label>
                <input type="text" class="form-control" class="tags" name="tags" id="tags" />
                <span class="help-block">Separate each keyword with a comma ( , )</span>
              </div>
                <? /*
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
    */ ?>
            </fieldset>
        </div>

    <? /* 					<div id="revisions" class="">
							<h4>Feb 7, 2011</h4>
							<div class="small-row">
								<input type="radio" checked="checked" />
								<input type="radio" />
								#8 Created 14:22 by Adam Patterson
								<div class="alignright"><img src="<?=ADMIN_URL;?>images/icons/16_roll-back.png" width="16" height="16" alt="Revert" />
								</div>
							</div>
							<div class="small-row">
								<input type="radio" />
								<input type="radio" />
								#9 Created 14:22 by Adam Patterson
								<div class="alignright"><img src="<?=ADMIN_URL;?>images/icons/16_roll-back.png" width="16" height="16" alt="Revert" />
								</div>
							</div>
							<div class="small-row">
								<input type="radio" />
								<input type="radio" checked="checked" />
								#10 Created 14:22 by Adam Patterson
								<div class="alignright"><img src="<?=ADMIN_URL;?>images/icons/16_roll-back.png" width="16" height="16" alt="Revert" />
								</div>
							</div>
							<div class="small-row last">
								<input type="radio" />
								<input type="radio" />
								#11 Created 14:22 by Adam Patterson
								<div class="alignright"><img src="<?=ADMIN_URL;?>images/icons/16_roll-back.png" width="16" height="16" alt="Revert" />
								</div>
							</div>
							<h4>Feb 6, 2011</h4>
							<div class="small-row">
								<input type="radio" checked="checked" />
								<input type="radio" />
								#8 Created 14:22 by Adam Patterson
								<div class="alignright"><img src="<?=ADMIN_URL;?>images/icons/16_roll-back.png" width="16" height="16" alt="Revert" />
								</div>
							</div>
							<div class="small-row">
								<input type="radio" />
								<input type="radio" />
								#9 Created 14:22 by Adam Patterson
								<div class="alignright"><img src="<?=ADMIN_URL;?>images/icons/16_roll-back.png" width="16" height="16" alt="Revert" />
								</div>
							</div>
							<div class="small-row">
								<input type="radio" />
								<input type="radio" checked="checked" />
								#10 Created 14:22 by Adam Patterson
								<div class="alignright"><img src="<?=ADMIN_URL;?>images/icons/16_roll-back.png" width="16" height="16" alt="Revert" />
								</div>
							</div>
							<div class="small-row last">
								<input type="radio" />
								<input type="radio" />
								#11 Created 14:22 by Adam Patterson
								<div class="alignright"><img src="<?=ADMIN_URL;?>images/icons/16_roll-back.png" width="16" height="16" alt="Revert" />
								</div>
							</div>
							<div class="actions">
								<input type="btn medium secondry" value="Compare revision" class="button" />
							</div>
							<p class="red">
								Revision code to be added when a suitable diff has been found.
							</p>
							<div class="clear"></div>
						</div>
						<div id="tasks" class="">
							<h4>Task's</h4>
							<div class="small-row"><img src="<?=ADMIN_URL;?>images/icons/16_star.png" width="16" height="16" alt="Star" />
								<input type="checkbox" />
								#8 Created 14:22 by Adam Patterson
								<div class="alignright"><img src="<?=ADMIN_URL;?>images/icons/16_edit.png" width="16" height="16" alt="Edit" /><img src="<?=ADMIN_URL;?>images/icons/16_delete.png" width="16" height="16" alt="Delete" />
								</div>
							</div>
							<div class="small-row"><img src="<?=ADMIN_URL;?>images/icons/16_star.png" width="16" height="16" alt="Star" />
								<input type="checkbox" />
								#9 Created 14:22 by Adam Patterson
								<div class="alignright"><img src="<?=ADMIN_URL;?>images/icons/16_edit.png" width="16" height="16" alt="Edit" /><img src="<?=ADMIN_URL;?>images/icons/16_delete.png" width="16" height="16" alt="Delete" />
								</div>
							</div>
							<div class="small-row"><img src="<?=ADMIN_URL;?>images/icons/16_star.png" width="16" height="16" alt="Star" />
								<input type="checkbox" />
								#10 Created 14:22 by Adam Patterson
								<div class="alignright"><img src="<?=ADMIN_URL;?>images/icons/16_edit.png" width="16" height="16" alt="Edit" /><img src="<?=ADMIN_URL;?>images/icons/16_delete.png" width="16" height="16" alt="Delete" />
								</div>
							</div>
							<div class="small-row last"><img src="<?=ADMIN_URL;?>images/icons/16_star.png" width="16" height="16" alt="Star" />
								<input type="checkbox" />
								#11 Created 14:22 by Adam Patterson
								<div class="alignright"><img src="<?=ADMIN_URL;?>images/icons/16_edit.png" width="16" height="16" alt="Edit" /><img src="<?=ADMIN_URL;?>images/icons/16_delete.png" width="16" height="16" alt="Delete" />
								</div>
							</div>
							<h4>Completed</h4>
							<div class="completed grey">
								<div class="small-row">
									<input type="checkbox" checked="checked" />
									#8 Created 14:22 by Adam Patterson
									<div class="alignright"><img src="<?=ADMIN_URL;?>images/icons/16_delete.png" width="16" height="16" alt="Delete" />
									</div>
								</div>
								<div class="small-row ">
									<input type="checkbox" checked="checked" />
									#9 Created 14:22 by Adam Patterson
									<div class="alignright"><img src="<?=ADMIN_URL;?>images/icons/16_delete.png" width="16" height="16" alt="Delete" />
									</div>
								</div>
								<div class="small-row ">
									<input type="checkbox" checked="checked" />
									#10 Created 14:22 by Adam Patterson
									<div class="alignright"><img src="<?=ADMIN_URL;?>images/icons/16_delete.png" width="16" height="16" alt="Delete" />
									</div>
								</div>
								<div class="small-row last ">
									<input type="checkbox" checked="checked" />
									#11 Created 14:22 by Adam Patterson
									<div class="alignright"><img src="<?=ADMIN_URL;?>images/icons/16_delete.png" width="16" height="16" alt="Delete" />
									</div>
								</div>
							</div>
							<p class="center">
								<a href="#"><strong>Show archived tasks</strong></a>
							</p>
							<div class="actions">
								<button type="text" class="btn medium secondary">
									Add Task
								</button>
							</div>
							<div class="clear"></div>
						</div>
*/ ?>
		    			</div>
		    		</div>
		    	</div>
		    </div>
	    </form>
    </div>
    <!-- #wrap -->
<? load::view('admin/partials/media-modal'); ?>
<? load::view( 'admin/partials/footer', array( 'assets' => array( 'filedrop', user_editor() ) ) ); ?>
