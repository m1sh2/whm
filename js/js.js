window.count = 2;
window.str = '';

function e(id){
	return document.getElementById(id);
}
function NewEl(num,hide){
	var t = self.pageYOffset||(document.documentElement && document.documentElement.scrollTop)||(document.body && document.body.scrollTop);
	var ta = t+10+'px';
	if(num==''||typeof(num)=='undefined'){
		num = Math.round(Math.random()*1000);
	}
	jQuery('#page').append('<div class="messwindowin" id="messwindowin'+num+'" style="top:'+ta+';display:'+(num=='hide'||hide=='hide'?'none':'block')+';"><span class="close" style="position:absolute;right:5px;top:5px;z-index:10;" onclick="Remove(e(\'messwindowin'+num+'\'))"><span class="icon3 icon-close"></span></span><div class="messwindowin_2" id="messwindowin_2'+num+'"></div></div>');
	return num;
}
function Remove(id){
	if(id){
		id.parentNode.removeChild(id);//alert(id.id.search('messwindow'));
		// if(id.id.search('messwindowin')==0){
			// ind = 'messwindowin'+id.id.replaceAll('messwindowin','');
			// e(ind).parentNode.removeChild(e(ind));
		// }
	}
}
function QTip(id,act){alert(1);
	var nspan = document.createElement('SPAN');
	nspan.className = 'qtip';
	nspan.innerHTML = jQuery(this).attr('title');
}
function Page(h,block,num){
	switch(block){
		case'0':{
			n = NewEl(num,'');
			Load(e('messwindowin_2'+n),'type='+h+'&num='+n);
			if(h=='add&act=finance'){
				var t = setTimeout(function(){jQuery('.addfinanceone .opname').autocomplete({source: autocomplite,minLength:2});},1000);
			}
			break;
		}
		case'a':{
			n = NewEl(num,'hide');
			Load('a','type='+h+'&num='+n);
			break;
		}
		case'h':{
			n = NewEl(num,'hide');
			Load(e('messwindowin_2'+n),'type='+h+'&num='+n);
			break;
		}
		case'f':{
			var m = num.split('|');
			var n = m[1];
			var b = '<span class="btn f-l m-2 febtn'+n+' active" style="padding:5px 15px 5px 20px;" onclick="if(jQuery(\'.ewin'+n+'\').is(\':visible\')){jQuery(\'.ewin\').hide();jQuery(\'#top .files .btn\').removeClass(\'active\');jQuery(\'body\').removeClass(\'bodyeditor\');}else{jQuery(\'#top .files .btn\').removeClass(\'active\');jQuery(this).addClass(\'active\');jQuery(\'.ewin\').hide();jQuery(\'.ewin'+n+'\').show();jQuery(\'body\').addClass(\'bodyeditor\');}">';
			b += '<span class="iconlblue icon-transferthick-e-w" style="position:absolute;left:3px;top:4px;"></span>';
			b += '<span class="close" style="top: 0px;right: 0px;" onclick="var t = setTimeout(function(){jQuery(\'body\').removeClass(\'bodyeditor\');},500);jQuery(\'.febtn'+n+',.ewin'+n+'\').remove();"><span class="icon3 icon-close" title="Close"></span></span>';
			b += '<span class="ftitle">'+m[0]+'</span>';
			b += '</span>';
			jQuery('#top .files .btn').removeClass('active');
			jQuery('#page #top .files').append(b);
			
			jQuery('body .ewin').hide();
			var d = document.createElement("DIV");
			var d2 = document.createElement("DIV");
			jQuery(d2).addClass('ewinin').css({'width':window.innerWidth-40+'px','height':window.innerHeight-jQuery('#top').height()-60+'px'});
			d2.id = 'ewinin'+n;
			jQuery(d).append(d2).addClass('ewin ewin'+n).css({'top':jQuery('#top').height()+'px','width':window.innerWidth+'px','height':window.innerHeight-jQuery('#top').height()+'px'});
			jQuery('body').addClass('bodyeditor').append(d);
			
			Load(e('ewinin'+n),'type='+h);
		}
		default:{
			// data = h.split('&');
			// window.location.hash = data[0];
			// alert(block);
			jQuery(block).show();
			Load(block,'type='+h);
			break;
		}
	}
}
function Form(f,url,num){
	var value = '';
	for(i=0;i<f.length;i++){
		if(f[i].type!='submit'&&f[i].type!='button'){
			if(f[i].value==''){
				alert('Заполните, пожалуйста, все поля правильно!');
				return false
			}
			else{
				// alert(f[i].name);
				value += '&'+f[i].name+'='+f[i].value;
			}
		}
	}
	Remove(e('messwindowin'+num));
	Load(e('content'),'type=add&act=client&act2=add'+value);
}
function setCookie(name, value, expires, path, domain, secure){
      document.cookie = name + "=" + escape(value) +
        ((expires) ? "; expires=" + expires : "") +
        ((path) ? "; path=" + path : "") +
        ((domain) ? "; domain=" + domain : "") +
        ((secure) ? "; secure" : "");
}
function getCookie(name){
	var cookie = " " + document.cookie;
	var search = " " + name + "=";
	var setStr = null;
	var offset = 0;
	var end = 0;
	if (cookie.length > 0) {
		offset = cookie.indexOf(search);
		if (offset != -1) {
			offset += search.length;
			end = cookie.indexOf(";", offset)
			if (end == -1) {
				end = cookie.length;
			}
			setStr = unescape(cookie.substring(offset, end));
		}
	}
	return(setStr);
}
function hasClass(ele,cls){
	return ele.className.match(new RegExp('(\\s|^)'+cls+'(\\s|$)'));
}
function addClass(ele,cls){
	if (!this.hasClass(ele,cls)) ele.className += " "+cls;
}
function removeClass(ele,cls){
	if (hasClass(ele,cls))
	{
		var reg = new RegExp('(\\s|^)'+cls+'(\\s|$)');
		ele.className=ele.className.replace(reg,' ');
	}
}
function Open3(id,type){
	
	// alert(ul);
	// var span = id.parentNode.parentNode.childNodes[1];
	var li = id.parentNode.parentNode.parentNode;
	var div = id.parentNode.parentNode;
	var inn = li.lang.split('_');
	var ul = e(type+''+inn[1]);
	if(ul.style.display==''){
		ul.style.display = 'none';
		// removeClass(span,'active');
		removeClass(div,'active');
		return
	}
	ul.style.display = '';
	// addClass(span,'active');
	addClass(div,'active');
	
	Load(ul,'type='+inn[0]+'&idin='+inn[1]);
}
String.prototype.replaceAll = function(search, replace){
	return this.split(search).join(replace);
}
function Load(block,url){
	// var wait = e('waiting');
	// wait.style.display = 'block';
	jQuery(block).html('').addClass('loading');//alert(url);
	// block.innerHTML = '&#9803;';
	var ajaxRequest;
	try{
		ajaxRequest = new XMLHttpRequest();
	}
	catch(e){
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(e){
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				alert("Your browser broke!");
				return false;
			}
		}
	}
	ajaxRequest.open('POST',siteurl+'php/debug.php',true);
	ajaxRequest.setRequestHeader("Content-Charset", "UTF-8");
    ajaxRequest.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	ajaxRequest.setRequestHeader("Content-length",url.length);
	ajaxRequest.setRequestHeader("Connection", "close");//alert(ajaxRequest.readyState);
	ajaxRequest.onreadystatechange = function(){//alert(8);
		if(ajaxRequest.readyState==4){//alert(type+' '+url+' '+tag);
			if(block=='a'){
				alert(ajaxRequest.responseText);
			}
			else{
				console.log(ajaxRequest.responseText);
				block.innerHTML = ajaxRequest.responseText;
				jQuery(block).removeClass('loading');
			}
			// alert(ajaxRequest.responseText);
			// wait.style.display = 'none';
			
		}
	}
	ajaxRequest.send(url);
}
function datepick(){
	jQuery(".datepicker").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
		showOtherMonths: true,
		selectOtherMonths: true,
		showButtonPanel: true
	});
}
function PDF(type,id){
	window.location = 'pdf'+type+'.php?id='+id;
}
function FinanceEdit(type,i,id){
	switch(type){
		case'edit':{
			
			e('name'+i).style.display = 'none';
			e('amount'+i).style.display = 'none';
			e('valute'+i).style.display = 'none';
			e('date'+i).style.display = 'none';
			e('edit'+i).style.display = 'none';
			e('del'+i).style.display = 'none';
			e('ename'+i).style.display = '';
			e('etype'+i).style.display = '';
			e('eamount'+i).style.display = '';
			e('evalute'+i).style.display = '';
			e('edate'+i).style.display = '';
			e('save'+i).style.display = '';
			e('close'+i).style.display = '';
			e('fedit'+i).style.display = '';
			e('del2'+i).style.display = '';
			
			break;
		}
		case'save':{
			Page('finance&act=save&fid='+id+'&i='+i+'&atype='+e('etype'+i).value+'&aname='+e('ename'+i).value+'&avalue='+e('eamount'+i).value+'&vname='+e('evalute'+i).value+'&adate='+e('edate'+i).value+'&project='+e('project'+i).value,e('row'+i),'');
			// e('name'+i).style.display = '';
			// e('amount'+i).style.display = '';
			// e('valute'+i).style.display = '';
			// e('date'+i).style.display = '';
			// e('edit'+i).style.display = '';
			// e('ename'+i).style.display = 'none';
			// e('etype'+i).style.display = 'none';
			// e('eamount'+i).style.display = 'none';
			// e('evalute'+i).style.display = 'none';
			// e('edate'+i).style.display = 'none';
			// e('save'+i).style.display = 'none';
			// e('close'+i).style.display = 'none';
			break;
		}
		case'close':{
			e('name'+i).style.display = '';
			e('amount'+i).style.display = '';
			e('valute'+i).style.display = '';
			e('date'+i).style.display = '';
			e('edit'+i).style.display = '';
			e('del'+i).style.display = '';
			e('ename'+i).style.display = 'none';
			e('etype'+i).style.display = 'none';
			e('eamount'+i).style.display = 'none';
			e('evalute'+i).style.display = 'none';
			e('edate'+i).style.display = 'none';
			e('save'+i).style.display = 'none';
			e('close'+i).style.display = 'none';
			e('fedit'+i).style.display = 'none';
			e('del2'+i).style.display = 'none';
			break;
		}
	}
	
	
}
function getRadio(radioGroupObj){
	for(var i=0;i<radioGroupObj.length;i++){
		if (radioGroupObj[i].checked) return radioGroupObj[i].value;
	}
	return null;
}
function is_int(value){
	if((parseFloat(value)==parseInt(value))&&!isNaN(value)){
		return true;
	}
	else{ 
		return false;
	} 
}
function AddItem(t,event,act,date,iid,itype){
    
}
function AddItems(t,event,act,date,iid,itype){
	// alert(jQuery(t).parent().parent().parent().parent().children('.itemtxt').children('.itemorder').length);
	// alert(t.scrollHeight);
	if(event.keyCode=='13'){
		var newitemli = document.createElement("LI");
		var newitem = document.createElement("DIV");
		var newitembtn = document.createElement("DIV");
		var newitemtxt = document.createElement("TEXTAREA");
		var newitemorder = document.createElement("SPAN");
		var newitemdate1 = document.createElement("INPUT");
		var newitemdate2 = document.createElement("INPUT");
		newitemtxt.setAttribute('onkeyup',"AddItems(this,event,'','"+date+"','"+iid+"','"+itype+"');");
		newitemli.className = 'itemli';
		newitem.className = 'itemtxt';
		newitembtn.className = 'itembtns';
		newitemtxt.className = 'itemtxtarea';
		newitemorder.className = 'itemorder';
		newitemdate1.className = 'itemdate1';
		newitemdate2.className = 'itemdate2';
		newitemdate1.type = 'text';
		newitemdate2.type = 'text';
		newitemorder.innerHTML = jQuery(t).parent().parent().children('.itemorder').length>0||jQuery(t).parent().parent().parent().parent().children('.itemtxt').children('.itemorder').length>0?jQuery(t).parent().parent().parent().parent().children('.itemtxt').children('.itemorder').html()+'.'+(jQuery(t.parentNode.parentNode.parentNode).children('li').size()+1):jQuery(t.parentNode.parentNode.parentNode).children('li').size()+1;
		newitemdate1.value = date;
		newitemdate2.value = date;
		jQuery(newitemdate1).click(function(){jQuery(this).datepicker({changeMonth: true,changeYear: true,dateFormat: 'yy-mm-dd'});});
		jQuery(newitemdate2).click(function(){jQuery(this).datepicker({changeMonth: true,changeYear: true,dateFormat: 'yy-mm-dd'});});
		Load(newitembtn,'type=btns&act=additem&iid='+iid+'&itype='+itype);
		newitem.appendChild(newitemorder);
		newitem.appendChild(newitemdate1);
		newitem.appendChild(newitemdate2);
		newitem.appendChild(newitembtn);
		newitem.appendChild(newitemtxt);
		newitemli.appendChild(newitem);
		// t.parentNode.parentNode.parentNode.insertBefore(newitemli,t.parentNode.parentNode.nextSibling);
		t.parentNode.parentNode.parentNode.appendChild(newitemli);
		// e('tditems')
		var str = '';
		for(i=0;i<t.value.length-1;i++){
			str += t.value[i];
		}
		t.value = str;
		newitemtxt.focus();
		// alert(jQuery(t).index());
	}
	else if(event.ctrlKey){
		if(event.keyCode==86){
			var at = t.value.split('\n');
			// alert(at.length);
			t.value = at[0];
			for(i=1;i<at.length;i++){
				var newitemli = document.createElement("LI");
				var newitem = document.createElement("DIV");
				var newitembtn = document.createElement("DIV");
				var newitemtxt = document.createElement("TEXTAREA");
				var newitemorder = document.createElement("SPAN");
				var newitemdate1 = document.createElement("INPUT");
				var newitemdate2 = document.createElement("INPUT");
				newitemtxt.setAttribute('onkeyup',"AddItems(this,event,'','"+date+"','"+iid+"','"+itype+"');");
				newitemli.className = 'itemli';
				newitem.className = 'itemtxt';
				newitembtn.className = 'itembtns';
				newitemtxt.className = 'itemtxtarea';
				newitemorder.className = 'itemorder';
				newitemdate1.className = 'itemdate1';
				newitemdate2.className = 'itemdate2';
				newitemdate1.type = 'text';
				newitemdate2.type = 'text';
				newitemorder.innerHTML = jQuery(t).parent().parent().children('.itemorder').length>0||jQuery(t).parent().parent().parent().parent().children('.itemtxt').children('.itemorder').length>0?jQuery(t).parent().parent().parent().parent().children('.itemtxt').children('.itemorder').html()+'.'+(jQuery(t.parentNode.parentNode.parentNode).children('li').size()+1):jQuery(t.parentNode.parentNode.parentNode).children('li').size()+1;
				newitemdate1.value = date;
				newitemdate2.value = date;
				jQuery(newitemdate1).click(function(){jQuery(this).datepicker({changeMonth: true,changeYear: true,dateFormat: 'yy-mm-dd'});});
				jQuery(newitemdate2).click(function(){jQuery(this).datepicker({changeMonth: true,changeYear: true,dateFormat: 'yy-mm-dd'});});
				Load(newitembtn,'type=btns&act=additem&iid='+iid+'&itype='+itype);
				newitem.appendChild(newitemorder);
				newitem.appendChild(newitemdate1);
				newitem.appendChild(newitemdate2);
				newitem.appendChild(newitembtn);
				newitem.appendChild(newitemtxt);
				newitemli.appendChild(newitem);
				// t.parentNode.parentNode.parentNode.insertBefore(newitemli,t.parentNode.parentNode.nextSibling);
				t.parentNode.parentNode.parentNode.appendChild(newitemli);
				// e('tditems')
				// var str = '';
				// for(i=0;i<t.value.length-1;i++){
					// str += t.value[i];
				// }
				// t.value = str;
				newitemtxt.value = at[i];
				
			}
		}
	}
	else if(act=='sub'){//alert(t.parentNode.parentNode.parentNode.className.replace('itemli lid',''));
		var newitemul = document.createElement("UL");
		var newitemli = document.createElement("LI");
		var newitem = document.createElement("DIV");
		var newitembtn = document.createElement("DIV");
		var newitemtxt = document.createElement("TEXTAREA");
		var newitemorder = document.createElement("SPAN");
		var newitemdate1 = document.createElement("INPUT");
		var newitemdate2 = document.createElement("INPUT");
		newitemtxt.setAttribute('onkeyup',"AddItems(this,event,'','"+date+"')");
		newitemli.className = t.parentNode.parentNode.parentNode.className+'_1';
		newitem.className = 'itemtxt';
		newitembtn.className = 'itembtns';
		newitemtxt.className = 'itemtxtarea';
		newitemorder.className = 'itemorder';
		newitemdate1.className = 'itemdate1';
		newitemdate2.className = 'itemdate2';
		newitemdate1.type = 'text';
		newitemdate2.type = 'text';
		newitemorder.innerHTML = jQuery(t).parent().parent().children('.itemorder').html()+'.'+1;
		newitemdate1.value = date;
		newitemdate2.value = date;
		jQuery(newitemdate1).click(function(){jQuery(this).datepicker({changeMonth: true,changeYear: true,dateFormat: 'yy-mm-dd'});});
		jQuery(newitemdate2).click(function(){jQuery(this).datepicker({changeMonth: true,changeYear: true,dateFormat: 'yy-mm-dd'});});
		Load(newitembtn,'type=btns&act=additem');
		newitem.appendChild(newitemorder);
		newitem.appendChild(newitemdate1);
		newitem.appendChild(newitemdate2);
		newitem.appendChild(newitembtn);
		newitem.appendChild(newitemtxt);
		newitemli.appendChild(newitem);
		newitemul.appendChild(newitemli);
		// t.parentNode.parentNode.parentNode.insertBefore(newitemul,t.parentNode.parentNode.nextSibling);
		t.parentNode.parentNode.parentNode.appendChild(newitemul);
		// e('tditems')
		// var str = '';
		// for(i=0;i<t.value.length-1;i++){
			// str += t.value[i];
		// }
		// t.value = str;
		newitemtxt.focus();
	}
	// t.style.height = t.scrollHeight+'px';
}
function FormDebug(f,id){
	var url = '';
	jQuery(f).find('input').each(function(){
		// if(jQuery(this).attr('type')=='text'||){
			// alert(jQuery(this).val());
			url += '&'+jQuery(this).attr('name')+'='+jQuery(this).val();
		// }
	});
	jQuery(f).find('select').each(function(){
		// alert(jQuery(this).val());
		url += '&'+jQuery(this).attr('name')+'='+jQuery(this).val();
	});
	jQuery(f).find('textarea').each(function(){
		// alert(jQuery(this).val());
		url += '&'+jQuery(this).attr('name')+'='+jQuery(this).val().replaceAll("'",'[-quot-]').replaceAll('&','[-amp-]').replace(/\n/gi,"<br />");
	});
	// alert(url.substr(1));
	Load(id,url.replace('&',''));
	setTimeout(function() {
		jQuery('.countdown').find('.countdownvalue').each(function(){
			if(jQuery(this).find('.countdownspan')){
				var nd = jQuery(this).attr('title').split(' ');
				var ndy = nd[0].split('-');
				var ndt = nd[1].split(':');
				nd = new Date(ndy[0],ndy[1]-1,ndy[2],ndt[0],ndt[1],ndt[2]);
				jQuery(this).find('.countdownspan').countdown({until:nd,compact:true,format:'dHMS',layout: '{dn}<b class="fs-10 tt-u c-999">{dl}</b>  {hnn}<b class="fs-10 tt-u c-999">{hl}</b> {mnn}<b class="fs-10 tt-u c-999">{ml} </b>{snn}<b class="fs-10 tt-u c-999">{sl}</b>{desc}',description:'',compactLabels:['г.','мес.','нед.','дн.','ч.','м.','с.']});
			}
		});
	},500);
	if(jQuery(f).find('input[name="act"]').val()=='settings'){
		jQuery('#page').removeClass('widthauto').removeClass('width800').addClass('width'+jQuery(f).find('select[name="display"]').val());
	}
}
function AddItemsForm(f,url,num){
	// alert(f.act.value);
	switch(f.act.value){
		case'post':{
			var str = f.name.value;
			if(str==''){
				alert('Заполните поле "Название"!');
				return false
			}
			Load(e('content'),'type=add&act=post&name='+str+'&parent='+f.parent.value+'&act2=add');
			Remove(e('messwindowin'+num));
			break;
		}
		case'topost':{
			// var str = f.name.value;
			if(f.post.value==''){
				alert('Выберите "Должность"! Если нет ниодной должности, тогда сначала добавьте хотя бы одну должность.');
				return false
			}
			Load(e('content'),'type=add&act=topost&act2=add&post='+f.post.value+'&uuid='+f.uuid.value+'&cost='+f.cost.value+'&pval='+getRadio(f.pval)+'&html='+getRadio(f.html)+'&css='+getRadio(f.css)+'&php='+getRadio(f.php)+'&js='+getRadio(f.js)+'&mysql='+getRadio(f.mysql)+'&joomla='+getRadio(f.joomla)+'&user='+f.user.value+'');
			Remove(e('messwindowin'+num));
			break;
		}
		case'item':{
			var str = '';
			jQuery(f).find('.itemorder').each(function(){
				if(jQuery(this).parent().find('.itemtxtarea').val()!=''&&jQuery(this).parent().parent().css('display')!='none'){
					str += '[-s-]'+jQuery(this).html()+'[-|-]'+jQuery(this).parent().find('.itemtxtarea').val().replaceAll("'",'[-quot-]').replaceAll('&','[-amp-]').replace(/\n/gi,"<br />")+'[-|-]'+jQuery(this).parent().find('.itemdate1').val()+'[-|-]'+jQuery(this).parent().find('.itemdate2').val();
				}
			});
			if(str==''){
				alert('Заполните задание!');
				return false
			}
			
			Load(e('center'),'type=add&act=item&str='+str+'&task='+f.task.value+'&pid='+f.pid.value+'&act2=add&idproject='+f.idproject.value+'&taskname='+f.taskname.value+'&tasknew='+f.tasknew.value+'&cost='+f.cost.value);
			//Remove(e('messwindowin'+num));
			break;
		}
		case'task':{
			// var str = '';
			// jQuery(f).find('.itemorder').each(function(){
				// if(jQuery(this).parent().find('.itemtxtarea').val()!=''&&jQuery(this).parent().parent().css('display')!='none'){
					// str += '[-s-]'+jQuery(this).html()+'[-|-]'+jQuery(this).parent().find('.itemtxtarea').val().replaceAll("'",'[-quot-]').replaceAll('&','[-amp-]').replace(/\n/gi,"<br />")+'[-|-]'+jQuery(this).parent().find('.itemdate1').val()+'[-|-]'+jQuery(this).parent().find('.itemdate2').val();
				// }
			// });
			// if(jQuery(f.task).find(':selected').attr('title')==1&&f.tasknew.value==''){
				// alert('Заполните задание!');
				// return false
			// }
			// alert(jQuery(f.task).attr('p'));
			var cost = 0;
			var val = 0;
			if(jQuery(f.task).attr('p')=='1'){
				var n = '.taskcost[p='+f.task.value+']';
				cost = jQuery(n).val();
				n = '.taskval[p='+f.task.value+']';
				val = jQuery(n).val();
			}
			else{
				var n = '.taskcost[p=0]';
				cost = jQuery(n).val();
				n = '.taskval[p=0]';
				val = jQuery(n).val();
			}
			Load(e('messwindowin_2'+num),'type=add&act=task&task='+f.task.value+'&tasknew='+f.tasknew.value+'&taskdate2='+f.taskdate2.value+'&act2=add&num='+num+'&cost='+cost+'&val='+val);
			// Remove(e('messwindowin'+num));
			break;
		}
		case'project':{
			var client = '';
			var clientact = '';
			if(f.client.value>0){
				client = f.client.value;
				clientact = 'old';
			}
			else{
				client = f.cname.value;
				clientact = 'new';
			}
			Load(e('content'),'type=add&act=project&pname='+f.pname.value+'&incost='+f.incost.value+'&date2='+f.date2.value+'&act2=add&client='+client+'&clientact='+clientact+'&pval='+getRadio(f.pval)+'&inputs='+f.inputs.value.replaceAll("'",'[-quot-]').replaceAll('&','[-amp-]').replace(/\n/gi,"<br />")+'&outcost='+f.outcost.value+'&prio='+f.pprio.value);
			Remove(e('messwindowin'+num));
			break;
		}
		case'clients':{
			Load(e('content'),'type=add&act=client&act2=add&cname='+f.cname.value);
			Remove(e('messwindowin'+num));
			break;
		}
		case'finance':{
			// if(){
				
			// }
			var name = '';
			// alert(jQuery('.addfinance').html());
			var str = '';
			var error = 0;
			jQuery('.addfinance').find('.addfinanceone').each(function(){
				// alert(jQuery(this).find('.datepicker').val());
				error = 0;
				str += jQuery(this).find('.datepicker').val()+'[-s-]';
				str += jQuery(this).find('.optype').val()+'[-s-]';
				if(jQuery(this).find('.opcost').val()=='0.00'||jQuery(this).find('.opcost').val()<=0){
					jQuery(this).find('.opcost').addClass('error');
					error = 1;
				}
				else{
					jQuery(this).find('.opcost').removeClass('error');
					// error = 0;
				}
				if((jQuery(this).find('.optype').val()==775||jQuery(this).find('.optype').val()==777)&&(jQuery(this).find('.opname').val()=='Name'||jQuery(this).find('.opname').val()==''||jQuery(this).find('.opname').val()=='Name')){
					jQuery(this).find('.opname').addClass('error');
					error = 1;
				}
				else{
					jQuery(this).find('.opname').removeClass('error');
					// if(error==0){
						// error = 0;
					// }
				}
				if(jQuery(this).find('.optype').val()==0){
					jQuery(this).find('.optype').addClass('error');
					error = 1;
				}
				else{
					jQuery(this).find('.optype').removeClass('error');
				}
				if(jQuery(this).find('.optype').val()==62&&(jQuery(this).find('.fromfee').val()=='0.00'||jQuery(this).find('.fromfee').val()<=0)){
					jQuery(this).find('.fromfee').addClass('error');
					error = 1;
				}
				else{
					jQuery(this).find('.fromfee').removeClass('error');
				}
				if(jQuery(this).find('.optype').val()==62&&(jQuery(this).find('.to').val()=='0.00'||jQuery(this).find('.to').val()<=0)){
					jQuery(this).find('.to').addClass('error');
					error = 1;
				}
				else{
					jQuery(this).find('.to').removeClass('error');
				}
				
				
				str += jQuery(this).find('.opcost').val()+'[-s-]';
				str += jQuery(this).find('.opvalute').val()+'[-s-]';
				str += jQuery(this).find('.opname').val()+'[-s-]';
				str += jQuery(this).find('.cost2').val()+'[-s-]';
				
				// if(jQuery(this).find('.optype').val()==774||jQuery(this).find('.optype').val()==776||jQuery(this).find('.optype').val()==59||jQuery(this).find('.optype').val()==60){
				str += jQuery(this).find('.optype').val()+'[-s-]';
				str += jQuery(this).find('.op774776').val()+'[-s-]';
				str += jQuery(this).find('.op61').val()+'[-s-]';
				str += jQuery(this).find('.fromfee').val()+'[-s-]';
				str += jQuery(this).find('.to').val()+'[-s-]';
				str += jQuery(this).find('.toval').val();
				// }
				// if(){
					// str += jQuery(this).find('.op59').val()+'[-s-]';
				// }
				// else if(){
					// str += jQuery(this).find('.op60').val()+'[-s-]';
				// }
				str += '[-=s=-]';
				// alert(jQuery(this).find('.opcost').val());
				// alert(jQuery(this).find('.opname').val());
				// alert(jQuery(this).find('.opvalute').val());
				// alert(jQuery(this).find('.op59').val());
				// alert(jQuery(this).find('.op60').val());
				// alert(jQuery(this).find('.op774776').val());
			});
			if(error==1){
				jQuery('.addfinanceerror').show();
				return false;
			}
			jQuery('.addfinanceerror').hide();
			// alert(str);
			// if(getRadio(f.ftype)==59&&f.factive.value!='[-new-]'){}
			Load(e('content'),'type=add&act=finance&act2=add&str='+str);
			Remove(e('messwindowin'+num));
			break;
		}
		case'site':{
			Load(e('content'),'type=add&act=site&act2=add&alias='+f.alias.value);
			Remove(e('messwindowin'+num));
			break;
		}
	}
}

