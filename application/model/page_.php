<?
class page_model  
{	
	
	// Add Page
	//----------------------------------------------------------------------------------------------
	/**
	 * Add a record
	 *
	 * @author Adam Patterson
	 */	
	public function add ( ) 
	{
		$title         = input::post( 'title' );
		$slug          = string::sanitize($title);
		$content       = input::post( 'content' );
		$status        = input::post( 'status' );
		$parent_page   = input::post( 'parent_page' );
		//$post_template = input::post( 'page_template' );
		
		$dirty_template = session::get( 'template' );
		
		if ( $dirty_template == '' ):
			$post_template = 'default';
		else:
			$post_template = $dirty_template;
		endif;
		
		
		$post_type     = input::post( 'page-or-post' );
		
		$post_author   = user::id();
		
		$uri 			= $this->get_parent_uri( $parent_page ).$slug.'/';
		
		// Run content through HTMLawd and Samrty Text
		$page          = db('posts');
		
//		$row = $page->insert(array(
//			'title'	=>$title,
//			'slug'		=>$slug,
//			'content'	=>$content,
//			'status'	=>$status,
//			'author'	=>$post_author,
//			'type'		=>$post_type,
//			'template'	=>$post_template,
//			'parent'	=>$parent_page,
//			'uri'		=>$uri,
//			'date'		=>time(),
//			'modified'	=> time()
//		));

		$scaffold_data = $_POST;

        #var_dump($scaffold_data);

		$remove_keys = array( 'save', 'title', 'content', 'status', 'parent_page', 'page_template', 'page-or-post', 'history', 'tags', 'publish', 'year', 'month', 'day', 'hour', 'minute' );
		
		foreach ( $remove_keys as $remove_key ):
			unset( $scaffold_data[ $remove_key ] );
		endforeach;

        var_dump($scaffold_data);

		$meta_value = serialize( $scaffold_data );
die;
		$page_meta      = db('posts_meta');

		$page_meta->insert(array(
			'posts_id'=>$row->id,
			'meta_key'=>'scaffold_data',
			'meta_value'=>$meta_value
		));
die;
		note::set('success','page_add','Page Added!');
		return $row->id;
	}
	
	
	// Update Page
	//----------------------------------------------------------------------------------------------	
	/**
	 * Update a record
	 *
	 * @author Adam Patterson
	 */
	public function update ( $id ) 
	{
		// create a new version of the content.

		$title         = $_POST['title'];
		$slug          = string::sanitize($title);
		$content       = $_POST['content'];
		$status        = $_POST['status'];
		$parent_page   = $_POST['parent_page'];
		//$post_template = $_POST['page_template'];
		
		//$dirty_template = session::get( 'template' );
		$dirty_template = $_POST['page_template'];

		if ( $dirty_template == '' ):
			$post_template = 'default';
		else:
			$post_template = $dirty_template;
		endif;
		
		
		$post_type     = $_POST['page-or-post'];
		
		$post_author   = user::id();
		
		$uri 			= $this->get_parent_uri( $parent_page ).$slug.'/';
		
		// Run content through HTMLawd and Samrty Text
		$page          = db('posts');

		$row = $page->update(array(
			'title'	=>$title,
			'slug'		=>$slug,
			'content'	=>$content,
			'status'	=>$status,
			'author'	=>$post_author,
			'type'		=>$post_type,
			'template'	=>$post_template,
			'parent'	=>$parent_page,
			'uri'		=>$uri,
			'modified'	=> time()
		))		
			->where( 'id', '=', $id )
			->execute();

		$scaffold_data = $_POST;

		$remove_keys = array( 'title', 'content', 'status', 'parent_page', 'page_template', 'page-or-post', 'history', 'tags', 'publish', 'year', 'month', 'day', 'hour', 'minute', 'save' );
		
		foreach ( $remove_keys as $remove_key ):
			unset( $scaffold_data[ $remove_key ] );
		endforeach;

		$meta_value = serialize( $scaffold_data );

		$page_meta      = db('posts_meta');

        $meta = $page_meta->update(array(
			'meta_value'=>$meta_value
		))
			->where( 'posts_id', '=', $id )
			->execute();

		note::set('success','page_update','Page Updated!');
	}


