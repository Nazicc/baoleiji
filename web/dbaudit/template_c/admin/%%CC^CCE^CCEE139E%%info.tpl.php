<?php /* Smarty version 2.6.18, created on 2013-08-19 22:27:53
         compiled from info.tpl */ ?>
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
<title><?php echo $this->_tpl_vars['language']['Master']; ?>
<?php echo $this->_tpl_vars['language']['page']; ?>
面</title>
<meta name="generator" content="editplus">
<meta name="author" content="nuttycoder">
<link href="<?php echo $this->_tpl_vars['template_root']; ?>
/cssjs/all_purpose_style.css" rel="stylesheet" type="text/css" />
<script>
	function my_confirm(str){
		if(!confirm("确认要" + str + "？"))
		{
			window.event.returnValue = false;
		}
	}
	function chk_form(){
		for(var i = 0; i < document.member_list.elements.length;i++){
			var e = document.member_list.elements[i];
			if(e.name == 'chk_member[]' && e.checked == true)
				return true;
		}
		alert("您没有<?php echo $this->_tpl_vars['language']['select']; ?>
任何<?php echo $this->_tpl_vars['language']['User']; ?>
！");
		return false;
	}
</script>
<script>
function setScroll(){
	window.parent.scrollTo(0,0);
}
</script>
</head>
<style type="text/css">
a {
    color: #003499;
    text-decoration: none;
} 
a:hover {
    color: #000000;
    text-decoration: underline;
}
</style>
<td width="84%" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td valign="middle" class="hui_bj"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tabs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td></tr>
<body>
		  <tr>
		<td class="">
			<iframe id="frame_content" src='<?php echo $this->_tpl_vars['url']; ?>
' width= "100%" scrolling="no" onload="setScroll();reinitIframe();" frameborder="0" ></iframe>
		</td>
	  </tr>
	</table>
	<script type="text/javascript">
document.ondblclick= function(){
	window.parent.menuhide();
}
document.getElementById("frame_content").contentWindow.document.ondblclick= function(){
	window.parent.menuhide();
}
function reinitIframe(){

var iframe = document.getElementById("frame_content");

try{
var minHeight = 600;
var bHeight = iframe.contentWindow.document.body.scrollHeight;
var height = Math.max(bHeight, minHeight);
iframe.height =  height;
window.parent.reinitIframe();
}catch (ex){}

}

//window.setInterval("reinitIframe()", 200);

</script>
</body>
</html>