function TasksCheck(project){
	if(jQuery('.taskcheck:checked').length>0){
		e('tasksoperations'+project).style.display = '';
	}
	else{
		e('tasksoperations'+project).style.display = 'none';
	}
}
function TasksDelete(project){
	if(confirm('Are you sure you want to delete that item(s)?')){
		var str = '';
		var name = '';
		jQuery('.taskcheck:checked').each(function(){
			str+=jQuery(this).val()+'[-s-]';
			name = '#task'+jQuery(this).val();
			jQuery(name).slideUp(200);
			jQuery(this).attr('checked', false);
		});
		//e('tasksoperations'+project).style.display = 'none';
		Page('delete&act=task&idin='+str,'h');
		// Load(e('content'),'type=delete&act=task&idin='+str);
	}
}
function TasksArchive(project){
	if(confirm('Are you sure you want to send that item(s) to archive?')){
		var str = '';
		var name = '';
		jQuery('.taskcheck:checked').each(function(){
			str+=jQuery(this).val()+'[-s-]';
			name = '#task'+jQuery(this).val();
			jQuery(name).slideUp(200);
			jQuery(this).attr('checked', false);
		});
		//e('tasksoperations'+project).style.display = 'none';
		Page('status&act=task&idin='+str+'&nstatus=4','h');
		// Load(e('content'),'');
	}
}
function TasksStatus(task){
	var str = '';
	jQuery('.taskcheck:checked').each(function(){
		str+=jQuery(this).val()+'[-s-]';
	});
	jQuery('.btnst li.btnst0').click(function(){Load(e('content'),'type=status&act=item&idin='+str+'&nstatus=0&ntask='+task);});
	jQuery('.btnst li.btnst1').click(function(){Load(e('content'),'type=status&act=item&idin='+str+'&nstatus=1&ntask='+task);});
	jQuery('.btnst li.btnst2').click(function(){Load(e('content'),'type=status&act=item&idin='+str+'&nstatus=2&ntask='+task);});
	jQuery('.btnst li.btnst3').click(function(){Load(e('content'),'type=status&act=item&idin='+str+'&nstatus=3&ntask='+task);});
	jQuery('.btnst li.btnst6').click(function(){Load(e('content'),'type=status&act=item&idin='+str+'&nstatus=6&ntask='+task);});
	jQuery('#itemsstatus').buttonset().show();
}
function TaskEdit(id){
	
}
function TasksResize(id){
	if(parseInt(jQuery('#project_tasks'+id+'').css('width'))==400){
		jQuery('#project_tasks'+id+' .arrow').hide();
		jQuery('#project_tasks'+id+'').css({'width':'100%'});
	}
	else{
		jQuery('#project_tasks'+id+' .arrow').show();
		jQuery('#project_tasks'+id+'').css({'width':'400px'});
	}
}

