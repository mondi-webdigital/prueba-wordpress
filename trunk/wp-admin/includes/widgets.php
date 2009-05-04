<?php
/**
 * WordPress Widgets Administration API
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * Display list of the available widgets, either all or matching search.
 *
 * The search parameter are search terms separated by spaces.
 *
 * @since unknown
 *
 * @param string $show Optional, default is all. What to display, can be 'all', 'unused', or 'used'.
 * @param string $_search Optional. Search for widgets. Should be unsanitized.
 */
function wp_list_widgets() {
	global $wp_registered_widgets, $sidebars_widgets, $wp_registered_widget_controls;

	$done = array();
	$sort = array_keys($wp_registered_widgets);
	natcasesort($sort); ?>

	<div class="widget-holder">
	<p class="description"><?php _e('Drag widgets from here to a sidebar on the right to activate them.'); ?></p>
	<div id="widget-list">
<?php
	foreach ( $sort as $val ) {
		$widget = $wp_registered_widgets[$val];
		if ( in_array( $widget['callback'], $done, true ) ) // We already showed this multi-widget
			continue;

		$sidebar = is_active_widget( $widget['callback'], $widget['id'], false, false );
		$done[] = $widget['callback'];

		if ( ! isset( $widget['params'][0] ) )
			$widget['params'][0] = array();

		$args = array( 'widget_id' => $widget['id'], 'widget_name' => $widget['name'], '_display' => 'template' );

		if ( isset($wp_registered_widget_controls[$widget['id']]['id_base']) && isset($widget['params'][0]['number']) ) {
			$id_base = $wp_registered_widget_controls[$widget['id']]['id_base'];
			$args['_temp_id'] = "$id_base-__i__";
			$args['_multi_num'] = next_widget_id_number($id_base);
			$args['_add'] = 'multi';
		} else {
			$args['_add'] = 'single';
			if ( $sidebar )
				$args['_hide'] = '1';
		}

		$args = wp_list_widget_controls_dynamic_sidebar( array( 0 => $args, 1 => $widget['params'][0] ) );
		call_user_func_array( 'wp_widget_control', $args );
	} ?>
	</div>
	<br class='clear' />
	</div>
<?php
}

/**
 * {@internal Missing Short Description}}
 *
 * @since unknown
 *
 * @param string $sidebar
 */
function wp_list_widget_controls( $sidebar ) {
	add_filter( 'dynamic_sidebar_params', 'wp_list_widget_controls_dynamic_sidebar' );

	echo "\t<div id='$sidebar' class='widgets-sortables'>\n";
	dynamic_sidebar( $sidebar );
	echo "\t</div>\n";
}

/**
 * {@internal Missing Short Description}}
 *
 * @since unknown
 *
 * @param array $params
 * @return array
 */
function wp_list_widget_controls_dynamic_sidebar( $params ) {
	global $wp_registered_widgets;
	static $i = 0;
	$i++;

	$widget_id = $params[0]['widget_id'];
	$id = isset($params[0]['_temp_id']) ? $params[0]['_temp_id'] : $widget_id;
	$hidden = isset($params[0]['_hide']) ? ' style="display:none;"' : '';

	$params[0]['before_widget'] = "<div id='widget-${i}_$id' class='widget'$hidden>";
	$params[0]['after_widget'] = "</div>";
	$params[0]['before_title'] = "%BEG_OF_TITLE%"; // deprecated
	$params[0]['after_title'] = "%END_OF_TITLE%"; // deprecated
	if ( is_callable( $wp_registered_widgets[$widget_id]['callback'] ) ) {
		$wp_registered_widgets[$widget_id]['_callback'] = $wp_registered_widgets[$widget_id]['callback'];
		$wp_registered_widgets[$widget_id]['callback'] = 'wp_widget_control';
	}

	return $params;
}

function next_widget_id_number($id_base) {
	global $wp_registered_widgets;
	$number = 2;

	while ( isset($wp_registered_widgets["$id_base-$number"]) )
		$number++;

	return $number;
}

/**
 * Meta widget used to display the control form for a widget.
 *
 * Called from dynamic_sidebar().
 *
 * @since unknown
 *
 * @param array $sidebar_args
 * @return array
 */