    public function update_page_order( $new_order )
    {
        $page = load::model('page');

        // Run the function above
        $clean_order = parse_multidimensional_array( $new_order );

        // Loop through the "readable" array and save changes to DB
        foreach ($clean_order as $key => $value) {

            // $value should always be an array, but we do a check
            if (is_array($value)) {

                $new_uri = $page->update_uri( $value['id'] );

                db('posts')->update(array(
                    'parent'=>$value['parentID'],
                    'menu_order'=>$key,
                    'uri'=>$new_uri
                ))
                    ->where( 'id', '=', $value['id'] )
                    ->execute();
            }
        }
    }


    public function update_uri( $id )
    {
        $page = load::model( 'page' );

        $parent_uri = $page->get_parent_ids( $id );

        $uri = '';
        foreach( array_reverse($parent_uri) as $id ){
            $uri .= $page->get_parent_slug( $id ).'/';
        }

        return substr($uri, 1);
    }


    public function _update_uri( $id, $id_array = array() )
    {
        $id_array[] = $id;

        $pages = db ( 'posts' );

        $parents = $pages->select( 'parent', 'uri' )
            ->where ( 'id', '=', $id )
            ->clause ( 'AND' )
            ->where ( 'type', '=', 'page' )
            ->execute();

        $has_parent = !empty($parents);

        if ($has_parent)
        {
            // Loop through all of the children and run this function again
            foreach ($parents as $parent)
            {
                $id_array = $this->get_parent_ids($parent->parent, $id_array);
            }
        }

        return $id_array;
    }



	// Get Page
	//----------------------------------------------------------------------------------------------
	/**
	 * Return all pages or one page by ID
	 *
	 * @author Adam Patterson
	 */
	public function get ( $id='' )
	{
		$pages = db ( 'posts' );
		
		if( defined( 'FRONT' ) ) {
			$get_pages = $pages->select( '*' )
				->where ( 'type', '=', 'page' )
				->order_by ( 'menu_order', 'ASC' )
				->clause ('AND')
				->where ( 'status', '=', 'published' )
				->execute();

			return $get_pages;
		} elseif ( $id == '' ) {
			$get_pages = $pages->select( '*' )
				->where ( 'type', '=', 'page' )
				->order_by ( 'menu_order', 'ASC' )
				->clause ('AND')
				->where ( 'status', '!=', 'trash' )
				->execute();

			return $get_pages;
		} else {	
			$get_pages = $pages->select( '*' )
				->where ( 'id', '=', $id )
				->order_by ( 'id', 'DESC' )
				->execute();	

			return $get_pages[0];
		}	
	}


    // Get URI
    //----------------------------------------------------------------------------------------------
    /**
     * Return a page by ID
     *
     * @author Adam Patterson
     */
    public function get_uri( $id='' )
    {
        $pages = db ( 'posts' );

        if ( $id != '' ) {
            $get_pages = $pages->select( 'uri' )
                ->where ( 'id', '=', $id )
                ->order_by ( 'id', 'DESC' )
                ->execute();

            return $get_pages[0]->uri;
        }
    }


    /**
     * Return all pages or one page by ID
     *
     * @author Adam Patterson
     */
    public function get_by_parent_id ( $id='' )
    {
        $pages = db ( 'posts' );

        $get_pages = $pages->select( '*' )
            ->where ( 'type', '=', 'page' )
            ->order_by ( 'menu_order', 'ASC' )
            ->clause ('AND')
            ->where ( 'status', '!=', 'trash' )
            ->clause ('AND')
            ->where ( 'parent', '=', $id )
            ->order_by ( 'id', 'DESC' )
            ->execute();

        return $get_pages;

    }


    // Get Page by Status
	//----------------------------------------------------------------------------------------------
	/**
	 * @todo Test outcomes of this
	 */
	public function get_by_status ( $status='' )
	{
		$pages = db ( 'posts' );
		
		if ( $status != '' ) {
			$get_pages = $pages->select( '*' )
				->where ( 'type', '=', 'page' )
			 	->clause('AND')
				->where ( 'status', '=', $status )
				->order_by ( 'menu_order', 'ASC' )
				->execute();
					
			return $get_pages;
		} else {	
			return false;
		}	
	}
	