function ItemCheck(task){
	if(jQuery('.itemcheck:checked').length>0){
		e('itemsoperations').style.display = '';
	}
	else{
		e('itemsoperations').style.display = 'none';
	}
	ItemsStatus(task);
}
function ItemsDelete(task){
	if(confirm('Вы уверенны, что хотите удалить эти пункты?')){
		var str = '';
		jQuery('#taskitems'+task+'in .itemcheck:checked').each(function(){
			str+=jQuery(this).val()+'[-s-]';
		});
		Load(e('content'),'type=delete&act=item&idin='+str+'&ntask='+task);
	}
}
function ItemEdit(task){
	// var str = '';
	jQuery('#taskitems'+task+'in .itemcheck:checked').each(function(){
		// str+=jQuery(this).val()+'[-s-]';
		Load(e('i'+jQuery(this).val()+'e'),'type=edit&act=item&idin='+jQuery(this).val()+'');
	});
	
}
function ItemsUsers(){
	var str = '';
	jQuery('.itemcheck:checked').each(function(){
		str+=jQuery(this).val()+'[-s-]';
	});
	Page('add&act=userto&idin='+str,'0','');
}
function ItemsStatus(task){
	if(jQuery('.itemcheck:checked').length>0){
		var str = '';
		jQuery('.itemcheck:checked').each(function(){
			str+=jQuery(this).val()+'[-s-]';
		});
		jQuery('.btnst .st0').click(function(){Load(e('content'),'type=status&act=item&idin='+str+'&nstatus=0&ntask='+task);});
		jQuery('.btnst .st1').click(function(){Load(e('content'),'type=status&act=item&idin='+str+'&nstatus=1&ntask='+task);});
		jQuery('.btnst .st2').click(function(){Load(e('content'),'type=status&act=item&idin='+str+'&nstatus=2&ntask='+task);});
		jQuery('.btnst .st3').click(function(){Load(e('content'),'type=status&act=item&idin='+str+'&nstatus=3&ntask='+task);});
		jQuery('.btnst .st6').click(function(){Load(e('content'),'type=status&act=item&idin='+str+'&nstatus=6&ntask='+task);});
		jQuery('#itemsstatus').buttonset().show();
	}
	else{
		jQuery('.btnst li.btnst0').unbind("click");
		jQuery('.btnst li.btnst1').unbind("click");
		jQuery('.btnst li.btnst2').unbind("click");
		jQuery('.btnst li.btnst3').unbind("click");
		jQuery('.btnst li.btnst6').unbind("click");
		jQuery('#itemsstatus').buttonset().hide();
	}
}
function ItemsHide(){
	jQuery('.itemcheck:checked').each(function(){
		jQuery(this).parent().parent().parent().parent().parent().slideUp(350);
	});
}
function ItemsTimed(){
    jQuery('#itemstimed table').css({'display':'none'});
	Load(e('itemstimed'),'type=itemstimed');
    
}
function Item(act,id,status,time,el,task){
	switch(act){
		case'block':{
			if(confirm('Are you sure you want to lock this item (it mean that this item will '+(status==3?'':'not')+' be done)?')){
				jQuery('table.item'+id+'').removeClass('st1').removeClass('st2').removeClass('st4').removeClass('st5').removeClass('st6').addClass('st3');
				if(status==3){
					jQuery('.itemtime'+id+'').show();
				}
				else{
					jQuery('.itemtime'+id+'').hide();
				}
				Page('itemtime&itemid='+id+'&act='+(status==3?'un':'')+'lock','h');
				// ItemsTimed();
				jQuery(el).find('span').removeClass('icon-'+(status==3?'un':'')+'locked');
				jQuery(el).find('span').addClass('icon-'+(status==3?'':'un')+'locked');
				jQuery(el).parent().attr('width',''+(status==3?'7':'2')+'5');
			}
			break;
		}
		case'help':{
			if(jQuery('table.item'+id+'').hasClass('st2')){
				alert('Pause an item before!');
				return false;
			}
			if(jQuery('table.item'+id+'').hasClass('st5')){var sstt=1;}
			else{var sstt=0;}
			if(sstt==0){jQuery('table.item'+id+'').removeClass('st1').removeClass('st2').removeClass('st4').removeClass('st3').removeClass('st6').addClass('st5');}
			else{jQuery('table.item'+id+'').removeClass('st1').removeClass('st2').removeClass('st4').removeClass('st5').removeClass('st3').addClass('st6');}
			Page('itemtime&itemid='+id+'&act=help','h');
			// ItemsTimed();
			break;
		}
		case'stop':{
			if(confirm('Are you sure you want to set this item Done?')){
				jQuery('table.item'+id+'').removeClass('st3').removeClass('st2').removeClass('st4').removeClass('st5').removeClass('st6').addClass('st1');
				jQuery('.itemtime'+id+'').hide();
				Page('itemtime&itemid='+id+'&act=stop','h');
				// ItemsTimed();
			}
			break;
		}
		case'pause':{
			jQuery('table.item'+id+'').removeClass('st1').removeClass('st2').removeClass('st4').removeClass('st5').removeClass('st3').addClass('st6');
			jQuery(el).hide();
			jQuery(el).parent().find('.play').show();
			// jQuery('.time'+id+'').countdown('pause');
			Page('itemtime&itemid='+id+'&act=pause','h');
			// ItemsTimed();
			break;
		}
		case'play':{
			jQuery('table.item'+id+'').removeClass('st1').removeClass('st3').removeClass('st4').removeClass('st5').removeClass('st6').addClass('st2');
			jQuery(el).hide();
			jQuery(el).parent().find('.pause').show();
			if(jQuery('.time'+id+'').html()!='0.00'){
				Page('itemtime&itemid='+id+'&act=start&act2=0','h');
				// jQuery('.time'+id+'').countdown('resume');
			}
			else{
				Page('itemtime&itemid='+id+'&act=start&act2=1','h');
				// jQuery('.time'+id+'').countdown({since:jQuery('.time'+id+'').html(),compact:true,format:'HMS'});
			}
			// ItemsTimed();
			break;
		}
	}
	Page('taskstatus&tid='+task,e('taskprogress'+task));
}