function wp_widget_control( $sidebar_args ) {
	global $wp_registered_widgets, $wp_registered_widget_controls, $sidebars_widgets;

	$widget_id = $sidebar_args['widget_id'];
	$sidebar_id = isset($sidebar_args['id']) ? $sidebar_args['id'] : false;
	$key = $sidebar_id ? array_search( $widget_id, $sidebars_widgets[$sidebar_id] ) : '-1'; // position of widget in sidebar
	$control = isset($wp_registered_widget_controls[$widget_id]) ? $wp_registered_widget_controls[$widget_id] : array();
	$widget = $wp_registered_widgets[$widget_id];

	$id_format = $widget['id'];
	$widget_number = isset($control['params'][0]['number']) ? $control['params'][0]['number'] : '';
	$id_base = isset($control['id_base']) ? $control['id_base'] : $widget_id;
	$multi_number = isset($sidebar_args['_multi_num']) ? $sidebar_args['_multi_num'] : '';
	$add_new = isset($sidebar_args['_add']) ? $sidebar_args['_add'] : '';

	$query_arg = array( 'editwidget' => $widget['id'] );
	if ( $add_new ) {
		$query_arg['addnew'] = 1;
		if ( $multi_number ) {
			$query_arg['num'] = $multi_number;
			$query_arg['base'] = $id_base;
		}
	} else {
		$query_arg['sidebar'] = $sidebar_id;
		$query_arg['key'] = $key;
	}

	// We aren't showing a widget control, we're outputing a template for a mult-widget control
	if ( isset($sidebar_args['_display']) && 'template' == $sidebar_args['_display'] && $widget_number ) {
		// number == -1 implies a template where id numbers are replaced by a generic '__i__'
		$control['params'][0]['number'] = -1;
		// with id_base widget id's are constructed like {$id_base}-{$id_number}
		if ( isset($control['id_base']) )
			$id_format = $control['id_base'] . '-__i__';
	}

	$wp_registered_widgets[$widget_id]['callback'] = $wp_registered_widgets[$widget_id]['_callback'];
	unset($wp_registered_widgets[$widget_id]['_callback']);

	$widget_title = wp_specialchars( strip_tags( $sidebar_args['widget_name'] ) );
	$has_form = 'noform';

	echo $sidebar_args['before_widget']; ?>
	<div class="widget-top">
	<div class="widget-title-action">
		<a class="widget-action hide-if-no-js" href="#available-widgets"></a>
		<a class="widget-control-edit hide-if-js" href="<?php echo clean_url( add_query_arg( $query_arg ) ); ?>"><span class="edit"><?php _e('Edit'); ?></span><span class="add"><?php _e('Add'); ?></span></a>
	</div>
	<div class="widget-title"><h4><?php echo $widget_title ?></h4></div>
	</div>

	<div class="widget-inside">
	<form action="" method="post">
<?php
	if ( isset($control['callback']) )
		$has_form = call_user_func_array( $control['callback'], $control['params'] );
	else
		echo "\t\t<p>" . __('There are no options for this widget.') . "</p>\n"; ?>

	<input type="hidden" name="widget-id" class="widget-id" value="<?php echo attr($id_format); ?>" />
	<input type="hidden" name="id_base" class="id_base" value="<?php echo $attr(id_base); ?>" />
	<input type="hidden" name="widget-width" class="widget-width" value="<?php echo attr($control['width']); ?>" />
	<input type="hidden" name="widget-height" class="widget-height" value="<?php echo attr($control['height']); ?>" />
	<input type="hidden" name="widget_number" class="widget_number" value="<?php echo attr($widget_number); ?>" />
	<input type="hidden" name="multi_number" class="multi_number" value="<?php echo attr($multi_number); ?>" />
	<input type="hidden" name="add_new" class="add_new" value="<?php echo attr($add_new); ?>" />

	<div class="widget-control-actions">
		<a class="button widget-control-remove alignleft" href="<?php echo $edit ? clean_url( add_query_arg( array( 'remove' => $id_format, 'key' => $key, '_wpnonce' => $nonce ) ) ) : '#remove'; ?>"><?php _e('Remove'); ?></a>
<?php		if ( 'noform' !== $has_form ) { ?>
		<input type="submit" name="savewidget" class="button-primary widget-control-save alignright" value="<?php _ea('Save'); ?>" />
<?php		} ?>
		<br class="clear" />
	</div>
	</form>
	</div>

	<div class="widget-description">
<?php echo ( $widget_description = wp_widget_description($widget_id) ) ? "$widget_description\n" : "$widget_title\n"; ?>
	</div>
<?php
	echo $sidebar_args['after_widget'];
	return $sidebar_args;
}

