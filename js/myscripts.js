//------------------------------------------------
// Функция передает параметры скрипту и загружает указанный блок
//------------------------------------------------
function loadBlock(url, action, id, a, type, ids) 
{
    $("#actionContent").html('Загрузка...');

    $("#content .tut").removeClass();
    
     var params = "";
     
    if (typeof ids != 'undefined' && ids != null)
       params = 'id=' + id + "&action=" + action + "&type=" + type + "&ids=" + ids;
    else
       params = 'id=' + id + "&action=" + action + "&type=" + type;

    //Запрос к странице редактирования
    $.ajax({
            url:	url,
            type:	'POST',
            contentType: 'application/x-www-form-urlencoded', //Тип передаваемых данных
            data:	params,
                    //а это, собственно, данные (произвольные)
            success: function(data){
                $("#actionContent").html(data);
            }
    });	

    if (typeof a != 'undefined' && a != null)
        a.className = "tut";

    return false;
}

//------------------------------------------------
// Функция передает параметры скрипту и загружает указанный блок
// Перед удаление спрашивает подтверждение
//------------------------------------------------
function deleteItem(url, action, id) 
{
    if (!confirm('Действительно удалить?')) {
        return false;
    }
    else {
        $("#actionСontent").html('Загрузка...');

        //Запрос к странице редактирования
        $.ajax({
            url:	url,
            type:	'POST',
            contentType: 'application/x-www-form-urlencoded', //Тип передаваемых данных
            data:	'id=' + id + "&action=" + action,
                    //а это, собственно, данные (произвольные)
            success: function(data){
                $("#actionContent").html(data);
            }
        });
        return false;
    }
}
//------------------------------------------------
// Функция удаляет загруженный файл
//------------------------------------------------
function deleteUploadedFile(name, dir) 
{
    var nameFile = name.replace(".png", "").replace(/[\r\n]/g, "");
    
    //Запрос к странице редактирования
    $.ajax({
            url:	 'ajax/delete_uploaded.php',
            type:	 'POST',
            contentType: 'application/x-www-form-urlencoded', //Тип передаваемых данных
            data:	 'name=' + name + '&dir=' + dir,
                    //а это, собственно, данные (произвольные)
            success: function(data){
                $("#uploaded_" + nameFile).delay(100).fadeOut(100, function() { $(this).remove() });
            }
    });		

}
//------------------------------------------------
// Функция передает параметры скрипту на pfuhepre категорий
//------------------------------------------------
function promtLink(obj, action) {
	
    var link = prompt('Введите url', "http://");

    if (link != null) {
        replaceSelectedText(obj, action, link);
    }

}

//------------------------------------------------
// Функция передает параметры скрипту на pfuhepre категорий
//------------------------------------------------
function sendCat(select, url, action) {
	
    var portfolio_cat = select.options[select.selectedIndex].value;

    $("#actionContent").html('Загрузка...');

    //Запрос к странице
    $.ajax({
        url:	url,
        type:	'POST',
        contentType: 'application/x-www-form-urlencoded', //Тип передаваемых данных
        data:	'portfolio_cat=' + portfolio_cat + "&action=" + action,
                //а это, собственно, данные (произвольные)
        success: function(data){
                $("#actionContent").html(data);
        }
    });	
	
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

//------------------------------------------------
// Функция отмечает или снимает отметку со всех checkbox
//------------------------------------------------
function checkAll(check)
{
    // Получаем checkbox
    var tbody = document.getElementById('images');
    var content = tbody.getElementsByTagName('input');

    // Проходим по элементам формы и обрабатываем checkbox
    for (var i=0; i < content.length; i++) {
        if (content[i].type == 'checkbox') {
                content[i].checked = check;
        }
    }
}

//------------------------------------------------
// Функция отмечает или снимает отметку со всех checkbox
//------------------------------------------------
function deleteChecked(url, action, id, type)
{
    // Получаем checkbox
    var tbody = document.getElementById('images');
    var content = tbody.getElementsByTagName('input');
    var ids = "";
    
    // Проходим по элементам формы и обрабатываем checkbox
    for (var i=0; i < content.length; i++) {
        if (content[i].type == 'checkbox' && content[i].checked == true) {
                
            ids += content[i].value + ";";
        }
    }
    if (ids != "")
        loadBlock(url, action, id, null, type, ids);
    else
        return false;
}