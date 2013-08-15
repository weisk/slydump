function initPage() {
	loadFavoriteShows();
}

function loadFavoriteShows() {
	var uri = "inc/atspace_hook.php?action=favoriteShows";
	getAJAX(uri,5);
	return true;
}

function search(type,str) {
	switch(type) {
		case 1:
			removeLevel(2);
			addLevel("Resultados de b&uacute;squeda: "+str,2);
			slide(2);
			var uri = "inc/sly_hook.php?action=search&type=serie&str="+str;
			getAJAX(uri,1);
			break;
		default:break;
	}
}

function loadSerie(ids) {
	resetSerie();
	var uri = "inc/sly_hook.php?action=load&type=serie&ids="+ids;
	getAJAX(uri,2);
}

function loadEpisode(idm,tr) {
	if($('#episodes i.loading').length==0) {
		$('<i class="loading"></i>').insertAfter('#episodes li[nep="'+tr+'"] i');
		var uri = "inc/sly_hook.php?action=getlinks&idm="+idm+"&type=5";
		getAJAX(uri,3,tr);
	}
}

function loadLink(url,type,i) {
	if($('#links .icon-loading').length==0) {
		$('#'+type+' li').eq(i).children(0).append('<i class="icon-loading"></i>');
		$('#'+type+' li').eq(i).addClass('visited');
		var uri = "inc/sly_hook.php?action=getlink&link="+url;
		getAJAX(uri,4,i);
	}
}

function parse(data,type,arg1) {
	if(data=="E1") { showWarning(1); removeLoadings(); return; }
	if(data=="E2") { showWarning(2); removeLoadings(); return; }
	switch(type){
		case 1: 
			var x = eval("("+data+")");
			x = x.response.results;
			var html = '';
			for(var i=0;i<x.length;i++) {
				var y = x[i].object;
				var onclick = 'loadSerie('+y.idm+')';
				html += '<tr onclick="'+onclick+'"><td><img src="'+y.poster.small+'"><td>'+y.name+'</td><td>'+y.seasons+'</td><td>'+y.episodes+'</td></tr>';
			}
			$('#tbody_1').html(html);
			break;
		case 2: 
			var x = eval("("+data+")");
			var title = ''; if (x.name) title = x.name;
			var plot = ''; if (x.plot) plot = x.plot;
			var html = '';
			$('#serie-ids').val(x.idm);
			$('#poster').attr('src',x.img);
			addLevel(title,3);

			html+='<span class="strong">Titulo </span>'+title+'<br><br>';
			html+='<span class="strong">Sinopsis </span>'+plot;
			$('#descr .span9').html(html);
			var htmltemps=[], htmleps=[];
			for(var s in x.seasons_episodes) { if(x.seasons_episodes.hasOwnProperty(s)) {
				var season = parseInt(s.split('_')[1]);
				var html1='<li season="'+season+'"><a onclick="changeSeason('+season+');">'+season+'</a></li>';
				var html2='';
				for(var i=0;i<x.seasons_episodes[s].length;i++) {
					var oEpisod = x.seasons_episodes[s][i];
					var nEpisod = oEpisod.episode;
					html2 += '<li nep="'+nEpisod+'" onclick="loadEpisode(\''+oEpisod.idm+'\','+nEpisod+');"><a><i class="icon-chevron-right"></i>'+nEpisod+'.\t'+oEpisod.title+'</a></li>';
				}
				html2 = '<ul class="nav nav-list" season="'+season+'">'+html2+'</ul>'
				htmltemps.push(html1);
				htmleps.push(html2);
			}}
			$('#temp .nav').html(htmltemps.join(''));
			$('#episodes').html(htmleps.join(''));
			changeSeason(1);
		 	break;
		case 3:
			$('#episodes li[nep="'+arg1+'"] i:first-child ~ i').remove();
			$('#streaming ul').html('');
			$('#download ul').html('');
			if(data.length>2) {
				var x = eval("("+data+")");
				$('#episodes .active').removeClass('active');
				$('#episodes li[nep="'+arg1+'"]').addClass('active visited');
				$('#episodes ul.hidden li.active').removeClass('active visited');
				for(var prop in x) { if(x.hasOwnProperty(prop)) {
					switch(prop) {
						case 'streaming':
							for(var i=0;i<x[prop].length;i++) {
								var o = {
									quality: (typeof x[prop][i].quality != 'undefined'?x[prop][i].quality:''),
									url: x[prop][i].video_url,
									lang: parseLang(x[prop][i].lang,x[prop][i].subtitles),
									host: x[prop][i].host }
								var html = '<li><a onclick="loadLink(\''+o.url+'\',\'streaming\','+i+')"><i class="icon-'+o.lang+'"></i>'+o.host+'  -  '+o.lang+(o.quality!=''?'  -  '+o.quality:'')+'</a></li>';
								$('#streaming ul').append(html);
							}
							break;
						case 'direct_download':
						case 'download':
							for(var i=0;i<x[prop].length;i++) {
								var o = {
									quality: (typeof x[prop][i].quality != 'undefined'?x[prop][i].quality:''),
									url: x[prop][i].video_url,
									lang: parseLang(x[prop][i].lang),
									host: x[prop][i].host }
								var html = '<li><a onclick="loadLink(\''+o.url+'\',\'download\','+i+')"><i class="icon-'+o.lang+'"></i>'+o.host+'  -  '+o.lang+(o.quality!=''?'  -  '+o.quality:'')+'</a></li>';
								$('#download ul').append(html);
							}
							break;
					}
				}}
			} else showWarning(2);
			break;
		case 4:
			$('#links .icon-loading').remove();
			window.open(data,'_blank');
			break;
		case 5:
			var x = eval("("+data+")");
			var html = '<div class="row-fluid"><ul class="thumbnails">';
			for(var i=0;i<x.length;i++){
				if(i%4==0 && i!=0) { html+= '</ul></div><div class="row-fluid"><ul class="thumbnails">'; }
				html+= '<li class="span3"><a class="thumbnail" onclick="loadSerie('+x[i].ids+')">';
				html+= '<img alt="'+x[i].title+'" src="'+x[i].img+'">';
				html+= '<p>'+x[i].title+'</p>';
				html+= '</a></li>';
			}
			html+= '</ul></div>';
			$('#favshows').html(html);
			break;
		default: break;
	}
	return;
}

