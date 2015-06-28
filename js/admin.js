  function getMask()
    {
        var selsearch=$('select[name=selsearch]').val();
        switch(selsearch)
        {
            case 'name':
            $("input[name=searchinput]").attr('value','').unmask()
            break;
            case 'phone':
            $("input[name=searchinput]").attr('value','').mask("+38 (999) 999 99 99");
            break;
            case 'card':
            $("input[name=searchinput]").attr('value','').mask("9999 9999 9999 9999");
            break;
        }
    }
 function getList()
    {
        var pr=$('select[name=sfirms]').val();
        var category=$('select[name=category]').val();
        var product=$('select[name=sgroups]').val();
        var vidproduct=$('select[name=ssubgroups]').val();
        
        $.ajax({
            type: "POST",
			url: "../views/admin/ajax/products/productView.php",
			data: {sFirms:pr,category:category,sGroups:product,sSubgroups:vidproduct},
			success: function(data)
            {
                console.log(data)
                /*$('#fieldProduct fieldset').remove()
                $('#fieldProduct').append(data)*/
                location.href="/admin/product";
            }
		});
    }
   function getSort()
    {
        var pr=$('select[name=sort]').val();
        $.ajax({
            type: "POST",
			url: "../views/admin/ajax/clients/sort.php",
			data: {sort:pr},
			success: function(data)
            {
                $('#clientstable').replaceWith('<table style="width:100%;" id="clientstable">'+data+'</table>');
            }
		});
    }
//------------------------------------------------
// Функция передает параметры скрипту на pfuhepre категорий
//------------------------------------------------
function promtLink(obj, action) {
	
    var link = prompt('Введите url', "http://");

    if (link != null) {

    }

}


//------------------------------------------------
// Функция вставляет псевдотеги
//------------------------------------------------
function replaceSelectedText(obj, action, url)
{
	var obj = document.getElementById(obj);
	var rs, add_count;
	obj.focus();
	if (document.selection) 
	{
		var s = document.selection.createRange(); 
		if (s.text)
		{
			switch (action) {
				case "bold": 
						s.text = "[strong]" + s.text + "[/strong]";
						break;
                                case "cursiv": 
						s.text = "[em]" + s.text + "[/em]";
						break;
					
				case "color": 	
						s.text = "[color]" + s.text + "[/color]";
						break;
				
				case "url": 	
						s.text = "[url=" + url + "]" + s.text + "[/url]";
						break;
			}
			
			return true;
		}
	}
	else
		if (typeof(obj.selectionStart)=="number")
 		{
			if (obj.selectionStart != obj.selectionEnd)
			{
				var start = obj.selectionStart;
				var end = obj.selectionEnd;
				
				switch (action) {
					case "bold": 
							rs = "[strong]" + obj.value.substr(start, end - start) + "[/strong]";
							add_count = 17; 
							break;
                                                        
					case "cursiv": 
							rs = "[em]" + obj.value.substr(start, end - start) + "[/em]";
							add_count = 9; 
							break;
						
					case "color": 	
							rs = "[color]" + obj.value.substr(start, end - start) + "[/color]";
							add_count = 15; 
							break;

					case "url": 	
							rs = "[url=" + url + "]" + obj.value.substr(start, end - start) + "[/url]";
							add_count = 12 + url.length; 
							break;
				}
				obj.value = obj.value.substr(0, start) + rs + obj.value.substr(end);
				obj.setSelectionRange(end + add_count, end + add_count);
			}
			return true;
		}

	return false;
}


