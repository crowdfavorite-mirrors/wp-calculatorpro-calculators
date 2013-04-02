jQuery(document).ready(function($) {

  // most selects disabled
  $('.select2').select2();
  $('.select2').select2('disable');

  $('#cp_shortcode_picker').select2({
    width: 400,
    placeholder: "Choose Calculator"
  })

  $("#slider").slider({
    min:200,
    max:540,
    step:20,
    value: 260,
    disabled: true
  }); 

  // show shortcode upon calc selection
  $('#cp_shortcode_picker').change(function() {
    $('#cp_shortcode').html('Step 2: Copy and paste the following shortcode into your Post, Page, or Widget: <b>[calc id=' + $(this).val() + ']</b>');
  });

  // always refresh when picking a new calculator
  $('#cp_shortcode_picker').change(refreshPreview);
   
  // check for toggle on allow_links
  $('[name*="allow_links"]').click(function() {
    if(!$(this).is(":checked")) {
      if(confirm("Are you sure you want to remove links from your calculator? These links help to allow CalculatorPro.com to provide these widgets free of cost.")) {
        refreshPreview(); // only refresh if actually accepted toggle
      } else {
        $(this).prop('checked', true); // revert toggle on decline
      }
    } else { // always refresh if unchecked
      refreshPreview();
    }
  });

  $('#reset_cust').click(function() {
    if(confirm("Are you sure you would like to reset all of your customizations to the defaults? This cannot be undone.")) {
      $('#old_background').val('#378CAF')
        .next().val('#006395')
        .next().val('#FFFFFF')
        .next().val("260")
        .next().val('16px')
        .next().val(''); // empty currency override
      $('#allow_links_check').prop('checked',true);
      refreshPreview(); // settings probably changed
    }
  })
   
  function refreshPreview() {
    if(!isNaN(parseInt($('#cp_shortcode_picker').val()))) {   
      $('#widget_preview').html('<img class="waiting" src="/wp-admin/images/wpspin_light.gif" alt="">');     
      var data = {
        action: 'preview_calc',
        id: $('#cp_shortcode_picker').val(),
        calc_width:  $('#old_width').val(),
        text_color: $('#old_text').val(),
        start_color: $('#old_background').val(),
        end_color: $('#old_border').val(),
        allow_links: ($('[name*="allow_links"]').is(":checked") ? "1" : "-1"),
        font_size: $('[name*="font_size"]').val()
      };

      // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
      jQuery.post(ajaxurl, data, function(response) {
        $('#widget_preview')[0].innerHTML = response;
        var scriptTag = $('#wordpress_preview_calc_unique');
        var src = scriptTag.attr('src');
        var newScript = document.createElement('script');
        newScript.src = src;
        scriptTag.after(newScript);
      });
    }
  }
});