	// Get Page Meta
	//----------------------------------------------------------------------------------------------
	/**
	 * Get the meta date for a page, this would be any scaffold data or page options.
	 *
	 * @author Adam Patterson
	 */
	public function get_page_meta ( $id='' )
	{		
		$page_meta = db ( 'posts_meta' );
	
		$dirty_page_meta = $page_meta->select( 'meta_value' )
			->where ( 'posts_id', '=', $id )
			->execute();	

        if($dirty_page_meta != '' )
        {
            $clean_page_meta = unserialize( $dirty_page_meta[0]->meta_value );
            return (object)$clean_page_meta;
        }
        else
        {
            return null;
        }
	}
	
	
	/**
	 * Get the URI of a parent page
	 *
	 * @param string $parent_id 
	 * @return void
	 * @author Adam Patterson
	 */
	public function get_parent_uri( $parent_id )
	{
		$pages = db ( 'posts' );
	
		$get_parent_uri = $pages->select( 'uri' )
			->where ( 'id', '=', $parent_id )
			->clause ( 'AND' )
			->where ( 'type', '=', 'page' )
			->execute();
		
		if ( $parent_id ):
			return $get_parent_uri[0]->uri;
		else:
			return false;
		endif;
	}


    /**
     * Get the SLUG of a parent page
     */
    public function get_parent_slug( $parent_id )
    {
        $pages = db ( 'posts' );

        $get_parent_uri = $pages->select( 'slug' )
            ->where ( 'id', '=', $parent_id )
            ->clause ( 'AND' )
            ->where ( 'type', '=', 'page' )
            ->execute();

        if ( $parent_id ):
            return $get_parent_uri[0]->slug;
        else:
            return false;
        endif;
    }


    /**
     * Get a parent page
     *
     * @param string $parent_id
     * @return void
     * @author Adam Patterson
     */
    public function get_parent( $parent_id )
    {
        $pages = db ( 'posts' );

        $get_parent_uri = $pages->select( '*' )
            ->where ( 'id', '=', $parent_id )
            ->clause ( 'AND' )
            ->where ( 'type', '=', 'page' )
            ->execute();

        if ( $parent_id ):
            return $get_parent_uri[0];
        else:
            return false;
        endif;
    }

	/**
	 * Get an object based on its URI
	 *
	 * @param string $uri 
	 * @return void
	 * @author Adam Patterson
	 * 
	 * @todo If the URI query returns nothing we should post a 404
	 * 
	 */
	public function get_by_uri( $uri )
	{

        $uri = slash_it($uri);

        $pages = db ( 'posts' );
	
		$get_parent_uri = $pages->select( '*' )
			->where ( 'uri', '=', $uri )
			->execute();

		if ( $uri ):
			if ( isset($get_parent_uri[0] ) and !empty($get_parent_uri) ):
                return $get_parent_uri[0];
			else:
				return false;
			endif;
		else:
			return false;
		endif;
	}
	
	
	/**
	 * Get an object based on its SLUG
	 *
	 * @param string $uri 
	 * @return void
	 * @author Adam Patterson
	 * 
	 * @todo Finds the last page available in a URI
	 * 
	 */
	public function get_by_slug( $uri )
	{
		$pages = db ( 'posts' );
	
		$slug_parts = explode('/', $uri);

		foreach ($slug_parts as $part ) {
			$get_slug = $pages->select( '*' )
				->where ( 'slug', '=', $part )
				->execute();
			
			if ($get_slug) {
				return $get_slug[0];
			}
		}
	}

	
	/**
	 * Return the home Hobject
	 *
	 * @author Adam Patterson
	 */
	public function get_home( )
	{
		$pages = db ( 'posts' );
				
		$home = $pages->select( '*' )
			->where ( 'menu_order', '=', '1' )
			->execute();
			
			return $home;
	}
	
	
	public function get_breadcrumbs( )
	{
		
	}
	