function ProjectsArchive(){
	if(confirm('Вы уверенны, что хотите отправить эти задания в архив?')){
		var str = '';
		jQuery('.projectcheck:checked').each(function(){
			str+=jQuery(this).val()+'[-s-]';
		});
		Load(e('content'),'type=status&act=project&idin='+str+'&nstatus=4');
	}
}
function ProjectsDelete(){
	if(confirm('Are You sure You want to delete this projects(s)?')){
		var str = '';
		jQuery('.projectcheck:checked').each(function(){
			str+=jQuery(this).val()+'[-s-]';
		});
		Load(e('content'),'type=delete&act=project&idin='+str);
	}
}
function ProjectsCheck(){
	if(jQuery('.projectcheck:checked').length>0){
		e('projectsoperations').style.display = '';
		ProjectsStatus();
	}
	else{
		e('projectsoperations').style.display = 'none';
		ProjectsStatus();
	}
}
function ProjectStatus(act,pid){
	switch(act){
		case'delete':{
			// Load(e('content'),'type=delete&act=project&idin='+str);
			jQuery('#project'+pid).remove();
			Page('delete&act=project&idin='+pid,'0','hide');
			break;
		}
		case'archive':{
			jQuery('#project'+pid).prependTo('#ownarchiveprojects .projectblocks .projectblock:first-child ul.projects').find('.btnarchive').click(function(){
				ProjectStatus('active',pid);
			});
			Page('status&act=project&idin='+pid+'&nstatus=4','0','hide');
			break;
		}
		case'active':{
			jQuery('#project'+pid).appendTo('.projectblocks .projectblock:last-child ul.projects').find('.btnarchive').click(function(){
				ProjectStatus('archive',pid);
			});
			Page('status&act=project&idin='+pid+'&nstatus=0','0','hide');
			break;
		}
	}
}
function ProjectsHide(){
	jQuery('.projectcheck:checked').each(function(){
		jQuery(this).parent().parent().slideUp(350);
	});
}
function Project(id){
	if(jQuery('#project'+id).is('.active')){
		//jQuery('#project_tasks'+id).hide();
		// jQuery('#project_tasks'+id+'bg').hide();
		jQuery('ul.projects>li').removeClass('active');
		jQuery('#center').html('');
		//jQuery('#project_tasks'+id).hide();
		// jQuery('#project_tasks'+id+' .arrow').show();
		// jQuery('#project_tasks'+id).css({'width':'400px'});
	}
	else{
		//jQuery('ul.projects .project-tasks').hide();
		//jQuery('#project_tasks'+id).show();
		// jQuery('#project_tasks'+id+'bg').show();
		jQuery('ul.projects>li').removeClass('active');
		jQuery('#project'+id).addClass('active');
		// Page('tasks&idproject='+id,e('project_tasks'+id+'in'));
		Page('tasks&idproject='+id,e('center'));
	}
}