$(function(){
    var hash = (location.hash) ? location.hash.split('#')[1] : null;
	var funct =
	{
		clickEvent:function(selector,event)
		{
            $(selector).live('click',event)
		},
        
        triggerClick:function(id)
		{
            $(id).trigger('click');
		},
        
        checkTextIn:function(arrayElements, chekForNull)
		{
            var error = 0;
            for (i = 0; i <= arrayElements.length; i++)
            {
                $(arrayElements[i]).is(function(){
                    if (!$(arrayElements[i]).val().replace(/ /g,''))
                    {
                        $(arrayElements[i]).addClass('br');
                        error++;
                    }
                    else 
                    {
                        $(arrayElements[i]).removeClass('br');
                    }
                    
                    if (chekForNull)
                    if ($(arrayElements[i]).val().replace(/ /g,'') == 0)
                    {
                        $(arrayElements[i]).addClass('br');
                        error++;
                    }
                    else 
                    {
                        $(arrayElements[i]).removeClass('br');
                    }
                })
            }
            return parseInt(error);
		},
        /* _parametr - в формате {first_parametr: 'example',sec_parametr:'example'}
        *  _datatype - xml, json, script, or html
        *  _event - в формате function(принимающая переменая) {//события}
        */
        ajax:function(_url,_type,_datatype,_parametr,_event)
        {
            $.ajax({
			url: _url,
			type: _type,
			dataType: _datatype,
			data: _parametr,
			success: _event 
		});
        },
        sinval:function(funct,time)
        {
            setInterval(funct,time)
        }
        ,
        calendar:function(elementByName)
        {
            $('input[name="'+elementByName+'"]').datepicker();
        }
	}
    check()
	//functions
    function check()
    {
        if ($('#whatPage').val() == 'login') return false;
        else
        {
            funct.ajax('/views/admin/ajax/order/check.php','post','',{action:'check'},function(result){
                if (result)
                {
                    beep();
                    orderflash();
                    if ($('#orderPage').val())
                    {
                        funct.ajax('/views/admin/ajax/order/orders.php','post','',{action:'update'},function(result){
                            $('#fieldOrder').html(result)
                            //console.log(result)
                        })   
                    }
                }
            })
        }
    }
    
    //мигание кнопки
    function orderflash() 
    {
        //$('body').animate({backgroundColor: "#F8A2A5" }, 200);
        $('#orderflash').animate({backgroundColor: "#F8A2A5" }, 200,function(){
            //$('body').animate({backgroundColor: "#374353" }, 200);
            $('#orderflash').animate({backgroundColor: "#EEEEEE" }, 200, function(){
                orderflash() ;
            });
        });
        //setTimeout(function(){$('#orderflash').css('background-color','#F8A2A5');},1000)
    }
    //функция воспроизводит звуковой сигнал
    function beep()
    {
        $('.beep').remove();
        $('body').prepend("<embed class='beep' src='/mp3/beep.mp3' hidden=true autostart=true loop=false>");
    }
    
     
    //******************************** Группы и подгруппы
    //показ скрытие влежений
    funct.clickEvent('.showing',function(){$(this).next('div').toggleClass('hidden')})
    
    //показ добавленной или редактированой группы, подгруппы
    if (hash) 
    {
        ids = hash.split('-');
        funct.triggerClick('#s'+ids[0])
        if (ids.length == 2) funct.triggerClick('#g'+ids[1])
    }
    
    //******************************** проверка на заполнение поля
    funct.clickEvent('input[type="submit"]',function(){
        var and = '';
        var result = '';
        var page = $('input[name="whatPage"]').val();
        var pageEditor = $('input[name="whatPageEditor"]').val();
        switch (page)
        {
            //страница отзывов
            case 'review' : 
                if (pageEditor == 'reviewEditor')
                {
                    result = funct.checkTextIn(['input[name="name"]','#text']);
                    if ($('.onlyRemoveSelectedProduct').size() != 1) and += "\nОбязательно должен быть 1 товар, к которому относиться отзыв!";
                }
            break;
            
            //страница акций
            case 'event' : 
                result = funct.checkTextIn(['input[name="name"]','#text','#dateBegin', '#dateOver']);
                if ($('.baner').size() <= 0) and += "\nОбязательно должен быть банер!";
            break;
            
            //страница инструкции
            case 'instruction' : 
                result = funct.checkTextIn(['input[name="name"]']);
                if (($('.pdfLink').size() <= 0) && !$('#pdf').val()) and += "\nОбязательно должен быть файл PDF!";
                if ($('#idsProducts').val().replace(/~/g,'').length < 1) and += "\nОбязательно должен быть хотя бы 1 товар, к которому относиться инструкция!";
            break;
            
            //страница производители
            case 'firm' : 
                result = funct.checkTextIn(['input[name="name"]']);
            break;
             
            //страница Разделы, группы, подгруппы 
            case 'section' : 
                result = funct.checkTextIn(['input[name="name"]']);
            break;
            
            //страница Разделы галереи
            case 'groupgalery' : 
                result = funct.checkTextIn(['input[name="name"]']);
            break;
            
            //страница Галерея
            case 'galery' : 
                result += funct.checkTextIn(['#author']);
                result += funct.checkTextIn(['#sGroupsGalery'],true);
                if ($('.photoG').size() <= 0) and += "\nОбязательно должно быть фото!";
                if ($('#idsProducts').val().replace(/~/g,'').length < 1) and += "\nОбязательно должен быть хотя бы 1 товар!";
            break;
            
            //страница ТОВАРЫ 
            case 'product' : 
                if (pageEditor == 'productEditor')
                {
                    if ($('#SHorderDay').attr('checked') == 'checked') result += funct.checkTextIn(['input[name="orderDay"]'],true);
                    result += funct.checkTextIn(['input[name="nameSearch"]','input[name="name"]','input[name="price"]','textarea[name="description"]']);
                    if (($('#loaded_image_preview div').size() <= 0) || !($('input[name="ava"]:checked').val())) and += "\nОбязательно должен быть хотя бы 1 отмеченое фото!";
                    if ($('.colors').size() <= 0) and += "\nОбязательно должен быть хотя бы 1 цвет со всеми заполнеными полями!";
                    else
                    if ($('.colors').size() == 1) 
                    {
                        if (parseInt(funct.checkTextIn(['input.colorName','input.colorArticle'])) > 0)
                        and += "\nОбязательно должен быть хотя бы 1 цвет со всеми заполнеными полями!";
                    }
                    result += funct.checkTextIn(['#sFirms','#sSections','#sGroups','#sSubgroups','input[name="price"]'],true)
                }
            break;
        }
        result = parseInt(result);
        if (result > 0 || and)
        {
            var message = '';
            if (result)  message = 'Красным выделены обязательные поля для заполнения!';
            alert(message+and);
            return false
        }
    })
    //******************************** Отзывы
    function hideSearcBar()
    {   
        var p = $('input[name="whatPage"]').val();
        if (p == 'review')
        if ($('.onlyRemoveSelectedProduct').size() == 1) 
            $('#searchBar, #searchResult').addClass('hidden');
        else 
            $('#searchBar, #searchResult').removeClass('hidden');
    }
    hideSearcBar()
    funct.clickEvent(document,hideSearcBar);
    
    //******************************** Товыры
    

    
    //быстрое редактирование
    $('.price, .presence, .show').live('change',function(){
        if ($('input[name="whatPage"]').val() == 'product')
        {
            
            var block = $(this).parents('table');
            
            var action = 'edit';
            var id = block.attr('id').replace(/[^\d.]/g, "");
            var price = block.find('.price').val();
            var presence = block.find('.presence').val();
            var show = (block.find('.show').attr('checked') == 'checked') ? 1 : 0;
            var quickChangeDays = $('input[name="quickChangeDays"]').val();
            
             
            if (funct.checkTextIn(['#price'+id],true)) 
            {
                alert('Поле цена не может быть 0 или пустым\nОсталось прежнее значенеие');
                //$('#price'+id).val(1);
                //return false;
            }
            else
            {
                $('#substrate').css('display','block');
                funct.ajax('/views/admin/ajax/products/productQuickSave.php','post','',
                {
                    action:action,
                    id:id,
                    price:price,
                    presence:presence,
                    show:show,
                    quickChangeDays:quickChangeDays
                },function(result){
                    //console.log(result)
                    //return false;
                    result = result.replace(/\n/g, "")
                    if (result) 
                    {
                        var data = result.split('~~~');
                        $('#uah'+id).html(data[0])
                        $('#euro'+id).html(data[1])
                        $('#updateBlock'+id).html(data[2])
                    }
                    $('#substrate').css('display','none');
                })
            }
        }
    })
    
    /*//список групп
    funct.clickEvent('select[name="sSections"]',function(){
        var page = $('#pageEditor').val();
        $('#sGoups').attr('disabled','disabled');
        $('select[name="sGroups"]').trigger('click');
        var idSection = $(this).val();//console.log(idGroup)
        if (idSection != 0)
        funct.ajax('/views/admin/ajax/products/getSelectGroup.php','post','',{idSection:idSection,page:page},function(result){
            if (result) $('#forSelectGroup').html(result);
        })
    })
    //список подгрупп
    funct.clickEvent('select[name="sGroups"]',function(){
        $('#sSubgroups').attr('disabled','disabled');
        var idGroup = $(this).val();//console.log(idGroup)
        if (idGroup != 0)
        funct.ajax('/views/admin/ajax/products/getSelectSubgroup.php','post','',{idGroup:idGroup},function(result){
            if (result) $('#forSelectSubgroup').html(result);
        })
    })*/
   
    //смена настроек для ajaxForm
    $('#load_image').live('click',function ()
    {
        var photoLoader = 
        {
            url:   'views/admin/ajax/products/photoLoader.php',
            beforeSubmit: function(jqForm) 
            {
                $('#load_image,input[type=submit]').attr('disabled','disabled')
            	$('#img_loader_photo_preview').replaceWith("<span id='img_loader_photo_preview'><img style='margin: 0px 8px 5px;' src='/img/admin/l.gif'/> Подождите идет загрузка фото</div>");
            },
            success: function(responseText) 
            { 
                if (responseText.indexOf('error') == -1) 
                {
                    
                    $('#loaded_image_preview').replaceWith('<span id="loaded_image_preview">'+responseText+'</span>'); 
                }
                else
                {
                    	alert(responseText.replace(/<.*?>/g, ''));
                }
                $('#img_loader_photo_preview').replaceWith("<span id='img_loader_photo_preview'></span>");
                $('#load_image,input[type=submit]').removeAttr('disabled')
                
            }
        };
        $('#form').ajaxSubmit(photoLoader); 
        return false;   
    })
    $('#load_image_dial').live('click',function ()
    {
        var photoLoader = 
        {
            url:   'views/admin/ajax/dial/photoLoader.php',
            beforeSubmit: function(jqForm) 
            {
                $('#load_image_dial,input[type=submit]').attr('disabled','disabled')
            	$('#img_loader_photo_preview').replaceWith("<span id='img_loader_photo_preview'><img style='margin: 0px 8px 5px;' src='/img/admin/l.gif'/> Подождите идет загрузка фото</div>");
                $('input[name=goSearch],input[name=load_image_dial],input[name=edit]').attr('disabled',true)
            },
            success: function(responseText) 
            { 
                 $('#load_image_dial,input[type=submit]').removeAttr('disabled')
                $('#img_loader_photo_preview').replaceWith("<span id='img_loader_photo_preview'></span>");
                $('#loaded_image_preview').replaceWith('<span id="loaded_image_preview">'+responseText+'</span>'); 
                $('input[name=goSearch],input[name=load_image_dial],input[name=edit]').attr('disabled',false)
            }
        };
        $('#form').ajaxSubmit(photoLoader); 
        return false;   
    })
    //удаление фото
    funct.clickEvent('.photodel',function(){
        if (!confirm('Удалить?')) {return false;}
        else
        {           
            var id = $(this).attr('id').split('~')[1]; //console.log(id);
            $('#img_loader_photo_preview').replaceWith("<span id='img_loader_photo_preview'><img style='margin: 0px 8px 5px;' src='/img/admin/l.gif'/> Подождите идет удаление фото</div>");
            funct.ajax('views/admin/ajax/products/photoLoader.php', 'post', '', {action:'photodel',id:id}, function(result){
                $('#img_loader_photo_preview').replaceWith("<span id='img_loader_photo_preview'></span>");
                $('#loaded_image_preview').replaceWith('<span id="loaded_image_preview">'+result+'</span>'); 
            })
        }
    })
        //удаление фото
    funct.clickEvent('.photodeldial',function(){
        if (!confirm('Удалить?')) {return false;}
        else
        {           
            var id = $(this).data('del');
            console.log(id)
            $('#img_loader_photo_preview').replaceWith("<span id='img_loader_photo_preview'><img style='margin: 0px 8px 5px;' src='/img/admin/l.gif'/> Подождите идет удаление фото</div>");
            funct.ajax('views/admin/ajax/dial/photoLoader.php', 'post', '', {action:'photodel',id:id}, function(result){
                console.log(result)
                $('#img_loader_photo_preview').replaceWith("<span id='img_loader_photo_preview'></span>");
                $('#loaded_image_preview').replaceWith('<span id="loaded_image_preview">'+result+'</span>'); 
            })
        }
    })
    //добавление строки для цвета
    funct.clickEvent('#addColor',function(){
        //$('.rowColor').css('display','table-row');
        var idC = (parseInt($('#numberColors').val())); 
        idC = (idC) ? idC : 0;
        var row = '<tr class="rowColor"><td align="center"><input type="text" name="colorName['+idC+']" style="width:400px;"></td><td align="center"><input type="text" name="colorArticle['+idC+']" style="width:400px;"></td><td align="center"><input type="hidden" class="colors" name="rgb['+idC+']" value="000000"></td></tr>'; 
        
        //console.log(row);
        $('.rowColor :last').after(row);
        $('#numberColors').val(idC+1);
        $('.jPicker').remove();
    })
    //if ($('.rowShortDescription').size() == 1) $('.rowShortDescription').css('display','none')
    
    //добавление строки для короткого описания(характеристик)
    funct.clickEvent('#addRow',function(){
        $('.rowShortDescription').css('display','table-row');
        var idSR = (parseInt($('#idShortRow').val()));
        idSR = (idSR) ? idSR : 0;
        var row = '<tr class="rowShortDescription"><td align="center"><input type="text" name="nameShort['+idSR+']" style="width:400px;"></td><td align="center"><input type="text" name="descriptionShort['+idSR+']" style="width:400px;"></td><td align="center"><input type="checkbox" name="showInShortDescription['+idSR+']"></td></tr>'; 
        $('.rowShortDescription :last').after(row);
        $('#idShortRow').val(idSR+1)
    })
    if ($('.rowShortDescription').size() == 1) $('.rowShortDescription').css('display','none')
    
    //******************************** акции
    //смена настроек для ajaxForm
    $('#load_baner').live('click',function ()
    {
        if (!$('#image').val())
        {
            alert('Выберите банер!');
            return false;
        }
        else
        {
        var photoLoader = 
        {
            url:   'views/admin/ajax/event/imageLoader.php',
            beforeSubmit: function(jqForm) 
            {
            	$('#img_loader_photo_preview').replaceWith("<span id='img_loader_photo_preview'><img style='margin: 0px 8px 5px;' src='/img/admin/l.gif'/> Подождите идет загрузка фото</div>");
            },
            success: function(responseText) 
            { 
                $('#img_loader_photo_preview').replaceWith("<span id='img_loader_photo_preview'></span>");
                $('#loaded_image_preview').replaceWith('<span id="loaded_image_preview">'+responseText+'</span>'); 
            }
        };
        $('#form').ajaxSubmit(photoLoader); 
        return false;   
        }
    })
    
    //******************************** заказы
    //проверка на наличие новых товаров
    funct.sinval(check,(1000 * 60 * 5))//каждые 5 мин.
    
    //прикрепление календарей
    funct.calendar('dateTo');
    funct.calendar('dateFrom');
    
    funct.calendar('dateBegin');
    funct.calendar('dateOver');
    
    funct.clickEvent('input[name="resetBtn"]',function(){
        var date = new Date();
        var month;
        if(date.getMonth()<9)
        {
            month=date.getMonth()+""+1;
        }
        else
        {
            month=date.getMonth();
        }
        if($('input[name=reset]').val()==1)
        {
            getList();
        }
        $('#dateFrom').val(date.getDate()+"."+month+"."+date.getFullYear())
        $('#dateTo').val(date.getDate()+"."+month+"."+date.getFullYear())
        /*$("#category").val($("#category option:first").val());
        $("#sfirms").val($("#sfirms option:first").val());
        $("#sgroups").val($("#sgroups option:first").val());
        $("#ssubgroups").val($("#ssubgroups option:first").val());*/
    })
   
    
    //******************************** Галирея
    //смена настроек для ajaxForm
    $('#load_photoG').live('click',function ()
    {
        var photoLoader = 
        {
            url:'views/admin/ajax/galery/imageLoader.php',
            beforeSubmit: function(jqForm) 
            {
            	$('#img_loader_photo_preview').replaceWith("<span id='img_loader_photo_preview'><img style='margin: 0px 8px 5px;' src='/img/admin/l.gif'/> Подождите идет загрузка фото</div>");
            },
            success: function(responseText) 
            { 
                $('#img_loader_photo_preview').replaceWith("<span id='img_loader_photo_preview'></span>");
                $('#loaded_image_preview').replaceWith('<span id="loaded_image_preview">'+responseText+'</span>'); 
                $('.photoG').exifLoad();
            }
        };
        $('#form').ajaxSubmit(photoLoader); 
        return false;   
    })
 
    funct.clickEvent('#readEXIF',function(){
        //console.log(($('.photoG').exifAll()))
         $('.photoG').exifLoad(function(){
            
            
         
        var img = $('.photoG');
        var author = img.exif("Artist");
        var fileSize = $('#fileSizeHidden').val();
        var pixelSize = img.exif("PixelXDimension")+' x '+img.exif("PixelYDimension")+' px';
        var firm = img.exif("Make");
        var model = img.exif("Model");
        var timeTaken = img.exif("DateTimeOriginal");
        var numberDiaphragm = img.exif("FNumber");
        
        var focalLens = img.exif("FocalLength");
        
        var flash = img.exif("Flash");
        
        
        
        if (author) $('#author').val(author)
        if (fileSize) $('#fileSize').val(fileSize)
        if (pixelSize) $('#pixelSize').val(pixelSize)
        if (firm) $('#firm').val(firm)
        if (model) $('#model').val(model)
        if (timeTaken) $('#timeTaken').val(timeTaken) 
        if (numberDiaphragm) $('#numberDiaphragm').val(numberDiaphragm) 
        
        if (focalLens) $('#focalLens').val(focalLens) 
        
        if (flash) $('#flash').val(flash) 
        })
        return false;
    })
    
    
    //*************************************** поиск товаров
    funct.clickEvent('#goSearch',function(){
        var idsProducts = $('#idsProducts').val();
        var query = $('#search').val();
        funct.ajax('views/admin/ajax/products/search.php', 'post', '', {idsProducts:idsProducts,query:query}, function(result){
            $('#searchResult').html(result);
            //console.log(result)
        })
    })
    //запись товаров как аксессуар
    funct.clickEvent('.selectProduct',function(){
        $(this).removeClass('selectProduct').addClass('onlyRemoveSelectedProduct');
        var id = $(this).val();
        var idsProducts = $('#idsProducts').val();
        var tr = $(this).parent().parent();
        tr.find('.number').text('');
        $('#selectedProductsAfterHeadRow').after(tr);
        $('#idsProducts').val(idsProducts+id+'~');
    })
    funct.clickEvent('.onlyRemoveSelectedProduct',function(){
        var id = $(this).val();
        var idsProducts = $('#idsProducts').val();
        var tr = $(this).parent().parent();
        tr.css('display','none').html('');
        $('#idsProducts').val(idsProducts.replace(id,'').replace(/~~/g,'~'))
    })
    
    $('input[name=nameart]').click(function()
    {
        if($(this).attr('checked')=="checked")
        {
            $('input[name=searchart]').val('Поиск по названию')
        }
        else
        {
            $('input[name=searchart]').val('Поиск по артиклу')
        }
    })
    $('input[name=kkz]').click(function()
    {
        if($(this).attr('checked')=="checked")
        {
            $('.kkz').show()
            
        }
        else
        {
            $('.kkz').hide()
        }
    })
    $('input[name=searchart]').click(function()
    {
        var searchart = 
        {
            url:'views/admin/ajax/dial/search.php',
            beforeSubmit: function(jqForm) 
            {
            	$('#searchResult').replaceWith("<div id='searchResult'><img style='margin: 0px 8px 5px;' src='/img/admin/l.gif'/>Подождите осуществлется поиск</div>");
            },
            success: function(responseText) 
            { 
                $('#searchResult').replaceWith('<div id="searchResult">'+responseText+'</div>');
            }
        };
        $('#form').ajaxSubmit(searchart); 
        return false; 
    })
    $('.addtosostav').live('click',function()
    {
        
        var dataid=$(this).data('id');
        var html=$(this).parents('.searchtable').html();
        html=html.replace(/addtosostav/g,"removetosostav");
        html=html.replace(/Добавить в состав/g,"убрать из состава");
        html=html.replace(/<tr class="vidvis"><\/tr>/g,'<tr class="vidvis"><td colspan="3"><table><tr><td style="padding:6px 0 0 4px;width:25px;">Вид</td><td><select name="narezannot" style="margin-left:10px;width:150px;"><option value="0">Не нарезан</option><option value="1">Нарезан</option></select></td><td style="width:25px;padding:6px 0 0 4px;">Вес</td><td><input name="vesnarez" style="margin-top: 4px;margin-left:10px;width:150px;"></td><td><select name="narezed" style="width:50px;"><option value="1">кг.</option><option value="2">гр.</option><option value="3">л.</option><option value="4">шт.</option><option value="5">мл.</option></select></td></tr></table></td></tr>');
        html=html.replace(/narezannot/g,'narezannot'+dataid);
        html=html.replace(/vesnarez/g,'vesnarez'+dataid);
        html=html.replace(/narezed/g,'narezed'+dataid);
        html=html.replace(/idname/g,"sostav"+dataid)
        $('#sostav').append("<table class='searchtable'>"+html+"</table>")
        $(this).parents('.searchtable').remove();
        
    })
    $('.removetosostav').live('click',function()
    {
        var dataid=$(this).data('id');
        var delid=$('input[type=hidden]',$(this).parents('.searchtable')).val()
        $('.alreadyin',$('.searchtable input[value='+delid+']').parents('.searchtable')).replaceWith("<input type='button' class='addtosostav' value='Добавить в состав' data-id='"+dataid+"'>")
        $(this).parents('.searchtable').remove();
    })
    //*************************************** оставить только цифры
    $(document).bind('keyup keydown keypress',function(e)
    {
        onlynumbers(e)
    });
    function onlynumbers(ev)
    {
        if(ev.keyCode!=37 && ev.keyCode!=38 && ev.keyCode!=40 && ev.keyCode!=39)
        {
        $('.onlynumbers').map(function(){
            var numbers = $(this).val().replace(/[^\d.]/g, "");
            $(this).val(numbers);
        })
        }
    }
    
     //Запрос на удаление набора
    funct.clickEvent('.deldial',function(){if (!confirm('Удалить?')) {return false;}})
    //Запрос на удаление товара
    funct.clickEvent('.delp',function(){if (!confirm('Удалить? Удаление данного товар приведет к удалению данного товара в наборах')) {return false;}})
    //Запрос на удаление вида продукции
    funct.clickEvent('.delsubgroup',function(){if (!confirm('Удалить? Удаление данного вида продукции приведет к удалению товаров, состава в наборах этого вида продукции')) {return false;}})
    //Запрос на удаление продукции
    funct.clickEvent('.del',function(){if (!confirm('Удалить? Удаление данной продукции приведет к удалению товаров, состава в наборах этой продукции')) {return false;}})
    //удаление произодителя
    funct.clickEvent('.delfirm',function(){if (!confirm('Удалить? Удаление данного произодителя приведет к удалению товаров, состава в наборах этого производителя')) {return false;}})
    //удаление из каталога продукции
    funct.clickEvent('.delcat',function(){if (!confirm('Удалить? Удаление данной категории приведет к удалению товаров, состава в наборах этой категории')) {return false;}})
    //Запрос на удаление клиента
    funct.clickEvent('.deleteclient',function(){if (!confirm('Удалить?')) {return false;}})
    //
    funct.clickEvent('input[name=clientsearch]',function(){
        var searchinput=$('input[name=searchinput]').val()
        var selsearch=$('select[name=selsearch]').val()
        var sort=$('select[name=sort]').val()
        if(searchinput!='')
        {
            funct.ajax('/views/admin/ajax/clients/sort.php','post','',{action:'search',input:searchinput,sel:selsearch,s:sort},function(result){
                if (result)
                {
                    $('#clientstable').replaceWith('<table style="width:100%;" id="clientstable">'+result+'</table>');
                }
            })
        }
    })
    $('input[name="nametosearch"]').on('keydown',function(e)
    {
        if(e.keyCode==13) { $('input[name="searchart"]').trigger('click');return false;}
    })
    
})