	/**
	 * Return all ID's under a parent id.
	 *
	 * @author Adam Patterson
	 */
	public function get_descendant_ids( $id, $id_array = array() )
	{
		$id_array[] = $id;

		$pages = db ( 'posts' );
		
		$children = $pages->select( 'id', 'title' )
			->where ( 'parent', '=', $id )
			->clause ( 'AND' )
			->where ( 'type', '=', 'page' )
			->execute();

		$has_children = !empty($children);

		if ($has_children)
		{
			// Loop through all of the children and run this function again
			foreach ($children as $child)
			{
				$id_array = $this->get_descendant_ids($child->id, $id_array);
			}
		}

		return $id_array;
	}


    public function get_parent_ids( $id, $id_array = array() )
    {
        $id_array[] = $id;

        $pages = db ( 'posts' );

        $parents = $pages->select( 'parent', 'uri' )
            ->where ( 'id', '=', $id )
            ->clause ( 'AND' )
            ->where ( 'type', '=', 'page' )
            ->execute();

        $has_parent = !empty($parents);

        if ($has_parent)
        {
            // Loop through all of the children and run this function again
            foreach ($parents as $parent)
            {
                $id_array = $this->get_parent_ids($parent->parent, $id_array);
            }
        }

        return $id_array;
    }
	
	
	/**
	 * Return a hierarchical page tree
	 *
	 * @author Adam Patterson
	 */
	public function get_page_tree ( $page_dirty )
	{	
		$pages = array();		
		
		foreach ($page_dirty as $page) {
			$pages[$page->id] = (array) $page;
		}	
			
		// build a multidimensional array of parent > children
		foreach ($pages as $row):
			if (array_key_exists($row['parent'], $pages))
				// add this page to the children array of the parent page
				$pages[$row['parent']]['children'][] =& $pages[$row['id']];
			
			// this is a root page
			if ($row['parent'] == 0)
				$page_array['children'][] =& $pages[$row['id']];
			
		endforeach;
		
		return $page_array;
	}
	

	// Children
	//----------------------------------------------------------------------------------------------
	/**
	 * Does the page have children?
	 *
	 * @access public
	 * @param int $parent_id The ID of the parent page
	 * @return mixed
	 */
	public function has_children( $parent_id )
	{
		// Query the DB looking for parent_id
	}
	
	
	/**
	* Return the children under a parent ID
	*
	* @author Adam Patterson
	*
	* @param int $page_id 
	*
	* @return Object
	*/
	public function &get_page_children( $page_id, $pages, $level = 0 ) 
	{
        $page_list = array();
        foreach ( (array) $pages as $key => $page ):
            if ( $page->parent == $page_id ):
                $page_list[$key] = (array)$page;
				$page_list[$key]['level'] = $level;
				
			//	$page_list = array_merge($page_list, $page_list_two);

                if ( $children = $this->get_page_children($page->id, $pages, $level+1 ) )
                	$page_list = array_merge( $page_list, $children );
            endif;
        endforeach;
		
        return $page_list;
    }


	public function get_page_by_level ( $pages, $depth = 0 )
	{
		$page_list = array();
     
   		foreach ( (array) $pages as $page ):
            if ( $page['level'] == $depth ):
                $page_list[] = (array)$page;
            endif;
        endforeach;

        return $page_list;
	}
	
	
	public function get_page_level ( $pages, $uri )
	{
		$page_list = array();
     
   		foreach ( (array) $pages as $page ):
            if ( $page['uri'] == $uri ):
                $page_level[] = (array)$page['level'];
            endif;
        endforeach;

        return $page_level[0][0];
	}


	public function &get_flat_page_hierarchy( &$pages, $page_id = 0 )
	{
		if ( empty( $pages ) ) {
			$result = array();
			return $result;
		}

		$children = array();
		foreach ( (array) $pages as $p ) {
			$parent_id = intval( $p->parent );
			$children[ $parent_id ][] = $p;
		}

		$result = array();
		$this->_page_traverse_name( $page_id, $children, $result );

		return $result;
	}

	
	public function _page_traverse_name( $page_id, &$children, &$result )
	{
		if ( isset( $children[ $page_id ] ) ){
			foreach( (array)$children[ $page_id ] as $child ) {
				$result[ $child->id ] = $child->slug;
				$this->_page_traverse_name( $child->id, $children, $result );
			}
		}
	}	
} // END setting_model
?>