function ClientsArchive(){
	if(confirm('Вы уверенны, что хотите отправить эти задания в архив?')){
		var str = '';
		jQuery('.projectcheck:checked').each(function(){
			str+=jQuery(this).val()+'[-s-]';
		});
		Load(e('content'),'type=status&act=project&idin='+str+'&nstatus=4');
	}
}
function ClientsDelete(){
	if(confirm('Are You sure You want to delete this client(s)?')){
		var str = '';
		jQuery('.clientcheck:checked').each(function(){
			str+=jQuery(this).val()+'[-s-]';
		});
		Load(e('content'),'type=delete&act=client&idin='+str);
	}
}
function ClientsCheck(){
	if(jQuery('.clientcheck:checked').length>0){
		e('clientsoperations').style.display = '';
		ProjectsStatus();
	}
	else{
		e('clientsoperations').style.display = 'none';
		ProjectsStatus();
	}
}
function ClientsStatus(){
	if(jQuery('.projectcheck:checked').length>0){
		var str = '';
		jQuery('.projectcheck:checked').each(function(){
			str+=jQuery(this).val()+'[-s-]';
		});
		jQuery('.btnst .st0').click(function(){Load(e('content'),'type=status&act=project&idin='+str+'&nstatus=0');});
		jQuery('.btnst .st1').click(function(){Load(e('content'),'type=status&act=project&idin='+str+'&nstatus=1');});
		jQuery('.btnst .st2').click(function(){Load(e('content'),'type=status&act=project&idin='+str+'&nstatus=2');});
		jQuery('.btnst .st3').click(function(){Load(e('content'),'type=status&act=project&idin='+str+'&nstatus=3');});
		jQuery('.btnst .st4').click(function(){Load(e('content'),'type=status&act=project&idin='+str+'&nstatus=4');});
		jQuery('#projectsstatus').buttonset().show();
	}
	else{
		jQuery('.btnst .nst0').unbind("click");
		jQuery('.btnst .st1').unbind("click");
		jQuery('.btnst .st2').unbind("click");
		jQuery('.btnst .st3').unbind("click");
		jQuery('.btnst .st4').unbind("click");
		jQuery('#projectsstatus').buttonset().hide();
	}
}
function ClientsHide(){
	jQuery('.projectcheck:checked').each(function(){
		jQuery(this).parent().parent().slideUp(350);
	});
}
function Client(id){
	if(jQuery('#project'+id).is('.active')){
		jQuery('#project_tasks'+id).hide();
		jQuery('#project_tasks'+id+'bg').hide();
		jQuery('ul.projects>li').removeClass('active');
		jQuery('#project_tasks'+id).hide();
		// jQuery('#project_tasks'+id+' .arrow').show();
		// jQuery('#project_tasks'+id).css({'width':'400px'});
	}
	else{
		jQuery('ul.projects .project-tasks').hide();
		jQuery('#project_tasks'+id).show();
		jQuery('#project_tasks'+id+'bg').show();
		jQuery('ul.projects>li').removeClass('active');
		jQuery('#project'+id).addClass('active');
		Page('tasks&idproject='+id,e('project_tasks'+id+'in'));
	}
}

