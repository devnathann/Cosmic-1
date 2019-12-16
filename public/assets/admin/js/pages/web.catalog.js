var catalog = function() {

    return {
        init: function() {

            $(".goBackWizard").unbind().click(function() {
                catalog.goBack();
            });

            $.ajax({
                url: '/housekeeping/api/catalog/getcatalogpages',
                type: "post",
                headers: {
                    "Authorization": 'housekeeping'
                },
                dataType: 'json',
                beforeSend: function() {
                    blockPageInterface.init();
                },
                success: function(data) {
                    catalog.initDatatable(data);
                }
            });
        },

        goBack: function() {
            $("#catalogList").show();
            $("#eCatalogPage").hide();
            $("#eItemPage").hide();

            $("#kt_datatable_catalog_itemlist").KTDatatable("destroy");
            $("#kt_datatable_catalog_itemlist").attr("id","kt_datatable_catalog");

            catalog.init();
        },

        initDatatable: function (jsonObj) {
            $(".addCatalogPage").html("Add page");

            $(".addCatalogPage").unbind().click(function() {
                catalog.addPage();
            });

            $(".kt-portlet__head-title").html("Catalog pages");
            var datatableCatalogPage = function () {

                if ($('#kt_datatable_catalog').length === 0) {
                    return;
                }

                var t;
                $("#kt_datatable_catalog").KTDatatable({
                     data: {
                         type: 'local',
                         source: jsonObj,
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
                        width: 75
                    }, {
                        field: "caption",
                        title: "Caption",
                        template: function (data) {
                            return '<span class="kt-font">' + data.caption + '</span>';
                        }
                    }, {
                        field: "page_texts",
                        title: "Page texts",
                        sortable: "asc"
                    }, {
                        field: "page_layout",
                        title: "Page layout",
                        width: 130
                    }, {
                        field: "visible",
                        title: "Visible",
                        width: 130
                    }, {
                        field: "enabled",
                        title: "Enabled",
                        width: 130
                    }, {
                        field: "Actions",
                        title: "Actions",
                        sortable: !1,
                        width: 110,
                        overflow: "visible",
                        textAlign: "left",
                        autoHide: !1,
                        template: function () {
                            return '<a class="btn btn-sm btn-clean btn-icon btn-icon-sm" id="editCatalog" title="Edit"><i class="flaticon2-edit"></i></a>'
                        }
                    }]
                });

                $("#kt_datatable_catalog_reload").on("click", function () {
                    $("#kt_datatable_catalog").KTDatatable("reload")
                });
            };

            $("#kt_datatable_catalog").unbind().on("click", "#editCatalog, #deleteFaq", function (e) {
                e.preventDefault();
                let id = $(e.target).closest('.kt-datatable__row').find('[data-field="id"]').text();
                catalog.editCatalog(id, jsonObj);
            });

            datatableCatalogPage();
        },

        addPage: function () {
            $('#catalogForm').trigger("reset");
            $("#eCatalogPage").show();
            $("#catalogList").hide();
            $("#eCatalogPage [name=object]").val('add');
        },

        editCatalog: function (id, jsonObj) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            self.ajax_manager.post("/housekeeping/api/catalog/getcatalogbypageid", {post: id}, function (result) {
                $("#eCatalogPage").show();

                $("[name=catid]").val(result.page.id);
                $("[name=caption]").val(result.page.caption);
                $("[name=page_teaser]").val(result.page.page_teaser);
                $("[name=page_headline]").val(result.page.page_headline);
                $("[name=page_images]").val(result.page.page_images);
                $("#eCatalogPage [name=object]").val('edit');

                for (var i = 0; i < jsonObj.length; i++){
                    var parent_page = jsonObj[i];
                    $("[name=parent_id]").append(new Option(parent_page.caption, parent_page.id));
                }

                $("#selectPage option[value='" + result.page.parent.id + "']").prop('selected', true);
                $("#pagelayout option[value='" + result.page.page_layout + "']").prop('selected', true);
                $("#enabled option[value='" + result.page.enabled + "']").prop('selected', true);
                $("#visible option[value='" + result.page.visible + "']").prop('selected', true);

                $("#selectPage").select2();

                catalog.createItemList(result.page.id, jsonObj);
            });
        },

        createItemList: function (id, jsonObj) {

            $(".addItem").html("Add item");

            $(".addItem").unbind().click(function() {
                catalog.addItem(jsonObj);
            });

            $(".kt-portlet__head-title").html("Items");
            $("#kt_datatable_catalog").KTDatatable("destroy");
            $("#kt_datatable_catalog").attr("id","kt_datatable_catalog_itemlist");

            var datatableCatalogItemPage = function () {

                if ($('#kt_datatable_catalog_itemlist').length === 0) {
                    return;
                }

                var t;
                $("#kt_datatable_catalog_itemlist").KTDatatable({
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                url: '/housekeeping/api/catalog/getCatalogItemsByItemId',
                                params: {
                                   "id": id
                                },
                                headers: {
                                    'Authorization': 'housekeeping_server_catalog'
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
                        input: $("#generalSearch")
                    },
                    columns: [{
                        field: "item_ids",
                        title: "#",
                        type: "number",
                        width: 75
                    }, {
                        field: "catalog_name",
                        title: "Name",
                        width: 200
                    }, {
                        field: "cost_credits",
                        title: "Credits",
                    }, {
                        field: "cost_points",
                        title: "Points"
                    }, {
                        field: "club_only",
                        title: "Club only"
                    }, {
                        field: "Actions",
                        title: "Actions",
                        sortable: !1,
                        width: 110,
                        overflow: "visible",
                        textAlign: "left",
                        autoHide: !1,
                        template: function () {
                            return '<a class="btn btn-sm btn-clean btn-icon btn-icon-sm" id="editItem" title="Edit"><i class="flaticon2-edit"></i></a>'
                        }
                    }]
                });

                $("#kt_datatable_catalog_reload").on("click", function () {
                    $("#kt_datatable_catalog_itemlist").KTDatatable("reload")
                });
            };

            $("#kt_datatable_catalog_itemlist").unbind().on("click", "#editItem", function (e) {
                e.preventDefault();
                let id = $(e.target).closest('.kt-datatable__row').find('[data-field="item_ids"]').text();
                catalog.editItem(id, jsonObj);
            });

            datatableCatalogItemPage();
        },

        addItem: function(jsonObj) {
            $('#itemForm').trigger("reset");
            $("#eCatalogPage").hide();
            $("#catalogList").hide();
            $("#eItemPage").show();
            $("#eItemPage [name=object]").val('add');

            for (var o = 0; o < jsonObj.length; o++){
                var pages = jsonObj[o];
                $("[name=page_id]").append(new Option(pages.caption, pages.id));
            }
        },

        editItem: function(id, jsonObj) {
            var self = this;
            this.ajax_manager = new WebPostInterface.init();

            self.ajax_manager.post("/housekeeping/api/catalog/getFurnitureById", {post: id}, function (result) {
                $("html, body").animate({ scrollTop: 0 }, "slow");

                $("#eItemPage").show();
                $("#eCatalogPage").hide();
                $("#eItemPage [name=object]").val('edit');
              
                $("[name=id]").val(result.furniture.id);
                $("[name=item_name]").val(result.furniture.public_name);
                $("[name=public_name]").val(result.furniture.item_name);
                $("[name=width]").val(result.furniture.width);
                $("[name=length]").val(result.furniture.length);
                $("[name=stack_height]").val(result.furniture.stack_height);
              
                for (var o = 0; o < jsonObj.length; o++){
                    var pages = jsonObj[o];
                    $("[name=page_id]").append(new Option(pages.caption, pages.id));
                }

                $("[name=allow_stack] option[value='" + result.furniture.allow_stack  + "']").prop('selected', true);
                $("[name=allow_sit] option[value='" + result.furniture.allow_sit  + "']").prop('selected', true);
                $("[name=allow_lay] option[value='" + result.furniture.allow_lay  + "']").prop('selected', true);       
                $("[name=allow_walk] option[value='" + result.furniture.allow_walk  + "']").prop('selected', true);
                $("[name=allow_gift] option[value='" + result.furniture.allow_gift  + "']").prop('selected', true);
                $("[name=allow_trade] option[value='" + result.furniture.allow_trade  + "']").prop('selected', true);
                $("[name=allow_recycle] option[value='" + result.furniture.allow_recycle  + "']").prop('selected', true);
                $("[name=allow_marketplace_sell] option[value='" + result.furniture.allow_marketplace_sell  + "']").prop('selected', true);
                $("[name=allow_inventory_stack] option[value='" + result.furniture.allow_inventory_stack  + "']").prop('selected', true);
                $("[name=type] option[value='" + result.furniture.type  + "']").prop('selected', true);
                $("[name=interaction_type] option[value='" + result.furniture.interaction_type  + "']").prop('selected', true);
                $("[name=interaction_modes_count]").val(result.furniture.interaction_modes_count);

                $("[name=page_id] option[value='" + result.itemsids.page_id  + "']").prop('selected', true);
                $("[name=catalog_name]").val(result.itemsids.catalog_name);
                $("[name=cost_credits]").val(result.itemsids.cost_credits);
                $("[name=cost_points]").val(result.itemsids.cost_points);
                $("[name=points_type]").val(result.itemsids.points_type);
                $("[name=amount]").val(result.itemsids.amount);
                $("[name=limited_sells]").val(result.itemsids.limited_sells);
                $("[name=limited_stack]").val(result.itemsids.limited_stack);

                $("#selectPage").select2();
            });
        }
    }
}();

jQuery(document).ready(function() {
    catalog.init();
});