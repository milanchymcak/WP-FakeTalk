<?php
namespace FakeTalk;

// Do not access the file directly!
defined('FAKETALK_LOGGED') or die('Boo! Do not access the file directly!'); 

// Sidebar Dashboard (Admin) Menu
/**
 * Create Menu Content 
 * WP-Admin Sidebar
 * 
 */
class menuContent {

    /**
     * __construct
     *
     * @param array $structure
     * @return void
     */
    function __construct(array $structure=array()) {

		// Admin Init
		$this->createAdminInit($structure);

        // Create Menu
        $this->createMenu($structure);
    }

	/**
	 * Enqueue the required styles 
	 * Register the required scripts
	 *
	 * @param  mixed $structure
	 * @return void
	 */
	private function createAdminInit(Array $structure=array()): void {

		// Call the wp_register_style
		add_action(
			'admin_init',
			function () use ($structure) {
				// CSS
				wp_register_style(
					$structure['config']['pluginSlug'].'-style', 
					FAKETALK_URL . 'resources/css/style.min.css'
				);
				wp_enqueue_style($structure['config']['pluginSlug'].'-style');
				// JS
				wp_register_script(
					$structure['config']['pluginSlug'].'-js', 
					FAKETALK_URL . 'resources/js/script.js'
				);
				wp_enqueue_script($structure['config']['pluginSlug'].'-js');
				// CSS - DatePicker for the calendar
				wp_register_style(
					$structure['config']['pluginSlug'].'-style-datepicker', 
					FAKETALK_URL . 'resources/css/datepicker.min.css'
				);
				wp_enqueue_style($structure['config']['pluginSlug'].'-style-datepicker');
				// JS - DatePicker for the calendar
				wp_register_script(
					$structure['config']['pluginSlug'].'-js-datepicker', 
					FAKETALK_URL . 'resources/js/datepicker.min.js'
				);
				wp_enqueue_script($structure['config']['pluginSlug'].'-js-datepicker');

			}
		);
	}

    /**
     * Create Menu 
	 * Action Hook - admin_menu
     *
     * @param array $structure
     * @return void
     */
    private function createMenu(Array $structure=array()): void {

        // Call the 'add_menu_page' function with 'admin_menu' action hook
		add_action(
			'admin_menu',
			function () use ($structure) {

				// Add menu
				foreach($structure as $menuType => $menuList) {

					// Main Page
					if($menuType === 'menu_page') {

						// Retrieve slug for each menu page
						$menuPageSlug = strtolower(str_ireplace(' ', '', $menuList['title']));

						// Page Title
						$menuPageTitle = $structure['config']['pluginTitle'];

						// Main page for the plugin
						add_menu_page(
							// Page Title
							$structure['config']['pluginTitle'],
							// Menu Title
							$menuList['title'],
							// Capability of the plugin
							'manage_options',
							// Menu Slug
							$structure['config']['pluginSlug'],
							// Content of the plugin will go right here
							function () use ($menuPageTitle, $menuPageSlug) {
								$this->pluginContent($menuPageTitle, $menuPageSlug);
							},
							// Dashicon for the menu - @link https://developer.wordpress.org/resource/dashicons/#tickets
							$menuList['icon'],
							// Position
							$menuList['position']
						);

						// Remove duplicate that is created automatically by WordPress
						// Even if it's not a clean solution, there is nothing to do about it
						// http://codex.wordpress.org/Adding_Administration_Menus#Sub-Level_Menus
						add_submenu_page(
							// Hooking up parent menu page
							$structure['config']['pluginSlug'],
							// Page Title
							'',
							// Menu Title
							'',
							// Capability of the plugin
							'manage_options',
							// Menu Slug
							$structure['config']['pluginSlug'],
							// Content of the settings page
							''
						);

						// After this little hack, we remove it
						remove_submenu_page($structure['config']['pluginSlug'], $structure['config']['pluginSlug']);
					}

					// Submenu page(s)
					if($menuType === 'submenu_page') {

						foreach($menuList as $subMenuTitle) {

							// Retrieve slug for each menu page
							$menuPageSlug = strtolower(str_ireplace(' ', '', $subMenuTitle));

							// Page Title
							$menuPageTitle = $subMenuTitle . ' - ' . $structure['config']['pluginTitle'];

							// Add new > submenu for the plugin
							add_submenu_page(
								// Hooking up parent menu page
								$structure['config']['pluginSlug'],
								// Page Title
								$menuPageTitle,
								// Menu Title
								$subMenuTitle,
								// Capability of the plugin
								'manage_options',
								// Menu Slug
								$structure['config']['pluginSlug'] . '-' . $menuPageSlug,
								// Content of the settings page
								function () use ($subMenuTitle, $menuPageSlug) {
									$this->pluginContent($subMenuTitle, $menuPageSlug);
								}
							);
						}
					}
				}
			}
		);

    }

	/**
	 * Template Content
	 *
	 * @param string $menuPageTitle
	 * @param string $menuPageSlug
	 * @return string
	 */
	private function pluginContent(string $menuPageTitle='', string $menuPageSlug=''): void {

		echo '<div class="wrap" id="faketalk_content">';

		if(file_exists(FAKETALK_PATH . '/inc/components/breadcrumbs.php')) {
			echo include(FAKETALK_PATH . '/inc/components/breadcrumbs.php');
		}

		echo '<div id="faketalk_inner_container">';

		// Submitting Data from Form
		if(isset($_POST['faketalk_hidden_submit']) && !empty($_POST['faketalk_hidden_submit'])) {

			// get data
			$submitData = new submitData($_POST);

			// Print Message
			echo $submitData->prepareSubmit();
		}

		//  Headline
        echo '<h2>'.$menuPageTitle.'</h2>';

		// Post Form
		echo '<form method="post" action="" id="faketalk">';

		// #Poststuff
		echo '<div id="poststuff">';

		// #post-body
		if($menuPageSlug === 'addnew') {
			echo '<div id="post-body" class="metabox-holder columns-2">';
		} else {
			echo '<div id="post-body" class="metabox-holder">';
		}

		// #faketalk_tabbed
		echo '<div id="faketalk_tabbed">';
		
		// Add Pages
		include FAKETALK_PATH . '/inc/menu/menu.' . $menuPageSlug.'.php';

		echo '</div>'; // >> #faketalk_tabbed

		echo '</div>'; // >> #post-body

		echo '</div>'; // >> #Poststuff

		echo '</div>'; // >> #faketalk_inner_container

		echo '</div>'; // >> .wrap

	}
}