function FinanceDelete(id){
	if(confirm('Are You sure You want to delete?')){
		var str = '';
		// jQuery('.financecheck:checked').each(function(){
			str+=id+'[-s-]';
			jQuery('#operation'+id).slideUp(500);
			// jQuery(this).prop('checked',false);
		// });
		// alert(str);
		Page('delete&act=finance&idin='+str,'h','');
		// Load(e('content'),'type=delete&act=finance&idin='+str);
	}
}
function FinanceCheck(){
	// jQuery('.itemcheck').click(function(){
		// alert(11);
	// });
	if(jQuery('.financecheck:checked').length>0){
		e('financeoperations').style.display = '';
	}
	else{
		e('financeoperations').style.display = 'none';
	}
}
function FinanceStatus(){
	var str = '';
	jQuery('.projectcheck:checked').each(function(){
		str+=jQuery(this).val()+'[-s-]';
	});
	// alert(str);
	// var newitem = document.createElement("DIV");
	jQuery('.btnst li.btnst0').click(function(){Load(e('content'),'type=status&act=project&idin='+str+'&nstatus=0');});
	jQuery('.btnst li.btnst1').click(function(){Load(e('content'),'type=status&act=project&idin='+str+'&nstatus=1');});
	jQuery('.btnst li.btnst2').click(function(){Load(e('content'),'type=status&act=project&idin='+str+'&nstatus=2');});
	jQuery('.btnst li.btnst3').click(function(){Load(e('content'),'type=status&act=project&idin='+str+'&nstatus=3');});
	jQuery('.btnst li.btnst4').click(function(){Load(e('content'),'type=status&act=project&idin='+str+'&nstatus=4');});
	jQuery('#projectsstatus').buttonset().show();
	// e('itemsstatus').appendChild(newitem);
}
function FinanceHide(){
	jQuery('.financecheck:checked').each(function(){
		jQuery(this).parent().parent().parent().parent().parent().slideUp(350);
	});
}
function FinanceChange(act,id){
	switch(act){
		case'save':{
			
			// jQuery('#fo'+id).ready(function(){
				// if(jQuery(this).css('display')=='none'){
					// str+=jQuery(this).val()+'[-|-]'+jQuery(this).parent().parent().find('.etype').val()+'[-|-]'+jQuery(this).parent().parent().find('.epro').val()+'[-|-]'+jQuery(this).parent().parent().find('.ename').val()+'[-|-]'+jQuery(this).parent().parent().find('.ecost').val()+'[-|-]'+jQuery(this).parent().parent().find('.eval').val()+'[-|-]'+jQuery(this).parent().parent().find('.edate').val()+'[-s-]';
				// }
				var str = '';
				str += jQuery('#fo'+id).find('.ecost').val()+'[-|-]';
				str += jQuery('#fo'+id).find('.eval').val()+'[-|-]';
				str += jQuery('#fo'+id).find('.ename').val()+'[-|-]';
				if(jQuery('#fo'+id).find('.etype').val()=='61'){
					str += jQuery('#fo'+id).find('.op61').val()+'[-|-]';
				}
				else if(jQuery('#fo'+id).find('.etype').val()=='62'){
					str += '62[-|-]';
				}
				else{
					str += jQuery('#fo'+id).find('.epro').val()+'[-|-]';
				}
				
				str += jQuery('#fo'+id).find('.edate').val()+'[-|-]';
				str += jQuery('#fo'+id).find('.etype').val()+'[-|-]';
				str += id+'[-|-]';
				if(jQuery('#fo'+id).find('.espan').val()==59||jQuery('#fo'+id).find('.espan').val()==60){
					str += jQuery('#fo'+id).find('.etype2').val();
					// if(){
						// str += 
					// }
				}
				else if(jQuery('#fo'+id).find('.espan').val()>60){
					str += jQuery('#fo'+id).find('.evalspan').val()+'[-|-]';
					str += jQuery('#fo'+id).find('.ecostfee').val()+'[-|-]';
					str += jQuery('#fo'+id).find('.ecostconv').val();
					
				}
				else{
					str += jQuery('#fo'+id).find('.espan').val();
				}
				
				
				var name = 'operation'+id;
//				alert(name+"\n"+str);
				Load(e(name),'type=finance&act=save&str='+str);
			// });
//			alert(str);
			
			
			break;
		}
		case'cancel':{
			jQuery('#fo'+id).find('tr.saved').removeClass('d-n');
			jQuery('#fo'+id).find('tr.edit').addClass('d-n');
			break;
		}
		default:{
			// jQuery('.financecheck:checked').each(function(){
				// alert(jQuery(this).parent().parent().html());
				// Load(jQuery(this).parent().parent(),'');
				jQuery('#fo'+id).find('tr.edit').removeClass('d-n');
				jQuery('#fo'+id).find('tr.saved').addClass('d-n');
				jQuery('.edit').ready(function(){jQuery(".datepicker").datepicker({changeMonth: true,changeYear: true,dateFormat: "yy-mm-dd"});});
				// jQuery(this).addClass('d-n');
			// });
		}
	}
	
}
function AddUsers(eid,uid,num){
	// alert(eid+'-'+uid);
	Load(e('messwindowin_2'+num),'type=add&act=userto&act2=add&eid='+eid+'&uid='+uid+'&num='+num);
}
function FinanceOperation(v,id,av){
	switch(v){
		case'60':
		case'59':
		case'776':
		case'774':{
			jQuery(id).parent().find('.op774776').show();
			jQuery(id).parent().find('.opvalute').hide();
			jQuery(id).parent().find('.opvalute').val(valarray[jQuery(id).parent().find('.op774776').val()]);
			jQuery(id).parent().find('.conv').hide();
			break;
		}
		case'project':{
			jQuery(id).parent().find('.opvalute').val(valarray[jQuery(id).parent().find('.op774776').val()]);
			break;
		}
		case'61':{
			jQuery(id).parent().find('.op61').show();
			jQuery(id).parent().find('.op59').hide();
			jQuery(id).parent().find('.op60').hide();
			jQuery(id).parent().find('.opvalute').show();
			jQuery(id).parent().find('.op774776').hide();
			jQuery(id).parent().find('.conv').hide();
			break;
		}
		case'62':{
			jQuery(id).parent().find('.op61').hide();
			jQuery(id).parent().find('.op59').hide();
			jQuery(id).parent().find('.op60').hide();
			jQuery(id).parent().find('.opvalute').show();
			jQuery(id).parent().find('.op774776').hide();
			jQuery(id).parent().find('.conv').show();
			break;
		}
		default:{
			jQuery(id).parent().find('.op61').hide();
			jQuery(id).parent().find('.op59').hide();
			jQuery(id).parent().find('.op60').hide();
			jQuery(id).parent().find('.op774776').hide();
			jQuery(id).parent().find('.opvalute').show();
			jQuery(id).parent().find('.conv').hide();
			break;
		}
	}
}
function FinanceFilter(act,v){
	switch(act){
		case'val':{
			if(v=='0'){
				jQuery('.finance li').show();
			}
			else{
				jQuery('.finance li').each(function(){
					if(!jQuery(this).hasClass('v'+v)){
						jQuery(this).hide();
					}
					else{
						// jQuery(this).show();
					}
				});
				// jQuery('.finance li').hide();jQuery('.finance li.v'+v).show();
			}
			break;
		}
		case'income':{
			if(v=='0'){
				jQuery('.finance li').show();
			}
			else{
				jQuery('.finance li').hide();jQuery('.finance li.'+v).show();
			}
			break;
		}
	}
}

