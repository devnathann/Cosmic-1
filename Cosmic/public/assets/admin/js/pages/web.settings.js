jQuery(document).ready(function() {
  
    $('.targetRole').select2({
        placeholder: 'Select a role',
        width: '85%',
        ajax: {
            url: '/housekeeping/search/get/role',
            headers: {
                "Authorization": "housekeeping_permissions"
            },
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    }); 
  
// The DOM element you wish to replace with Tagify
var input = document.querySelector('input[name=vip_badges]');

// init Tagify script on the above inputs
new Tagify(input);
  
  
  $('.targetCurrency').select2({
      placeholder: 'Select a currency',
      width: '85%',
      ajax: {
          url: '/housekeeping/search/get/currencys',
          headers: {
              "Authorization": "housekeeping_permissions"
          },
          dataType: 'json',
          delay: 250,
          data: function (params) {
              return {
                  searchTerm: params.term
              };
          },
          processResults: function (data) {
              return {
                  results: data
              };
          },
          cache: true
      }
  }); 
  

  
    tinymce.init({
        selector: "textarea",
        width: '100%',
        height: 270,
        plugins: "advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table contextmenu directionality emoticons template paste textcolor colorpicker textpattern imagetools codesample",
        statusbar: true,
        menubar: true,
        toolbar: "undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    });
});