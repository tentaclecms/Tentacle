<header>
	<nav>
		<div class="topbar">
		  <div class="fill">
			<div class="container-full">
			  <h3><a href="<?= ADMIN ?>"><img src="<?= TENTACLE_URL.'/admin/images/tentacle_logo_small.png' ?>" alt="" /></a></h3>
			<ul class="nav" data-dropdown="dropdown">
				<li class="<? if (
			    CURRENT_PAGE == 'admin/dashboard') echo 'active'; ?>"><a href="<?= ADMIN ?>">Dashboard</a></li>
				<li class="<? /*if (
			    CURRENT_PAGE == 'admin/content_add_page' OR 
			    CURRENT_PAGE == 'admin/content_manage_pages' OR 
			    CURRENT_PAGE == 'admin/content_add_post' OR
			    CURRENT_PAGE == 'admin/content_manage_posts'OR
			    CURRENT_PAGE == 'admin/content_manage_comments' OR
			    CURRENT_PAGE == 'admin/content_manage_categories') echo 'active'; */?>"> <a href="#" class="dropdown-toggle">Content</a>
				  <ul class="dropdown-menu">
					<li class="<? current_page('admin/content_add_page'); ?>"><a href="<?= ADMIN ?>content_add_page/">Write a new page</a></li>
			        <li class="<? current_page('admin/content_manage_pages'); ?>"><a href="<?= ADMIN ?>content_manage_pages/">Manage pages</a></li>
					<li class="divider"></li>
			        <li class="<? current_page('admin/content_add_post'); ?>"><a href="<?= ADMIN ?>content_add_post/">Write a new post</a></li>
			        <li class="<? current_page('admin/content_manage_posts'); ?>"><a href="<?= ADMIN ?>content_manage_posts/">Manage posts</a></li>
					<li class="divider"></li>
			        <li class="<? current_page('admin/content_manage_comments'); ?>"><a href="<?= ADMIN ?>content_manage_comments/">Manage comments</a></li>
			        <li class="<? current_page('admin/content_manage_categories'); ?>"><a href="<?= ADMIN ?>content_manage_categories/">Manage categories</a></li>
					<li class="divider"></li>
					<li class="<? current_page('admin/media_manage'); ?>"><a href="<?= ADMIN ?>media_manage/">Manage media</a></li>
				    <li class="<? current_page('admin/media_downloads'); ?>"><a href="<?= ADMIN ?>media_downloads/">Media downloads</a></li>
				  </ul>
				</li>
				<li class="<?/* if (
			    CURRENT_PAGE == 'admin/snippets_add' OR 
			    CURRENT_PAGE == 'admin/snippets_manage') echo 'active'; */?>"> <a href="#" class="dropdown-toggle">Snippets</a>
				  <ul class="dropdown-menu">
					<li class="<? current_page('admin/snippets_add'); ?>"><a href="<?= ADMIN ?>snippets_add/">Add a mew snippet</a></li>
					<li class="<? current_page('admin/snippets_manage'); ?>"><a href="<?= ADMIN ?>snippets_manage/">Manage snippets</a></li>
				  </ul>
				</li>
				<li class="<? /*if (
			    CURRENT_PAGE == 'admin/users_manage' OR 
			    CURRENT_PAGE == 'admin/users_add' OR
			    CURRENT_PAGE == 'admin/users_profile') echo 'active'; */?>"> <a href="#" class="dropdown-toggle">Users</a>
				  <ul class="dropdown-menu">
					<li class="<? current_page('admin/users_manage'); ?>"><a href="<?= ADMIN ?>users_manage/">Manage users</a></li>
					<li class="<? current_page('admin/users_add'); ?>"><a href="<?= ADMIN ?>users_add/">Add a new user</a></li>
					<li class="<? current_page('admin/users_profile'); ?>"><a href="<?= ADMIN ?>users_profile/">Your profile</a></li>
				  </ul>
				</li>
				<li class="<? if (
			    CURRENT_PAGE == 'admin/addons_install') echo 'active'; ?> menu"><a href="<?= ADMIN ?>addons_install/" class="">Addon's</a></li>
				<li class="<? /*if (
			    CURRENT_PAGE == 'admin/settings_appearance' OR 
			    CURRENT_PAGE == 'admin/settings_general' OR 
			    CURRENT_PAGE == 'admin/settings_seo' OR 
			    CURRENT_PAGE == 'admin/settings_writing' OR
			    CURRENT_PAGE == 'admin/settings_reading'OR
			    CURRENT_PAGE == 'admin/settings_comments' OR
			    CURRENT_PAGE == 'admin/settings_media' OR
			    CURRENT_PAGE == 'admin/settings_privacy' OR
			    CURRENT_PAGE == 'admin/settings_import' OR
			    CURRENT_PAGE == 'admin/settings_export') echo 'active'; */?>"> <a href="#" class="dropdown-toggle">Settings</a>
				  <ul class="dropdown-menu">
					<li><a class="<? current_page('admin/settings_appearance'); ?>" href="<?= ADMIN ?>settings_appearance/">Appearance</a></li>
					<li class="<? current_page('admin/settings_general'); ?>"><a href="<?= ADMIN ?>settings_general/">General</a></li>
					<li class="<? current_page('admin/settings_seo'); ?>"><a href="<?= ADMIN ?>settings_seo/">SEO</a></li>
					<li class="<? current_page('admin/settings_writing'); ?>"><a href="<?= ADMIN ?>settings_writing/">Writing</a></li>
					<li class="<? current_page('admin/settings_reading'); ?>"><a href="<?= ADMIN ?>settings_reading/">Reading</a></li>
					<li class="<? current_page('admin/settings_comments'); ?>"><a href="<?= ADMIN ?>settings_comments/">Comments</a></li>
					<li class="<? current_page('admin/settings_media'); ?>"><a href="<?= ADMIN ?>settings_media/">Media</a></li>
					<li class="<? current_page('admin/settings_privacy'); ?>"><a href="<?= ADMIN ?>settings_privacy/">Privacy</a></li>
					<li class="<? current_page('admin/settings_import'); ?>"><a href="<?= ADMIN ?>settings_import/">Import</a></li>
					<li class="<? current_page('admin/settings_export'); ?>"><a href="<?= ADMIN ?>settings_export/">Export</a></li>
				  </ul>
				</li>
				<li class="<? if (
			    CURRENT_PAGE == 'admin/about_system_details') echo 'active'; ?>"><a href="<?= ADMIN ?>about_system_details/">About</a> </li>
			  </ul>
			  <!--
					<form action="">
						<input type="text" placeholder="Search" />
					  </form>-->
		<!--
			<div class="utility span4">
				Hello <strong>Adam Patterson</strong><br>
				You have <a class="messages" href="#message">3 Messages</a>
			</div>-->
			<ul class="nav secondary-nav">
				<li class="<? current_page('admin/users_profile'); ?>"> <a href="<?= ADMIN ?>users_profile/">My Account</a> </li>
				<li><a href="http://tentacle.kroud.co/" target="blank" class="help_off">Help</a>
					 <ul class="dropdown-menu">
						<li><a href="https://tentacle.tenderapp.com/home">Knowledge Base</a></li>
						<li><a href="https://github.com/adampatterson/Tentacle/issues">Submit an Issue</a></li>
					</ul>
				</li>
				<li> <a href="<?= ADMIN ?>logout/">Logout</a> </li>
			</ul>
			</div><!-- . container-full -->
		  </div><!-- .fill -->
		</div><!-- .topbar -->
	</nav>
</header>