function SitesDownload(aid){
	Page('sites&act=download&aid='+aid,'0','');
}

function AddFile(id,uid,iid,itype){
	var newdiv = document.createElement("DIV");
	var newdivin = document.createElement("DIV");
	var newbtn1 = document.createElement("SPAN");
	var newbtn1in = document.createElement("SPAN");
	newdiv.className = 'addfile';
	newdivin.className = 'addfilein';
	newbtn1.className = 'btn f-r';
	newbtn1in.className = 'icon3 icon-close';
	newbtn1.onclick = Function("jQuery(this).parent().remove()");
	Load(newdivin,'type=add&act=file&uid='+uid+'&iid='+iid+'&itype='+itype);
	newbtn1.appendChild(newbtn1in);
	newdiv.appendChild(newbtn1);
	newdiv.appendChild(newdivin);
	id.parentNode.parentNode.appendChild(newdiv);
}
function InsertFile(id){
	var f = jQuery(id).parent().find('select.ffff').val().split('[-]');
	var t = jQuery(id).parent().parent().parent().find('textarea').val();
	t += getCookie('siteurl')+f[1];
	jQuery(id).parent().parent().parent().find('textarea').val(t);
	jQuery(id).parent().parent().remove();
}
function AddInfo(id,save,num){
	switch(save){
		case'save':{

			// alert(num);
			var str = '';
			jQuery('.sets').find('.set').each(function(){//alert(jQuery(this)[0].tagName);
				str += '[-s-]'+jQuery(this).attr('name')+'[=]'+jQuery(this).val()+'[=]'+jQuery(this)[0].tagName;
			});
			// alert(str.replace('[-s-]',''));
			Load(e('messwindowin_2'+num),'type=edit&act=settings&act2=add&str='+str.replace('[-s-]','')+'&num='+num);
			break;
		}
		default:{
			var newtr = document.createElement("TR");
			var newth = document.createElement("TH");
			var newtd = document.createElement("TD");
			// alert(jQuery(id).parent().children()[0].nodeName);
			newth.innerHTML = jQuery(id).parent().children(0).val();
			switch(jQuery(id).parent().children()[0].nodeName){
				case'INPUT':{
					jQuery(newtd).html('<input type="text" name="'+jQuery(id).parent().children(0).val()+'" class="set" />');
					break;
				}
				case'TEXTAREA':{
					jQuery(newtd).html('<textarea name="'+jQuery(id).parent().children(0).val()+'" class="set"></textarea>');
					break;
				}
			}
			
			newtr.appendChild(newth);
			newtr.appendChild(newtd);
			jQuery('.sets').append(newtr);
			jQuery(id).parent().remove();
			// var newbtn1in = document.createElement("SPAN");
			break;
		}
	}
}

function goToByScroll(id){
     	// jQuery('html,body').animate({scrollTop: jQuery("#"+id).offset().top-35},500);
     	jQuery('.'+id).css({'top':-1*jQuery('html,body').offset().top+40+'px'});
		// console.log(jQuery('html,body').offset().top);
		// jQuery(e(id)).delay(500).animate({marginTop:'30px'},500);alert(1);
}
function getRadio(radioObj) {
	if(!radioObj)
		return "";
	var radioLength = radioObj.length;
	if(radioLength == undefined)
		if(radioObj.checked)
			return radioObj.value;
		else
			return "";
	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) {
			return radioObj[i].value;
		}
	}
	return "";
}
function CheckReg(type,id){
	switch(type){
		case'login':{
			if(id.value.length>0){
				e('el').innerHTML = '<img alt="" src="img/status_1.gif" border="0" />';
			}
			else{
				e('el').innerHTML = '<img alt="" src="img/status_0.gif" border="0" />';
			}
			break;
		}
		case'password':{
			if(id.value.length>4){
				e('ep').innerHTML = '<img alt="" src="img/status_1.gif" border="0" />';
			}
			else{
				e('ep').innerHTML = '<img alt="" src="img/status_0.gif" border="0" />';
			}
			break;
		}
		case'repassword':{
			if(id.value.length>4&&id.value==e('pss').value){
				e('epr').innerHTML = '<img alt="" src="img/status_1.gif" border="0" />';
			}
			else{
				e('epr').innerHTML = '<img alt="" src="img/status_0.gif" border="0" />';
			}
			break;
		}
		case'email':{
			if(validate(id.value)==false){
				e('ee').innerHTML = '<img alt="" src="img/status_0.gif" border="0" />';
				return
			}
			e('ee').innerHTML = '<img alt="" src="img/status_1.gif" border="0" />';
			//Loading('ee','type=registration&act=check&typecheck=login&value='+id.value,'d',"php/debug.php",'','');
			break;
		}
		case'emailcheck':{
			Load('eerrr','type=registration&act=emailcheck&value='+id.value);
			break;
		}
		case'registration':{
			// alert(e('ee').innerHTML);
			// alert(e('eerrr').innerHTML.length);
			// alert(e('epr').innerHTML);
			// alert(e('ep').innerHTML);
			// alert(e('el').innerHTML);
			if(e('ee').innerHTML=='<img alt="" src="img/status_0.gif" border="0" />'||
			e('ee').innerHTML==''||
			e('eerrr').innerHTML.length>5||
			e('epr').innerHTML=='<img alt="" src="img/status_0.gif" border="0" />'||
			e('epr').innerHTML==''||
			e('ep').innerHTML=='<img alt="" src="img/status_0.gif" border="0" />'||
			e('ep').innerHTML==''||
			e('el').innerHTML=='<img alt="" src="img/status_0.gif" border="0" />'||
			e('el').innerHTML==''){
				e('errorreg').innerHTML = 'Check that the fields have been filled in correctly!';
				return
			}
			// alert(1);
			Page('registration&act=registration&login='+id.nlogin.value+'&email='+id.nemail.value+'&password='+id.npass.value+'&pid='+id.pid.value,e('content'),'');
			
			e('ee').innerHTML = '';
			e('ep').innerHTML = '';
			e('el').innerHTML = '';
			e('epr').innerHTML = '';
			e('eerrr').innerHTML = '';
			id.nlogin.value = '';
			id.nemail.value = '';
			id.npass.value = '';
			id.rpass.value = '';
			
			//Loading('content','type=registration&act=registration&login='+id.nlogin.value+'&email='+id.nemail.value+'&password='+id.npass.value+'&pid='+id.pid.value,'d',"php/debug.php",'','');
			//Loading('panel','type=panel','d',"php/debug.php",'','');
			break;
		}
	}
}
function validate(email){
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	var address = email;
	if(reg.test(address)==false){
		//alert('Invalid Email Address');
		return false;
	}
	return true;
}
function Domains(f){
	var a='';
	for(i=0;i<f.zones.length;i++){
		if(f.zones[i].checked==true){
			a += f.zones[i].value+'|';
		}
	}
	// alert(a);
	Page('domains&act=check&zones='+a+'&domain='+f.domain.value,e('result'),'');
}
function DomainCheckAll(p){
	var d=e('domains');
	for(i=0;i<d.childNodes.length;i++){
		if(p=='1'){
			d.childNodes[i].childNodes[0].checked=true;
		}
		else if(p=='0'){
			d.childNodes[i].childNodes[0].checked=false;
		}
	}
}

