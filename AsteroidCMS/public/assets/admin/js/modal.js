$('#actionModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget)
  var recipient = button.data('id')
  var modal = $(this)

  if(recipient != null) {
      modal.find('.modal-title').text('Action to ' + recipient)
      modal.find('.modal-footer').html('<button type=\"button\" class=\"btn btn-success\" data-toggle=\"modal\" data-target=\"#alertModal\" class=\"btn btn-success\" data-id=\"' + recipient + '\">Alert</button><button type=\"button\" class=\"btn btn-danger\" data-toggle=\"modal\" data-target=\"#banModal\" data-id=\"' + recipient + '\">Ban</button><a href=\"/housekeeping/remote/user/view/' + recipient + '\" class=\"btn btn-secondary\">Manage User</a>')
      modal.find('.modal-body input').val(recipient)
  }
});

$('#alertModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget)
  var recipient = button.data('id')
  var modal = $(this)

  modal.find('.modal-body #inputUsername').val(recipient);
  
});

$('#banModal').click().on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget)
  var recipient = button.data('id')
  var modal = $(this)

  modal.find('.modal-body #inputUsername').val(recipient);
  
  $.ajax({
      url: '/housekeeping/api/search/banfields',
      type: "post",
      headers: {
          "Authorization": 'housekeeping_remote_control'
      },
      dataType: 'json',
      success: function(data) {
          for (var i = 0; i < data.banmessages.length; i++){
              var parent_page = data.banmessages[i];
              modal.find('[name=reason]').append(new Option(parent_page.message, parent_page.id));
          }  
          for (var x = 0; x < data.bantime.length; x++){
              var parentc_page = data.bantime[x];
              modal.find('[name=expire]').append(new Option(parentc_page.message, parentc_page.id));
          }  
      }
  });
  
});

$('#ipremoteLogs').on('show.bs.modal', function (event) {
    $("#datatableAccounts").KTDatatable("destroy");

    var button = $(event.relatedTarget)
    var recipient = button.data('id')
    var modal = $(this)

    modal.find('.modal-title').html("User accounts by access logs");

    var datatableIpLogs = function() {

    if ($('#datatableAccounts').length === 0) {
    return;
    }

    var t;
    $("#datatableAccounts").KTDatatable({
      data: {
        type: 'remote',
        source: {
          read: {
            url: '/housekeeping/api/remote/ip',
            params: {
              "access_id": recipient
            },
            headers: {'Authorization': 'housekeeping_remote_control' }
          }
        },
        pageSize: 10
      },
       layout: {
           scroll: !1,
           footer: !1
       },
       sortable: !0,
       pagination: !0,
       search: {
           input: $("#generalSearch")
       },
       columns: [{
           field: "id",
           title: "#",
           type: "number",
           width: 40
       }, {
           field: "username",
           title: "Player",
           template: function(data) {
               return '<span class="kt-font"><a href="#" class="kt-user-card-v2__name" data-toggle="modal" data-target="#actionModal" data-id="' + data.username + '">' + data.username + '</a></span>';
           }
       }, {
           field: "ip_last",
           title: "IP Adress",
           template: function(data) {
               return '<span class="kt-font">' + data.ip_last + ' / ' + data.ip_reg + '</a></span>';
           }
       }, {
           field: "last_online",
           title: "Timestsamp",
           template: function(data) {
               return '<span class="kt-font">' + data.last_online + '</span>';
           }
       }]
    }), $("#kt_datatable_reload").on("click", function() {
       $("#datatableAccounts").KTDatatable("reload")
    })
    }

    datatableIpLogs();

    if($.trim( $('#datatableAccounts').html() ).length) {
      $("#datatableAccounts").KTDatatable("reload");
    }
});