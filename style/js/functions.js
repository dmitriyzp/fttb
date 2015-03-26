var typeWork;
$(document).ready(function(jQuery){
//Отключаем родительские ссылки в выпадающих пунктах
   $("li:has(ul.dropdown)").addClass("dropdownLi")
   $("li.dropdownLi > a").click(function(eventObject){return false})
$(function() {
	$("a").bind('click',function() {
		var _this = $(this);		
// Раскрываем текущую ссылку		
_this.toggleClass('selected', 5);
_this.next().toggleClass('dropdown', 500);
// Проходим по другим ссылкам и выключаем активное состояние
   $("a").not(_this).each(function() {
      $(this).next().addClass('dropdown', 500);
	  $(this).removeClass('selected', 5);
});
});
});
    $(function(){
        var pWidth = $(document).width();
        $('.divCenter').css({'margin-left': (pWidth-$('.divCenter').width())/2});
    })
});
function getPermissionFields(formname){
        $.ajax({
            type: 'get',
            url: window.location.origin+'/main/getFormFieldPermissionAjax',
            dataType: 'json',
            data: 'formname='+formname,
            success: function(data){
                        for(var i=0; i<data.length; i++){
                            if($('#'+data[i]['fieldname']+'').val() == undefined){
                                var quantif = ".";
                            }else{
                                var quantif = "#";                                                 
                            }
                            if($(quantif+data[i]['fieldname']+'').val() != undefined){
                                  switch($(quantif+data[i]['fieldname']+'').getType()){
                                    case "text":
                                        $(quantif+data[i]['fieldname']+'').attr('readonly','readonly');break;
                                    case "submit": 
                                        $(quantif+data[i]['fieldname']+'').hide();break;
                                    case "a":
                                        $(quantif+data[i]['fieldname']+'').attr('hidden','true');break;
                                    default:
                                        $(quantif+data[i]['fieldname']+'').attr('disabled','disabled');break;
                                  }                              
                            }

                            
                        }
                    }
        })        
    

}

$.fn.getType = function(){ return this[0].tagName == "INPUT" ? this[0].type.toLowerCase() : this[0].tagName.toLowerCase(); }

function DropDown(el) {
				this.dd = el;
				this.initEvents();
			}
			DropDown.prototype = {
				initEvents : function() {
					var obj = this;

					obj.dd.on('click', function(event){
						$(this).toggleClass('active');
						event.stopPropagation();
					});	
				}
			}

			$(function() {

				var dd = new DropDown( $('#dd') );

				$(document).click(function() {
					// all dropdowns
					$('.wrapper-dropdown-5').removeClass('active');
				});

			});