function parseLang(s,subs) {
	var s = s.toLowerCase();
	if (typeof subs != 'undefined') var subs=subs.toLowerCase();
	else var subs="";
	if(s.indexOf('original')!=-1) return 'english';
	else if (s.indexOf('ingl')!=-1) return 'english';
	else if (s.indexOf('catal')!=-1) return 'catala';
	else if (s.indexOf('latino')!=-1) return 'latino';
	else if (s.indexOf('castellano')!=-1) return 'castellano';
	else if (s.indexOf('sin audio')!=-1) return 'sinaudio';
	else if (s.indexOf('vo')!=-1 && subs!="" ) return 'vose';
	else return s;
}

function changeSeason(i) {
	$('#temp .active').removeClass('active');
	$('#temp li[season="'+i+'"]').addClass('active');
	$('#episodes ul').addClass('hidden');
	$('#episodes ul[season="'+i+'"]').removeClass('hidden');
}

function addLevel(s,pos) {
	removeLevel(pos);
	var html = "<li class='level' pos='"+pos+"'><a onclick='slide("+pos+")'>"+s+"</a></li><li class='divider-vertical'></li>";
	$('#subnav .level[pos='+(pos-1)+']').next().after(html);
	slide(pos);
}

function removeLevel(pos) {
	$('#subnav .level[pos='+pos+']').next().remove();
	$('#subnav .level[pos='+pos+']').remove();
}

function slide(i) {
	$('#subnav .active').removeClass('active');
	$('#subnav .level').eq(i-1).addClass('active');
	//$('.box').css('height','0px'); $('#box'+i).css('height','100%');
	for(var j=1;j<4;j++) {
		//$('#box'+j).animate({ opacity:i==j?'1':'0', width: i==j?'100%':'0px', height: i==j?'100%':'0px'},1000,function(){});
		$('#box'+j).css({
			opacity:i==j?'1':'0',
			width: 	i==j?'100%':'0px',
			height: i==j?'100%':'0px'
		});
	}
}

function resetSerie() {
	$('#descr .span9').html('');
	$('#temp .nav').html('');
	$('#episodes').html('');
	$('#streaming ul').html('');
	$('#download ul').html('');
}

function removeLoadings() {
	$('#links .icon-loading').remove();
	$('#episodes li[nep="'+arg1+'"] i:first-child ~ i').remove();
}

function showWarning(i) {
	switch(i){
		case 1: 	var msg = 'No se pudo realizar la acci&oacute;n, intentalo de nuevo.'; break;
		case 2:		var msg = 'Debes estar logueado para realizar la acci&oacute;n.'; break;
		default: 	break;
	}
	var html = '<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
	html += '<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="myModalLabel">Aviso</h3></div>';
	html += '<div class="modal-body"><p>'+msg+'</p></div>';
	html += '<div class="modal-footer"><button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button></div></div>'
	$(html).modal();
	return;
}