<script type="text/javascript">
	function showSelected(val){
		document.getElementById
('selectedResult').innerHTML = "The selected number is - " 
+ val;
	}
</script>

<div id='selectedResult'></div>

<select name='test' onChange='showSelected(this.value)'>
	<option value='1'>one</option>
	<option value='2'>two</option>
</select>