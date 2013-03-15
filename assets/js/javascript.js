$(document).ready(function() {
    $(".hide_yes_js").hide();
    $(".hide_no_js").show();

    $(".confirm").click(function(e) {
		
		var are_you_sure = "<span id=\"confirmation_message\"></span>";
		if ($(this).attr("title").indexOf("?") <= 0) {
			are_you_sure = "Are you sure you want to <span id=\"confirmation_message\"></span>?";
		}

        if ($("#confirmModal").length == 0) {		
            $(".site-container").append(
                "<div class=\"modal hide\" id=\"confirmModal\">" + 
                "<div class=\"modal-header\">" + 
                "<button type=\"button\" class=\"close\" data-dismiss=\"modal\">×</button>" + 
                "<h3>Confirmation needed</h3>" + 
                "</div>" + 
                "<div class=\"modal-body\">" + 
                "<input type=\"hidden\" id=\"confirmation_url\" value=\"\" />" + 
                "<p>"+are_you_sure+"</p>" + 
                "</div>" + 
                "<div class=\"modal-footer\">" + 
                "<a href=\"javascript:;\" class=\"btn\" data-dismiss=\"modal\">No</a>" + 
                "<a href=\"javascript:;\" id=\"confirmation_accept\" class=\"btn btn-primary\">Yes</a>" + 
                "</div>" + 
                "</div>"
                );
        }
		
        $('#confirmModal').modal('show');
        $("#confirmation_message").text($(this).attr("title"));
        $("#confirmation_url").val($(this).attr("href"));

        return false;

    //return confirm("Are you sure you want to " + $(this).attr("title") + "?");
    });
    $("#confirmation_accept").live("click", function() {
        url = $("#confirmation_url").val();
        location.href = url;
    });

    $('.datepick').datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true,
        firstDay: 1,
        onSelect: function() {
            $(this).blur();
        }
    }).attr("autocomplete", "off");
	
    $(".datepick.future").datepicker("option", "minDate", "0");
	
	$(".basic_tinymce").tinymce({
        // Location of TinyMCE script
        script_url : homeurl+'js/tinymce/jscripts/tiny_mce/tiny_mce.js',
        //language: "es",
	
        // General options
        theme : "advanced",
        plugins : "autolink,lists,spellchecker,pagebreak,style,layer,,advlink,contextmenu,",
        entity_encoding  : "raw",
        document_base_url : "",
        force_p_newlines : true,
        relative_urls : false,
        fix_table_elements : true,
        removeformat_selector : "span,b,strong,i,em,table",
	
	
        // Theme options
        theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,link,unlink,|,forecolor,fontsizeselect",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_buttons4 : "",

        paste_auto_cleanup_on_paste : true,
        paste_preprocess : function(pl, o) {
            // Content string containing the HTML from the clipboard
            //alert(o.content);
            o.content = o.content;
        },
        paste_postprocess : function(pl, o) {
            // Content DOM node containing the DOM structure of the clipboard
            //alert(o.node.innerHTML);
            o.node.innerHTML = o.node.innerHTML;
        },
        theme_advanced_buttons4 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
        theme_advanced_resizing_min_width : 640,
        theme_advanced_resizing_max_width : 640,
	
	
        // Example content CSS (should be your site CSS)
        content_css : '/js/tinymce/jscripts/tiny_mce/themes/advanced/skins/default/content.css'
    });
	
    $(".tinymce").tinymce({
        // Location of TinyMCE script
        script_url : homeurl+'js/tinymce/jscripts/tiny_mce/tiny_mce.js',
        //language: "es",
	
        // General options
        theme : "advanced",
        plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
        entity_encoding  : "raw",
        document_base_url : "",
        force_p_newlines : true,
        relative_urls : false,
        fix_table_elements : true,
        removeformat_selector : "span,b,strong,i,em,table",
	
	
        // Theme options
        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",

        paste_auto_cleanup_on_paste : true,
        paste_preprocess : function(pl, o) {
            // Content string containing the HTML from the clipboard
            //alert(o.content);
            o.content = o.content;
        },
        paste_postprocess : function(pl, o) {
            // Content DOM node containing the DOM structure of the clipboard
            //alert(o.node.innerHTML);
            o.node.innerHTML = o.node.innerHTML;
        },
        theme_advanced_buttons4 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
        theme_advanced_resizing_min_width : 640,
        theme_advanced_resizing_max_width : 640,
	
	
        // Example content CSS (should be your site CSS)
        content_css : '/js/tinymce/jscripts/tiny_mce/themes/advanced/skins/default/content.css',
	
        // Replace values for the template plugin
        template_replace_values : {
            username : "Some User",
            staffid : "991234"
        }
    });
	
    function search_firm(e) {
        e.preventDefault();
		
        $.ajax({
            url: homeurl  + "firm/lookup_firm",
            data:[{
                name: "search_firm", 
                value: $("#search_firm").val()
            }],
            type:"POST",
            dataType:"json",
            beforeSend:function() {
            },
            success:function(e) {
                if (e.results) {
                    suggestions = "";

                    for (firm_id in e.results) {
                        suggestions += "<li><a class='select_firm' id='select_firm_"+firm_id+"' href='javascript:;'>" + e.results[firm_id] + "</a></li>";
                    }

                    if (!suggestions) {
                        suggestions += "<li>No firms returned for this search&nbsp;&nbsp;<input class='btn' type='submit' name='submit_no_firm' value='Register my firm' /></li>";
                    } else {
                        suggestions = "<li style='list-style-type:none;'>Choose firm from list below: </li>" + suggestions;
                    }

                    $("#search_results").html(suggestions).fadeIn("slow");
                }
            },
            error:function() {
            }
        });
    }
	
    $("#search_firm").keydown(function(e) {
        if (e.keyCode == 13)
        {
            search_firm(e);
            return false;
        }
    });
	
    $("#submit_search_firm").click(function(e) {
        search_firm(e);
    });

    $(".select_firm").live("click", function() {
        $(".select_firm").removeClass("active");
        $(this).addClass("active");

        $("#selected_firm_id").val($(this).attr("id").replace("select_firm_", ""));
        $("#search_firm").val($(this).text());
        $("#search_results").fadeOut("fast");
		
        $("#this_is_my_firm").show();
    });
    $("#check_all_areas").change(function() {
        if ($(this).attr("checked")) {
            $(".consumer_area_check,.corporate_area_check,#check_all_corporate,#check_all_consumer").attr("checked", "checked").removeAttr("disabled");
        }
    });
    $("#check_all_areas").change(function() {
        if ($(this).attr("checked")) {
            $(".area_check").attr("checked", "checked").removeAttr("disabled");
        }
    });
    $(".area_check").change(function() {
        id = $(this).attr("id").replace("area_", "");
		
        if ($(this).attr("checked")) {
            $(".area_check_"+id).removeAttr("disabled").attr("checked", "checked");
        } else {
            $(".area_check_"+id).attr("disabled", "disabled").removeAttr("checked");
        }
    });
    $(".post_request_tab").click(function() {
        id = $(this).attr("id").replace("request_", "") + "_tree";
		
        if ($(this).hasClass("active")) {
            $(this).parent("li").removeClass("active");
            $("#"+id).fadeOut("slow");
        } else {
            $(".post_request_tab").parent("li").removeClass("active");
            $(this).parent("li").addClass("active");

            $(".area_tree").hide();
            $("#"+id).fadeIn("slow");
        }
    });
	
    $(".check_include_all_counties").each(function() {
        id = $(this).attr("id").replace("include_all_counties_", "");
		
        if ($(this).attr("checked")) {
            $("#main_counties_"+id).val("");
            $("#main_counties_"+id).attr("disabled", "disabled");
            $("#main_counties_"+id).attr("readonly", "readonly");
        } else {
            $("#main_counties_"+id).removeAttr("disabled").removeAttr("readonly");
        }
    });
    $(".check_include_all_counties").change(function() {
        id = $(this).attr("id").replace("include_all_counties_", "");
		
        if ($(this).attr("checked")) {
            $("#main_counties_"+id).val("").attr("disabled", "disabled");
            $("#main_counties_"+id).attr("readonly", "readonly");
        } else {
            $("#main_counties_"+id).removeAttr("disabled").removeAttr("readonly");
        }
    });
	
    $(".tab_toggle").click(function() {
        id = $(this).attr("id").replace("toggle_", "");
		
        if ($("#"+id+":visible").length == 0) {
            $("#toggle_tab_list li").removeClass("active");
            $(this).parent().addClass("active");
			
            $(".toggle_div").hide();
            $("#"+id).fadeIn();
        }
    });
	
    function change_jurisdiction_selection(obj) {
        id = $(obj).attr("name").replace("area_", "");
		
        if ($(obj).val() == 0) {
            $("#include_all_main_"+id+",#include_all_counties_"+id).removeAttr("checked");
            $("#main_counties_"+id).val("");
            $("#include_all_main_"+id+",#include_all_counties_"+id+",#main_counties_"+id).attr("disabled", "disabled");
        } else {
            $("#include_all_main_"+id+",#include_all_counties_"+id+",#main_counties_"+id).removeAttr("disabled");
        }
    }
	
    $(".change_jurisdiction_selection").change(function() {
        change_jurisdiction_selection(this)
    });
	
    if ($(".change_jurisdiction_selection").length > 0) {
        $(".change_jurisdiction_selection").each(function() {
            change_jurisdiction_selection(this);
        });
    }
	
    function update_firm_cost() {
        if ($("#user_form").length > 0) {
            formData = $("#user_form").serializeArray();
            url = "provider/calculate_firm_extra_cost/" + $("#firm_id").val();
        } else {
            formData = $("#firm_form").serializeArray();
            url = "provider/calculate_firm_cost";
        }

        $.ajax({
            url: homeurl  + url,
            data: formData,
            type:"POST",
            dataType:"html",
            beforeSend:function() {
            },
            success:function(e) {
                $(".total_cost").html(e);
            },
            error:function() {
            }
        });
    }
	
    $(".change_firm_config").change(update_firm_cost);
	
    $(".delete_request_file").click(function() {
        id = $(this).attr("id").replace("delete_file_", "");
		
        $.ajax({
            url: homeurl  + "request/delete_file/" + id,
            data: [],
            type:"GET",
            dataType:"text",
            beforeSend:function() {
            },
            success:function(e) {
                alert(e);
                $("#req_file_" + id).remove();
            },
            error:function() {
            }
        });
    });
	
    if ($(".total_cost").length > 0) {
        update_firm_cost();
		
        $("#submit_change_user").click(function(evt) {
            evt.preventDefault();
			
            $.ajax({
                url: homeurl  + "provider/check_removed_jurisdiction/",
                data: $("#user_form").serializeArray(),
                type: "POST",
                dataType: "json",
                beforeSend:function() {
                },
                success:function(e) {
                    if (e.message) {
                        $(".site-container").append(
                            "<div class=\"modal hide\" id=\"changeUserModal\">" + 
                            "<div class=\"modal-header\">" + 
                            "<button type=\"button\" class=\"close\" data-dismiss=\"modal\">×</button>" + 
                            "<h3>Confirmation needed</h3>" + 
                            "</div>" + 
                            "<div class=\"modal-body\">" + 
                            "<p>"+e.message+"</p>" + 
                            "</div>" + 
                            "<div class=\"modal-footer\">" + 
                            "<a href=\"javascript:;\" class=\"btn\" data-dismiss=\"modal\">No</a>" + 
                            "<a href=\"javascript:;\" id=\"change_user_accept\" class=\"btn btn-primary\">Yes</a>" + 
                            "</div>" + 
                            "</div>"
                            );
					
                        $('#changeUserModal').modal('show');
                    } else {
                        $('#user_form').append("<input type='hidden' name='submit_user' value='1' />");
                        $("#user_form").submit();
                    }
                },
                error:function() {
                }
            });
        });
    }
    $("#change_user_accept").live("click", function() {
        $('#user_form').append("<input type='hidden' name='submit_user' value='1' />");
        $("#user_form").submit();
    });
    $('#filterCategory1').on('change', function(e){
        var $this = $(this);
        
        this_val = $this.val()
		
        if ($this.val() !== '') {
            $.ajax({
                url: homeurl  + "provider/filter_main_questionnaire/",
                data: [{
                    name: "main_category", 
                    value: this_val
                }],
                type: "POST",
                dataType: "html",
                beforeSend:function() {
                },
                success:function(e) {
                    $('#filterCategory2').prop('disabled', false)
                    $('#filterCategory2').html(e);
                },
                error:function() {
                }
            });
            
        } else {
            $('#filterCategory2, #filterCategory3').prop('disabled', 'disabled');
        }
    });
    
    $('#filterCategory2').on('change', function(e){
        var $this = $(this);
        
        this_val = $this.val()
		
        if ($this.val() !== '') {
            $.ajax({
                url: homeurl  + "provider/filter_main_questionnaire/",
                data: [{
                    name: "main_category", 
                    value: this_val
                }],
                type: "POST",
                dataType: "html",
                beforeSend:function() {
                },
                success:function(e) {
                    $('#filterCategory3').prop('disabled', false)
                    $('#filterCategory3').html(e);
                },
                error:function() {
                }
            });
        } else {
            $('#filterCategory3').prop('disabled', 'disabled')
        }
    });

	$("#cms_upload_photo").change(function() {
		this.form.submit();
	});
	
	$(".country_dropdown_counties").change(function() {
		val = $(this).val();
		
		$(".county_dropdown_menu").val("");
		
		option_list = "<option value=''>Select county</option>";
		
        $.ajax({
            url: homeurl  + "ajax/lookup_area/"+val,
            data:[],
            type:"GET",
            dataType:"json",
            beforeSend:function() {
            },
            success:function(e) {
                if (e.results) {
					for (county_id in e.results) {
						var county_name = e.results[county_id];
		
						option_list += "<option value='" + county_id + "'>" + county_name + "</option>";
					}
                }

				$(".county_dropdown_menu").html(option_list);
            },
            error:function() {
            }
        });

	});
	
	if ($(".change_jurisdiction_selected").length) {
		function change_jurisdiction_selected(obj) {
			id = $(obj).attr("name").replace("area_", "");

			if ($(obj).is(':checked')) {
				$("#selected_area_"+id).fadeIn();
			} else {
				$("#selected_area_"+id).hide();
			}
		}

		$(".change_jurisdiction_selected").each(function() {
			change_jurisdiction_selected(this)
		});
		
		$(".change_jurisdiction_selected").change(function() {
			change_jurisdiction_selected(this)
		});	
	}
});