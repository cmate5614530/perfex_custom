<script>
<?php if(isset($contract)) {?>
  var contract_id = '<?php echo html_entity_decode($contract->id); ?>';
<?php } ?>
(function($) {
  "use strict";
		validate_contract_form();
		function validate_contract_form(selector) {

   var selector = typeof(selector) == 'undefined' ? '#contract-form' : selector;

    appValidateForm($(selector), {
        contract_name: 'required',
        start_date: 'required',
        contract_number: {
               required: true,
               remote: {
                url: site_url + "admin/purchase/contract_number_exists",
                type: 'post',
                data: {
                    contract_number: function() {
                        return $('input[name="contract_number"]').val();
                    },
                    contract: function() {
                        return $('input[name="contractid"]').val();
                    }
                }
            }
           }
    });

  }

  var _templates = [];
    $.each(contractsTemplates, function (i, template) {
       _templates.push({
          url: admin_url + 'contracts/get_template?name=' + template,
          title: template
       });
    });
var selector = typeof(selector) == 'undefined' ? 'div.editable' : selector;   
    var _editor_selector_check = $(selector);

    if (_editor_selector_check.length === 0) { return; }

    $.each(_editor_selector_check, function() {
        if ($(this).hasClass('tinymce-manual')) {
            $(this).removeClass('tinymce');
        }
    });

    var editor_settings = {
       branding: false,
     selector: selector,
     browser_spellcheck: true,
     height: 400,
     theme: 'modern',
     skin: 'perfex',
     language: app.tinymce_lang,
       relative_urls: false,
       remove_script_host: false,
       inline_styles: true,
       verify_html: false,
       cleanup: false,
       apply_source_formatting: false,
       valid_elements: '+*[*]',
       valid_children: "+body[style], +style[type]",
       file_browser_callback: elFinderBrowser,
       table_default_styles: {
          width: '100%'
       },
       fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
       pagebreak_separator: '<p pagebreak="true"></p>',
       plugins: [
          'advlist pagebreak autolink autoresize lists link image charmap hr',
          'searchreplace visualblocks visualchars code',
          'media nonbreaking table contextmenu',
          'paste textcolor colorpicker'
       ],       
        tinycomments_mode: 'embedded',
        tinycomments_author: app.current_user,
       autoresize_bottom_margin: 50,
       toolbar: 'fontselect fontsizeselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | image link | bullist numlist | restoredraft',
       insert_toolbar: 'image media quicktable | bullist numlist | h2 h3 | hr',
       selection_toolbar: 'save_button bold italic underline superscript | forecolor backcolor link | alignleft aligncenter alignright alignjustify | fontselect fontsizeselect h2 h3',
       contextmenu: "image media inserttable | cell row column deletetable | paste pastetext searchreplace | visualblocks pagebreak charmap | code",
       setup: function (editor) {

          editor.addCommand('mceSave', function () {
             save_contract_content(true);
          });

          editor.addShortcut('Meta+S', '', 'mceSave');

          editor.on('MouseLeave blur', function () {
             if (tinymce.activeEditor.isDirty()) {
                save_contract_content();
             }
          });

          editor.on('MouseDown ContextMenu', function () {
             if (!is_mobile() && !$('.left-column').hasClass('hide')) {
                contract_full_view();
             }
          });

          editor.on('blur', function () {
             $.Shortcuts.start();
          });

          editor.on('focus', function () {
             $.Shortcuts.stop();
          });

       }
    }

    if (_templates.length > 0) {
       editor_settings.templates = _templates;
       editor_settings.plugins[3] = 'template ' + editor_settings.plugins[3];
       editor_settings.contextmenu = editor_settings.contextmenu.replace('inserttable', 'inserttable template');
    }

     if(is_mobile()) {

          editor_settings.theme = 'modern';
          editor_settings.mobile    = {};
          editor_settings.mobile.theme = 'mobile';
          editor_settings.mobile.toolbar = _tinymce_mobile_toolbar();

          editor_settings.inline = false;
          window.addEventListener("beforeunload", function (event) {
            if (tinymce.activeEditor.isDirty()) {
               save_contract_content();
            }
         });
     }

    tinymce.init(editor_settings);
    <?php if(isset($contract)){ ?>

   SignaturePad.prototype.toDataURLAndRemoveBlanks = function() {
     var canvas = this._ctx.canvas;
       // First duplicate the canvas to not alter the original
       var croppedCanvas = document.createElement('canvas'),
       croppedCtx = croppedCanvas.getContext('2d');

       croppedCanvas.width = canvas.width;
       croppedCanvas.height = canvas.height;
       croppedCtx.drawImage(canvas, 0, 0);

       // Next do the actual cropping
       var w = croppedCanvas.width,
       h = croppedCanvas.height,
       pix = {
         x: [],
         y: []
       },
       imageData = croppedCtx.getImageData(0, 0, croppedCanvas.width, croppedCanvas.height),
       x, y, index;

       for (y = 0; y < h; y++) {
         for (x = 0; x < w; x++) {
           index = (y * w + x) * 4;
           if (imageData.data[index + 3] > 0) {
             pix.x.push(x);
             pix.y.push(y);

           }
         }
       }
       pix.x.sort(function(a, b) {
         return a - b
       });
       pix.y.sort(function(a, b) {
         return a - b
       });
       var n = pix.x.length - 1;

       w = pix.x[n] - pix.x[0];
       h = pix.y[n] - pix.y[0];
       var cut = croppedCtx.getImageData(pix.x[0], pix.y[0], w, h);

       croppedCanvas.width = w;
       croppedCanvas.height = h;
       croppedCtx.putImageData(cut, 0, 0);

       return croppedCanvas.toDataURL();
     };


     function signaturePadChanged() {

       var input = document.getElementById('signatureInput');
       var $signatureLabel = $('#signatureLabel');
       $signatureLabel.removeClass('text-danger');

       if (signaturePad.isEmpty()) {
         $signatureLabel.addClass('text-danger');
         input.value = '';
         return false;
       }

       $('#signatureInput-error').remove();
       var partBase64 = signaturePad.toDataURLAndRemoveBlanks();
       partBase64 = partBase64.split(',')[1];
       input.value = partBase64;
     }

     var canvas = document.getElementById("signature");
     var signaturePad = new SignaturePad(canvas, {
      maxWidth: 2,
      onEnd:function(){
        signaturePadChanged();
      }
    });

     $('#identityConfirmationForm').submit(function() {
       signaturePadChanged();
     });

<?php } ?>


})(jQuery);