function Editor(j,url,filetype,pid,file,id,level,fname){
	var n = Math.round(Math.random() * 1000);
	var b = '<span class="btn f-l m-2 febtn'+n+' active" style="padding:5px 15px 5px 20px;" onclick="if(jQuery(\'.ewin'+n+'\').is(\':visible\')){jQuery(\'.ewin\').hide();jQuery(\'#top .files .btn\').removeClass(\'active\');jQuery(\'body\').removeClass(\'bodyeditor\');}else{jQuery(\'#top .files .btn\').removeClass(\'active\');jQuery(this).addClass(\'active\');jQuery(\'.ewin\').hide();jQuery(\'.ewin'+n+'\').show();jQuery(\'body\').addClass(\'bodyeditor\');}">';
	b += '<span class="iconlblue icon-document" style="position:absolute;left:3px;top:4px;"></span>';
	b += '<span class="close" style="top: 0px;right: 0px;" onclick="var t = setTimeout(function(){jQuery(\'body\').removeClass(\'bodyeditor\');},500);jQuery(\'.febtn'+n+',.ewin'+n+'\').remove();"><span class="icon3 icon-close" title="Close"></span></span>';
	b += '<span class="ftitle">'+fname+'</span>';
	b += '</span>';
	jQuery('#top .files .btn').removeClass('active');
	jQuery('#page #top .files').append(b);
	
	jQuery('body .ewin').hide();
	var d = document.createElement("DIV");
	var d2 = document.createElement("DIV");
	jQuery(d2).addClass('ewinin').css({'width':window.innerWidth-40+'px','height':window.innerHeight-jQuery('#top').height()-60+'px'});
	d2.id = 'ewinin'+n;
	var save = '<span class="btn f-l" onclick="EditorSave(\''+j+'\',\''+pid+'\',\''+file+'\',\''+n+'\');" style="position:absolute;left: 18px;top: 12px;"><span class="iconlblue icon-disk"></span></span>';
	jQuery(d).append(save).append(d2).addClass('ewin ewin'+n).css({'top':jQuery('#top').height()+'px','width':window.innerWidth+'px','height':window.innerHeight-jQuery('#top').height()+'px'});
	jQuery('body').addClass('bodyeditor').append(d);
	
	jQuery('#ewinin'+n).html('').addClass('loading');
	
	var ajaxRequest;
	try{
		ajaxRequest = new XMLHttpRequest();
	}
	catch(e){
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(e){
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				alert("Your browser broke!");
				return false;
			}
		}
	}
	ajaxRequest.open('POST',siteurl+'php/debug.php',true);
	ajaxRequest.setRequestHeader("Content-Charset", "UTF-8");
    ajaxRequest.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	ajaxRequest.setRequestHeader("Content-length",url.length);
	ajaxRequest.setRequestHeader("Connection", "close");
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState==4){
			if(j=='a'){
				alert(ajaxRequest.responseText);
			}
			else{
				console.log(ajaxRequest.responseText);
				
				// var n = num.split('|');
				
				
				// Load(e('ewinin'+n[1]),'type='+h);
				
				// jQuery('#file'.$rj.$j.'').show();
				// jQuery(this).addClass('fw-b td-n');
				jQuery('#ewinin'+n).html(ajaxRequest.responseText);
				jQuery('#ewinin'+n).removeClass('loading');
				var editor = ace.edit(e('ewinin'+n));
				var Mode = require("ace/mode/"+filetype).Mode;
				editor.getSession().setMode(new Mode());
				editor.setShowPrintMargin(false);
				editor.setTheme("ace/theme/ambiance");
				//editor.setTheme("ace/theme/monokai");
				//editor.setTheme("ace/theme/terminal");
				document.getElementById('ewinin'+n).style.fontSize = '14px';
				editor.commands.addCommand({
					name: 'Save',
					bindKey: {win: 'Ctrl-S',  mac: 'Command-S'},
					exec: function(editor) {
						// alert('save!');
						EditorSave(j,pid,file,n);
					},
					readOnly: true // false if this command should not apply in readOnly mode
				});
				// e('filein'+j).style.width = e('page').clientWidth-(level*20)-20+'px';
				// e('filein'+j).style.height = (window.innerHeight<=500?window.innerHeight-70:500)+'px';
				// var p = jQuery('#ewinin'+n).offset().top;
				// jQuery('body').scrollTop(p-40);
				// alert(p);
				// e('filein'+j).style.height = jQuery(document).height()+'px';
			}
		}
	}
	ajaxRequest.send(url);
}
function EditorSave(j,pid,file,n){
	// console.log(j+' '+pid+' '+file);
	jQuery('#ewinin'+n+' .ace_text-layer').addClass('c-ccc');
	var editor = ace.edit(e('ewinin'+n));
	var val = editor.getValue();
	Page('ftpsave&act=save&pid='+pid+'&file='+val.replaceAll('+','[-p-]').replaceAll('&','[-amp-]')+'&remotefile='+file+'',e('messages'));
	var t = setTimeout(function(){e('messages').innerHTML = '';},10000);
	jQuery('#ewinin'+n+' .ace_text-layer').removeClass('c-ccc');
}
function handleFiles(files){
	jQuery('#formedit .ftpfiles').remove();
	var html = '<div class="ftpfiles">';
	html += '<input type="hidden" readonly name="files" value="'+files.length+'" /><table border="1" rules="rows" cellspacing="0" cellpadding="5">';
	for(var i=0;i<files.length;i++){
		html += '<tr><td>'+files[i].name+'</td><td align="right">'+files[i].size+' Bytes</td></tr>';
	}
	html += '</table></div>';
	console.log(files);
	jQuery('#formedit').append(html);
}

function Makeup(act){
	switch(act){
		case'square':{
			var b;
			b = '<div class="block" style="background:rgb('+Math.floor((Math.random()*255)+0)+','+Math.floor((Math.random()*255)+0)+','+Math.floor((Math.random()*255)+0)+');border:0px solid rgb(153,153,153);z-index:0;width:100px;height:100px;position:relative;opacity:0.5;color:#000;">';
			b += '<span onclick="jQuery(this).parent().remove()" class="close" style="float:right;cursor:pointer;">&#9747;</span>';
			b += '<p class="blockname">100x100</p>';
			b += '<p class="blockpos">x:0 y:0</p>';
			b += '</div>';
			jQuery('#makeupwindow').append(b);
			jQuery('#makeupwindow .block').draggable({containment:'parent',grid:[1,1],snap:true,drag:function(event,ui){
				jQuery(this).find('.blockpos').html('x:'+jQuery(this).position().top+' y:'+jQuery(this).position().left);
			}}).resizable({grid:1,containment:'parent',resize:function(event,ui){
				jQuery(this).find('.blockname').html(jQuery(this).width()+'x'+jQuery(this).height());
			}});
			break;
		}
		case'save':{
			var s = new Array;
			var i = 0;
			jQuery('#makeupwindow .block').each(function(){
				// alert(jQuery(this).width()+'|'+jQuery(this).height()+'|'+jQuery(this).position().top+'|'+jQuery(this).position().left);
				s[i] = jQuery(this).width()+'|'+jQuery(this).height()+'|'+jQuery(this).position().top+'|'+jQuery(this).position().left;
				i++;
			});
			// alert(s.join('I'));
			// s = s.join('I');
			Page('makeup&s='+s+'&act=save&image='+jQuery('#makeupwindow').css('background-image')+'&width='+jQuery('#makeupwindow').width()+'&height='+jQuery('#makeupwindow').height(),e('content'));
			jQuery('#makeupwindow').css('background','none')
			
			// console.log(s.join('I'));
			break;
		}
	}
}

function drawChart() {
	// var v = jQuery;
	jQuery('.chart').sparkline('html',{
		type:'pie',
		width:'200px',
		height:'200px',
		tooltipFormatFieldlist:['ddd','yyyyy','gggg']
	});
	/* var d = new Array();
	jQuery('.chart_datas .chart_data').each(function(){
		d.push(jQuery(this).prop('alt')+''+jQuery(this).val());
	});
	var data = google.visualization.arrayToDataTable([
		['', ''],
		['Work',     11],
		['Eat',      2],
		['Commute',  2],
		['Watch TV', 2],
		['Sleep',    7]
	]);

	var options = {
		title: 'Chart'
	};
	var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
	chart.draw(data, options); */
}
