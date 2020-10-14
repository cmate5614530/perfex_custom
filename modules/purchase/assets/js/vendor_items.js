
  function group_it_change(invoker) {
    "use strict";
    if(invoker.value != ''){
      $.post(admin_url + 'purchase/group_it_change/'+invoker.value).done(function(response){
        response = JSON.parse(response);
        if(response.html != ''){
          $('select[id="items"]').html('');
          $('select[id="items"]').append(response.html);
          $('select[id="items"]').selectpicker('refresh');
        }else{
          $('select[id="items"]').html('');
          $('select[id="items"]').selectpicker('refresh');
        }
      });
    }else{
      $.post(admin_url + 'purchase/group_it_change/'+invoker.value).done(function(response){
        response = JSON.parse(response);
        if(response.html != ''){
          $('select[id="items"]').html('');
          $('select[id="items"]').append(response.html);
          $('select[id="items"]').selectpicker('refresh');
        }else{
          $('select[id="items"]').html('');
          $('select[id="items"]').selectpicker('refresh');
        }
      });
    }
  }