function view_pur_order(invoker){
  "use strict";
  var pur_order = invoker.value;
  if(pur_order != ''){
    $.post(admin_url + 'purchase/view_pur_order/'+pur_order).done(function(response){
        response = JSON.parse(response);
        $('select[name="vendor"]').val(response.vendor).change();
        $('input[name="contract_value"]').val(response.total);
        $('select[name="buyer"]').val(response.buyer).change();
    });
  }else{
    alert_float('warning', '<?php echo _l('please_chose_pur_order'); ?>');
  }
}


   function save_contract_content(manual) {
    "use strict";
    var editor = tinyMCE.activeEditor;
    var data = {};
    data.contract_id = contract_id;
    data.content = editor.getContent();
    $.post(admin_url + 'purchase/save_contract_data', data).done(function (response) {
       response = JSON.parse(response);
       if (typeof (manual) != 'undefined') {
          // Show some message to the user if saved via CTRL + S
          alert_float('success', response.message);
       }
       // Invokes to set dirty to false
       editor.save();
    }).fail(function (error) {
       var response = JSON.parse(error.responseText);
       alert_float('danger', response.message);
    });
   }

   function contract_full_view() {
    "use strict";
    $('.left-column').toggleClass('hide');
    $('.right-column').toggleClass('col-md-7');
    $('.right-column').toggleClass('col-md-12');
    $(window).trigger('resize');
   }
  function accept_action() {
    "use strict";
      $('#add_action').modal('show');
  }

  function signature_clear(){
    "use strict";
    var canvas = document.getElementById("signature");
    var signaturePad = new SignaturePad(canvas, {
      maxWidth: 2,
      onEnd:function(){
        //signaturePadChanged();
      }
    });
    signaturePad.clear();
    //signaturePadChanged();
  }

  function sign_request(id){
    "use strict";
    change_signed_status(id,'signed');
  }

  function change_signed_status(request_id, status){
    "use strict";
      var data = {};
      data.status = status;
      data.signature = $('input[name="signature"]').val();
      
      $.post(admin_url + 'purchase/sign_contract/' + request_id, data).done(function(response){
          response = JSON.parse(response); 
          if (response.success === true || response.success == 'true') {
              alert_float('success', response.message);
              window.location.reload();
          }
      });
  }

function preview_ic_btn(invoker){
    "use strict";
    var id = $(invoker).attr('id');
    var rel_id = $(invoker).attr('rel_id');
    view_ic_file(id, rel_id);
}

function view_ic_file(id, rel_id) {
    "use strict";
      $('#ic_file_data').empty();
      $("#ic_file_data").load(admin_url + 'purchase/file_pur_contract/' + id + '/' + rel_id, function(response, status, xhr) {
          if (status == "error") {
              alert_float('danger', xhr.statusText);
          }
      });
}

function close_modal_preview(){
    "use strict";
 $('._project_file').modal('hide');
}

function delete_ic_attachment(id) {
    "use strict";
    if (confirm_delete()) {
        requestGet('purchase/delete_pur_contract_attachment/' + id).done(function(success) {
            if (success == 1) {
                $("#ic_pv_file").find('[data-attachment-id="' + id + '"]').remove();
            }
        }).fail(function(error) {
            alert_float('danger', error.responseText);
        });
    }
  }

</script>