"use strict";

var WebPostInterface = function() {

    return {
        init: function() {
            /*
             * Post method
             * */
            this.post = function(url, data, callback, form) {
                // Prepare data
                if (!(data instanceof FormData)) {
                    if (!(data instanceof Object))
                        return;

                    var data_source = data;
                    data = new FormData();
                    for (var key in data_source) {
                        if (!data_source.hasOwnProperty(key))
                            continue;

                        data.append(key, data_source[key]);
                    }
                }

                // Check form name
                if (form !== undefined) {
                    if (form.attr("action") === "login")
                        data.append("return_url", window.location.href);
                }

                // Requests
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    dataType: "json",
                    headers: {
                        "Authorization": "housekeeping"
                    },
                    processData: false,
                    contentType: false
                }).done(function(result) {
                    // Change full page
                    if (result.location) {
                        window.location = result.location;
                        return null;
                    }
                  
                    if(isEmpty(result.status)) {
                        if (typeof callback === "function")
                            callback(result);
                            return null;
                    }

                    // Change page
                    if (result.pagetime)
                        setTimeout(function() {
                            window.location = result.pagetime
                        }, 2500);
                    
                    // Create notification
                    if (!isEmpty(result.status) && !isEmpty(result.message))
                      
                        if(result.status == "success") {
                            $('.modal').modal('hide');
                        }
                      
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": true,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        };

                    if(result.message !== undefined) {
                        toastr[result.status](result.message);
                    }

                    // Callback if exists
                    if (typeof callback === "function")
                        callback(result);
                });
            };
        }
    }
}();

var LoadingPage = function() {

    return {
        init: function() {
            this.web_document = $("body");
            var self = this;
  
            this.ajax_manager = new WebPostInterface.init();
            this.web_document.on("submit", "form:not(.default-prevent)", function(event) {
                event.preventDefault();

                if ($(this).attr("method") !== "get")
                    self.ajax_manager.post("/housekeeping/api/" + $(this).attr("action"), new FormData(this), null, $(this));
                else {
                    return true;
                }
            });
        }
    }
}();

function isEmptyObj(obj) {
    return Object.keys(obj).length === 0;
}

function isEmpty(str) {
    if (typeof str === "string")
        str = str.trim();

    return (!str || 0 === str.length);
}

var install = function() {
  
      var settings = [];
  
      return {
          init: function() {
                $("#connectionCheck").click(function() {
                    install.dbConnection();
                });
            
                $("#createFromNull").click(function() {
                    install.createUser();
                });

          },
        
          dbConnection: function () {
              var self = this;
              this.web = new WebPostInterface.init();
            
              var dbSettings = {
                  'host': $("#dbconnection [name=host]").val(),
                  'username': $("#dbconnection [name=username]").val(),
                  'database': $("#dbconnection [name=database]").val(),
                  'password': $("#dbconnection [name=password]").val(),
              };
            
              self.web.post("/installation/api/home/dbconnection", dbSettings, function(result) {

                  if(result.status == 'success'){
                      $("#connectionCheck").hide();
                    
                      setTimeout(function() { 
                          $("#createTables").show();
                          toastr['success']('Database connection success and saved!');
                      }, 2500);
                    
                      $("#dbconnection [name=host]").prop("readonly", true);
                      $("#dbconnection [name=username]").prop("readonly", true);
                      $("#dbconnection [name=database]").prop("readonly", true);
                      $("#dbconnection [name=password]").prop("readonly", true);
                    
                      $("#createTables").click(function() {
                        
                          toastr['info']('Please wait, we are creating the database table to your database.');
                          $("#createTables").hide();

                          self.web.post("/installation/api/home/createtables", dbSettings, function(result) {

                          if(result.status == 'success') {
                              $("#usersettings").show();
                              $('html, body').animate({
                                  scrollTop: $("#usersettings").offset().top
                              }, 2000);
                            
                              install.userSettings();
                          } else {
                              $("#createTables").show();
                          }
                        });
                      });
                  } else {
                      $("#connectionCheck").removeClass("btn btn-primary");
                      $("#connectionCheck").addClass("btn btn-warning").html('Connection failure..');
                  }
              });
          },
        
          userSettings: function() {
              $("#createUser").click(function() {
                    install.createUser();                    
              });
          },
        
          createUser: function () {
              var self = this;
              this.web = new WebPostInterface.init();

              var userSettings = {
                  'username': $("#usersettings [name=username]").val(),
                  'password': $("#usersettings [name=password]").val(),
                  'password_repeat': $("#usersettings [name=password_repeat]").val(),
                  'email': $("#usersettings [name=mail]").val()
              };

              self.web.post("/installation/api/home/createuser", userSettings, function(result) {
                  if(result.status == 'success') {
                      install.configSettings();
                  } 
              });
          },
        
          configSettings: function() {
              var rand = function() {
                  return Math.random().toString(36).substr(2); 
              };

              var token = function() {
                  return 'COSMIC-' + rand() + rand(); 
              }; 

              $("#webSettings [name=domain]").val(window.location.host);
              $("#webSettings [name=path]").val(window.location.origin);
              $("#webSettings [name=SECRET_TOKEN]").val(token);

              $("#settings").show();

              $('html, body').animate({
                  scrollTop: $("#settings").offset().top
              }, 2000);
            
              $("#createUser").hide();
            
              $("#complete").click(function() {
                    var self = this;
                    this.web = new WebPostInterface.init();
                
                    var configuration = {
                        'SECRET_TOKEN': $("#webSettings [name=SECRET_TOKEN]").val(),
                        'domain': $("#webSettings [name=domain]").val(),
                        'path': $("#webSettings [name=path]").val(),
                        'swfPath': $("#webSettings [name=swfPath]").val(),
                        'figurePath': $("#webSettings [name=figurePath]").val(),
                        'clientHost': $("#webSettings [name=clientHost]").val(),
                        'clientPort': $("#webSettings [name=clientPort]").val(),
                        'apiHost': $("#webSettings [name=apiHost]").val(),
                        'apiPort': $("#webSettings [name=apiPort]").val(),
                        'siteName': $("#webSettings [name=siteName]").val(),
                        'shortName': $("#webSettings [name=shortName]").val(),
                        'currencys': $("#webSettings [name=currencys]").val(),
                        'payCurrency': $("#webSettings [name=payCurrency]").val(),
                        'credits': $("#webSettings [name=credits]").val(),
                        'pixels': $("#webSettings [name=pixels]").val(),
                        'points': $("#webSettings [name=points]").val(),
                        'publicKey': $("#webSettings [name=publicKey]").val(),
                        'secretKey': $("#webSettings [name=secretKey]").val(),
                        'language': $("#webSettings [name=language]").val()
                    };

                    self.web.post("/installation/api/home/complete", configuration, function(result) {
                        if(result.status == 'success'){
                            setTimeout(function() { 
                                window.location.replace(configuration.path);
                            }, 3000);
                        }
                    });
              });
          }
      }
}();

// Class initialization on page load
jQuery(document).ready(function() {
    install.init();
});