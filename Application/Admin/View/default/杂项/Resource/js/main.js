//多选框处理，全选、反选、全不选
	var checkall=document.getElementsByName("id[]");
	function select(){
		for(var i=0; i<checkall.length; i++)
		{
			checkall[i].checked=true;
		}
	}

	function fanselect(){
		for(var i=0; i<checkall.length; i++)
		{
			if(checkall[i].checked)
			{
				checkall[i].checked=false;
			}else
			{
				checkall[i].checked=true;
			}
		}
	}

	function noselect(){
		for(var i=0; i<checkall.length; i++)
		{
			checkall[i].checked=false;
		}
	}
	var win=null;

