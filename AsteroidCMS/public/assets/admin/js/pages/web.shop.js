var shop = function() {

    return {
        init: function () {
            shop.initDatatable();
          
            $('#viewPaysafe').on('show.bs.modal', function (event) {
                shop.paysafeView();
            });
          
            $('#giveOffer').on('show.bs.modal', function (event) {
                shop.giveOffer();
            });
        },

        initDatatable: function () {

            var datatableShop = function () {

                if ($('#kt_datatable_shop').length === 0) {
                    return;
                }

                var t;
                $("#kt_datatable_shop").KTDatatable({
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                url: '/housekeeping/api/shop/getpurchaselogs',
                                headers: {
                                    'Authorization': 'housekeeping_shop_control'
                                }
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
                        input: $("#searchLatestPlayers")
                    },
                    columns: [{
                        field: "id",
                        title: "#",
                        type: "number",
                        width: 75,
                        sortable: "desc",
                        template: function (data) {
                            return '<span class="kt-font">' + data.id + '</span>';
                        }
                    }, {
                        field: "player_id",
                        title: "Username",
                    }, {
                        field: "data",
                        title: "Data",
                        sortable: "asc",
                    }, {
                        field: "timestamp",
                        title: "Timestamp",
                    }]
                });

                $("#kt_datatable_shop_reload").on("click", function () {
                    $("#kt_datatable_faq").KTDatatable("reload")
                });
            };

            $("#kt_datatable_shop").unbind().on("click", "#editFaq, #deleteFaq", function (e) {
                e.preventDefault();
                let id = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();
                let title = $(e.target).closest('.kt-datatable__row').find('[data-field="title"]').text();
            });

            datatableShop();
        },
      
          paysafeView: function () {

             $("#paysafeModal").KTDatatable({
                   data: {
                  type: 'remote',
                  source: {
                      read: {
                          url: '/housekeeping/api/shop/getpaysafelogs',
                          headers: {
                              'Authorization': 'housekeeping_shop_control'
                          }
                      }
                  },
                      pageSize: 10,
                      serverPaging: !0,
                      serverFiltering: !0,
                      serverSorting: !0
                  },
                  layout: {
                      scroll: !0,
                      footer: !1
                  },
                  sortable: !0,
                  pagination: !0,
                  columns: [{
                      field: "id",
                      title: "#",
                      type: "number"
                  }, {
                      field: "player_id",
                      title: "Username"
                  }, {
                      field: "amount",
                      title: "Amount",
                  }, {
                      field: "code",
                      title: "Code"
                  }, {
                      field: "Actions",
                      title: "Actions",
                      sortable: !1,
                      overflow: "visible",
                      autoHide: !1,
                      template: function() {
                          return '\t\t\t\t\t\t\t<div class="dropdown">\t\t\t\t\t\t\t\t<a href="javascript:;" id="" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">\t                                <i class="la la-ellipsis-h"></i>\t                            </a>\t\t\t\t\t\t\t    <div class="dropdown-menu dropdown-menu-right">\t\t\t\t\t\t\t        <a class="dropdown-item" href="#"><i class="la la-edit"></i> Edit Details</a>\t\t\t\t\t\t\t        <a class="dropdown-item" href="#"><i class="la la-leaf"></i> Update Status</a>\t\t\t\t\t\t\t        <a class="dropdown-item" href="#"><i class="la la-print"></i> Generate Report</a>\t\t\t\t\t\t\t    </div>\t\t\t\t\t\t\t</div>\t\t\t\t\t\t\t<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md acceptPaysafeReq" id="acceptPaysafeReq" title="Edit details">\t\t\t\t\t\t\t\t<i class="la la-check"></i>\t\t\t\t\t\t\t</a>\t\t\t\t\t\t\t<a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md declinePaysafeReq" title="Delete">\t\t\t\t\t\t\t\t<i class="la la-trash"></i>\t\t\t\t\t\t\t</a>\t\t\t\t\t\t'
                      }
                  }]
              });
            
              $("#viewPaysafe").unbind().on("click", ".declinePaysafeReq, .acceptPaysafeReq", function(e) {
                  e.preventDefault();
                  let id = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();
                
                  if($(this).attr('id') == "acceptPaysafeReq") {
                      shop.acceptPaysafeReq(id);
                  } else {
                      shop.declinePaysafeReq(id);
                  }
              });
        },
      
        acceptPaysafeReq: function(id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            self.ajax_manager.post("/housekeeping/api/shop/accept", {
                post: id
            }, function (result) {
                if (result.status == "success") {
                    $("#paysafeModal").KTDatatable("reload");
                }
            });
        },
      
        declinePaysafeReq: function(id) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            self.ajax_manager.post("/housekeeping/api/shop/decline", {
                post: id
            }, function (result) {
                if (result.status == "success") {
                    $("#paysafeModal").KTDatatable("reload");
                }
            });
        },
      
        giveOffer: function() {
            console.log(1)
        }
    }
}();

jQuery(document).ready(function() {
    shop.init();
});