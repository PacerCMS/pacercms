function findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function setTextField(objName,x,newText) { //v3.0
  var obj = findObj(objName); if (obj) obj.value = newText;
}

function confirmLink(theLink, theAction)
{
	var confirmMsg = "Are you sure you want to "
    if (confirmMsg == '' || typeof(window.opera) != 'undefined') {
        return true;
    }
    var is_confirmed = confirm(confirmMsg + theAction + "?");
    if (is_confirmed) {
        theLink.href;
    }
    return is_confirmed;
}

function toggleLayer(whichLayer)
{
	if (document.getElementById)
	{
		// this is the way the standards work
		var style2 = document.getElementById(whichLayer).style;
		style2.display = style2.display? "":"block";
	}
	else if (document.all)
	{
		// this is the way old msie versions work
		var style2 = document.all[whichLayer].style;
		style2.display = style2.display? "":"block";
	}
	else if (document.layers)
	{
		// this is the way nn4 works
		var style2 = document.layers[whichLayer].style;
		style2.display = style2.display? "":"block";
	}
}

// None of these are used quite yet.


function disableSubmit(whichButton)
{
	if (document.getElementById)
	{
		// this is the way the standards work
		document.getElementById(whichButton).disabled = true;
	}
	else if (document.all)
	{
		// this is the way old msie versions work
		document.all[whichButton].disabled = true;
	}
	else if (document.layers)
	{
		// this is the way nn4 works
		document.layers[whichButton].disabled = true;
	}
}

function confirmDelete()
{
    var agree=confirm("Are you sure you wish to delete this entry?");
    if (agree)
        return true;
    else
        return false;
}

function returnObjById( id )
{
	if (document.getElementById)
		var returnVar = document.getElementById(id);
	else if (document.all)
		var returnVar = document.all[id];
	else if (document.layers)
		var returnVar = document.layers[id];
	return returnVar;
}

function setHandler( tagType, clsName, eventType, func )
{
	elements = document.getElementsByTagName( tagType );
	for( var t = 0; t < elements.length; t++ )
	{
		if( elements[t].className.indexOf( clsName ) >= 0 )
		{
			var code = "elements[t]." + eventType + " = " + func;
			eval( code );
		}
	}
}

function toggleColor( )
{
	var style2 = this.style;
	style2.backgroundColor = style2.backgroundColor? "":"#FFFF00";
}

function toggleBgColor( elem )
{
	var style2 = elem.style;
	style2.backgroundColor = style2.backgroundColor? "":"#FFFF00";
}

function externalLinks( )
{
	if (!document.getElementsByTagName) return;
	var anchors = document.getElementsByTagName("a");
	for (var i=0; i<anchors.length; i++)
	{
		var anchor = anchors[i];
		if (anchor.getAttribute("href") && anchor.getAttribute("rel") == "external")
			anchor.target = "_blank";
	}
}

function initpage( )
{
	setHandler( 'tr', 'toggle', 'onclick', 'toggleColor' );
	setHandler( 'span', 'heading', 'onmouseover', 'toggleColor' );
	setHandler( 'span', 'heading', 'onmouseout', 'toggleColor' );
	externalLinks( );
}

window.onload = initpage;