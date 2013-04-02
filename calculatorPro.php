<?php
/*
 Plugin Name: CalculatorPro Calculators
 Plugin URI: http://www.calculatorpro.com/?utm_source=wordpress_plugin&utm_content=wp_plugins
 Description: A collection of over 300 embeddable calculators.
 Version: 1.1.4
 Author: jgadbois
 Author URI: http://www.domainsuperstar.com
 */
?>
<?php
  define("CP_VERSION","1.1.4");

  // setup callbacks
  add_action('admin_init', 'CP_init_fn');
  add_action('admin_menu', 'CP_add_settings');

  // ajax
  add_action('wp_ajax_preview_calc', 'CP_preview_calc');

  add_shortcode('calc', 'CP_widget_shortcode');

  // allow shortcode in widget
  add_filter('widget_text', 'do_shortcode');
  add_action('init', 'CP_load_text_domain');
  add_action('init', 'CP_setup_actions');

	
  function CP_load_text_domain() {
    $plugin_dir = trailingslashit( basename(dirname(__FILE__)) ) . 'lang/';
  	load_plugin_textdomain( 'calculator_pro', false, $plugin_dir );
  }

  function CP_setup_actions() {
    if( get_option('cp_version') != CP_VERSION ) {
      update_option('cp_version', CP_VERSION);
    }
  }

  function CP_add_settings() {
    add_options_page('Calculator Pro', 'Calculator Pro', 'administrator', __FILE__, 'CP_options_page_fn');
  }

  function CP_options_page_fn() {
    $options = get_option('calc_pro_options');
  ?>
    <div class="wrap">
      <div class="icon32" id="icon-options-general"><br></div>
      <h2>Calculator Pro Calculators</h2>
      <div>Provided by <a href="http://www.calculatorpro.com/?utm_source=wordpress_plugin&utm_content=settings_header" target="_blank">CalculatorPro.com</a>. Can't find the calculator you're looking for?  <a href="http://www.calculatorpro.com/contact/?utm_source=wordpress_plugin&utm_content=settings_contact" target="_blank">Contact us</a>.</div>
      <div class="cp_box donate_area clearfix">
        <h3 class="text_left">Support Calculator Pro!</h3>
        <form class="paypal_form" action="https://www.paypal.com/cgi-bin/webscr" method="post">
          <input type="hidden" name="cmd" value="_s-xclick">
          <input type="hidden" name="hosted_button_id" value="SR6TBXHTVJV2N">
          <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
          <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>
      </div>
      <form action="options.php" method="post">
        <input type="hidden" id="old_background" name="calc_pro_options[start_color]" value="<?php echo $options['start_color'] ? $options['start_color'] : ''; ?>">
        <input type="hidden" id="old_border" name="calc_pro_options[end_color]" value="<?php echo $options['end_color'] ? $options['end_color'] : ''; ?>">
        <input type="hidden" id="old_text" name="calc_pro_options[text_color]" value="<?php echo $options['text_color'] ? $options['text_color'] : ''; ?>">
        <input type="hidden" id="old_width" name="calc_pro_options[calc_width]" value="<?php echo $options['calc_width'] ? $options['calc_width'] : $options['calc_width']; ?>">
        <input type="hidden" id="old_font_size" name="calc_pro_options[font_size]" value="<?php echo $options['font_size'] ? $options['font_size'] : ''; ?>">
        <input type="hidden" id="old_currency" name="calc_pro_options[currency_symbol]" value="<?php echo $options['currency_symbol'] ? $options['currency_symbol'] : ''; ?>">
        <div id="column_holder" class="clearfix">

          <div class="backgroundForTrans">
            <div class="darken">
              <div class="cp_box clearfix main_settings">
                <div class="right">
                  <div class="cust_option_box">
                    <div class="customize_title">3. Advanced</div>
                    <div class="advanced_heading" id="first_heading">Customize Text</div>
                    <div id="select_overrides">
                      <select class="select2" id="title_override">
                        <optgroup label='Title'>
                          <option value="title">Title</option>
                        </optgroup>
                        <optgroup label='Calculator Fields'>
                        </optgroup>
                        <optgroup label='Calculator Buttons'>
                           <option value="custcalcbtntext">Calculate Button</option>
                           <option value="custloadingbtntext">Back Button</option>
                           <option value="custloadingtext">Loading Message</option>
                        </optgroup>
                      </select>
                    </div>
                    <div class="clearfix">
                      <input type="text" id="select_override_input" class="field shadow_input select_input" value="Customizable Calculator Field" disabled="disabled" />
                    </div>
                    <div class="advanced_heading">Translation</div>
                    <div id="select_overrides">
                      <select class="select2" id="translation_choose_language">
                            <option value="en">English</option>
                      </select>
                    </div>
                    <div class="advanced_heading">Answer Size</div>
                    <div id="answer_size">
                      <select class="select2" id="choose_answer_size">
                          <option value="22">22px</option>
                      </select>
                    </div>
                    <div id="checkbox_area">
                      <div class="check clearfix"><input type="checkbox" value="text_shadow" disabled checked="checked"><div class="text">text shadows</div></div>
                      <div class="check clearfix"><input type="checkbox" value="link" disabled checked="checked"><div class="text">include footer link</div></div>
                    </div>
                  </div>
                </div>
                <h3 class="no_top">Calculator Settings</h3>
                <div class="left">
                  <div class="cust_option_box">
                     <div class="customize_title">1. Choose Colors</div>
                     <div class="colorHolder clearfix"><div class="colorSelector" id="bgColorSelector"><div id="widgetBackground" style='background-color: #378caf;'></div></div> <span>Background Color</span></div>
                     <div class="colorHolder clearfix"><div class="colorSelector" id="bgEndColorSelector"><div id="widgetEndBackground" style='background-color: #006395;'></div></div> <span>Border Color</span></div>
                     <div class="colorHolder clearfix"><div class="colorSelector" id="textColorSelector"><div id="widgetText" style='background-color: #ffffff;'></div></div> <span>Text Color</span></div>
                  </div>
                  <div class="buffer"></div>
                  <div class="cust_option_box">
                     <div class="customize_title">2. Choose Styles</div>
                     <div id="select_font_size">
                        <select class="select2" id="font_selector">
                             <option value="16">16px</option>
                             <option value="17">17px</option>
                        </select>
                        Font Size
                     </div>
                     <div id="slider"></div><span id="sliderWidth">260px</span>
                     <div style="clear: both"></div>
                     <div id="slider_text">drag slider to change width</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="cp_box call_to_action">
            <h1>These customization options and more available at <a href="http://www.calculatorpro.com/?utm_source=wordpress_plugin&utm_content=settings_main">www.CalculatorPro.com</a>.</h1>
            Check out our <a href="http://www.calculatorpro.com/faq/?utm_source=wordpress_plugin&utm_content=faq">FAQ</a> page for a Q&A as well as some tips and tricks you can do with our calculators!
          </div>
        </div>
        <?php settings_fields('calc_pro_options'); ?>
        <?php do_settings_sections(__FILE__); ?>
        <p class="submit">
          <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
        </p>
        <h3>Add This Calculator To Your Site</h3>
        <table class="form-table"><tbody><tr valign="top"><th scope="row" class="select">Step 1: Select a Calculator</th>
          <td><?php CP_section_calcs() ?></td>
        </tr></tbody></table>
        <div id="cp_shortcode"></div>
      </form>

        <h3>Calculator Preview</h3>
        <div id="widget_preview">
           Select a calculator for a preview.
        </div>
    </div>
  <?php
  }

  function CP_getCalculatorList() {
    $calcList = wp_remote_get("http://calculatorpro.com/wp-content/plugins/calcs/ajax/widget.php?action=getCalcList");

    if(is_wp_error($calcList)) {
      // echo $calcList->get_error_message();
    } else {
      return json_decode($calcList['body']);
    }
  }

  function CP_getCalculator($id) {
    $calc = wp_remote_get("http://calculatorpro.com/wp-content/plugins/calcs/ajax/widget.php?action=getCalc&calc_id=" . $id);

    if(is_wp_error($calc)) {
      // echo $calc->get_error_message();
    } else {
      return json_decode($calc['body']);
    }
  }

  function CP_init_fn() {
    // set up settings
    register_setting('calc_pro_options', 'calc_pro_options' );

    add_settings_section('main_section', '', '', __FILE__);
    add_settings_field('allow_links', 'Allow Links to Calculator Pro', 'CP_allow_links_fn', __FILE__, 'main_section');
    add_settings_field('reset_customizations', 'Reset Customizations', 'CP_reset_customizations_fn', __FILE__, 'main_section');

    wp_enqueue_script('jquery-ui-slider');
    wp_enqueue_script('calc-colorpicker', plugins_url('js/colorpicker/colorpicker.js', __FILE__), array('jquery'));
    wp_enqueue_script('cp-app', plugins_url('js/app.js', __FILE__), array('jquery'));
    wp_enqueue_script('select2', plugins_url('js/select2/select2.min.js', __FILE__), array('jquery'));

    wp_register_style('cp-colorpicker', plugins_url('js/colorpicker/css/colorpicker.css', __FILE__));
    wp_enqueue_style('cp-colorpicker');

    wp_register_style('select_styling', plugins_url('js/select2/select2.css', __FILE__));
    wp_enqueue_style('select_styling');

    wp_register_style('cp-styles', plugins_url('css/cp_styles.css', __FILE__));
    wp_enqueue_style('cp-styles');

    wp_register_style('jquery-ui-slider', plugins_url('css/ui-slider.css', __FILE__));
    wp_enqueue_style('jquery-ui-slider');

    if(!get_option('calc_pro_options')) {
      $defaults = array('start_color'=>'#378CAF', 'end_color'=>'#006395', 'calc_width'=>260, 'text_color'=>'#FFFFFF', 'allow_links'=>'true', 'font_size'=>'16px');
      add_option('calc_pro_options', $defaults);
    }
  }

  function CP_shortcode_section_text_fn() {
    echo '<p>Select a calculator from the list to generate a shortcode that can be placed in any Post or Page</p>';
  }

   function CP_section_calcs() {
    $calcs = CP_getCalculatorList();

    echo "<select id='cp_shortcode_picker' name='cp_shortcode_picker'>";

    echo "<option></option>";

    foreach($calcs as $item=>$value) {
      echo "<option value='$item'>$value</option>";
    }

    echo "</select>";
  }

  function CP_allow_links_fn() {
    $options = get_option('calc_pro_options');
    $checked = $options['allow_links'] ? "checked='checked'" : "";

    echo "<input id='allow_links_check' name='calc_pro_options[allow_links]' type='checkbox' value='true' " . $checked . "'/>";
  }

  function CP_reset_customizations_fn() {
    echo "<input id='reset_cust' type='button' value='Reset' />";
  }

  function CP_rgb_to_html($rgbStr) {
		if( strpos( $rgbStr, "#" ) !== FALSE ) return $rgbStr;

		$rgbStr = substr($rgbStr, 4, sizeof($rgbStr)-2);
		$rgb = explode(',', $rgbStr);
		$r = $rgb[0]; $g = $rgb[1]; $b = $rgb[2];
		$r = intval($r); $g = intval($g); $b = intval($b);

		$r = dechex($r<0?0:($r>255?255:$r));
		$g = dechex($g<0?0:($g>255?255:$g));
		$b = dechex($b<0?0:($b>255?255:$b));

		$color = (strlen($r) < 2?'0':'').$r;
		$color .= (strlen($g) < 2?'0':'').$g;
		$color .= (strlen($b) < 2?'0':'').$b;
		return '#'.$color;
  }

  function CP_widget_shortcode($atts) {
    extract(shortcode_atts(array(
      'id' => '910',
      'text_color'=>null,
      'start_color'=>null,
      'end_color'=>null,
      'calc_width'=>null,
      'allow_links'=>null,
      'font_size'=>null,
      'use_custom_css'=>null,
      'currency_symbol'=>null,
      'unique_id'=>false
    ), $atts));
	
    $calc = CP_getCalculator($id);

    $calc->fields = explode(",", $calc->fields);
    $options = get_option('calc_pro_options');

    $text_color = $text_color ? $text_color : $options['text_color'];
    $calc_width  = $calc_width ? $calc_width : $options['calc_width'];
    $start_color = CP_rgb_to_html($start_color ? $start_color : $options['start_color']);
    $end_color = CP_rgb_to_html($end_color ? $end_color : $options['end_color']);
    $allow_links = $allow_links ? $allow_links : $options['allow_links'];
    $font_size = $font_size ? $font_size : $options['font_size'];
    $use_custom_css = $use_custom_css ? $use_custom_css : (isset($options['use_custom_css']) ? $options['use_custom_css'] : false);
    $currency_symbol = $currency_symbol ? $currency_symbol : (isset($options['currency_symbol']) ? $options['currency_symbol'] : '$');
    $unique_id = $atts['unique_id'];
    $datas = '';
    if (CP_rgb_to_html($text_color) != '#ffffff' && CP_rgb_to_html($text_color) != '#FFFFFF' && CP_rgb_to_html($text_color) != 'white') {
      $datas .= ' data-textcolor="' . CP_rgb_to_html($text_color) . '"';
    }
    if ($calc_width != '260' && $calc_width != '') {
      $datas .= ' data-calcwidth="' . $calc_width . 'px"';
    }
    if (CP_rgb_to_html($start_color) != '#378CAF' && CP_rgb_to_html($start_color) != '#378caf') {
      $datas .= ' data-backcolor="' . CP_rgb_to_html($start_color) . '"';
    }
    if (CP_rgb_to_html($end_color) != '#006395') {
      $datas .= ' data-bordcolor="' . CP_rgb_to_html($end_color) . '"';
    }
    if ($allow_links != null && $allow_links != 'null' && $allow_links != '-1') {
      $datas .= ' data-anchor="2"';
    } else {
      $datas .= ' data-anchor="' . ( -1 * ($id * 7 + 36)) . '"';
    }
    if ($font_size != '16px') {
      $datas .= ' data-textsize="' . $font_size . '"';
    }

    if ($currency_symbol != null && $currency_symbol != '$') {
      $datas .= ' data-currencysymbol="' . $currency_symbol . '"';
    }

    ob_start();

    ?>
    <div class="cp-calc-widget" data-calcid="<?php echo $id; ?>"<?php echo $datas; ?>></div><a href="http://www.calculatorpro.com/calculator/<?php $calc->name ?>"></a><script <?php echo ($unique_id ? 'id="wordpress_preview_calc_unique"' : ''); ?> src="http://www.calculatorpro.com/wp-content/plugins/calcs/js/widgetV6.min.js"></script>
    <?php

    $widget = ob_get_contents();
    ob_end_clean();
    return trim($widget);
  }

  function CP_preview_calc() {
    echo CP_widget_shortcode(array(
      'id' => $_POST['id'],
      'calc_width' => $_POST['calc_width'],
      'text_color' => $_POST['text_color'],
      'start_color' => $_POST['start_color'],
      'end_color' => $_POST['end_color'],
      'allow_links' => $_POST['allow_links'],
      'font_size' => $_POST['font_size'],
      'unique_id' => true
    ));
    die();
  }
?>
