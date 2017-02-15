
function comprar()
{
	var formSerialize = jquery("#appbundle_livrotransacao").serialize();
	jquery.post('http://localhost/united/web/app_dev.php/livrotransacao/create', formSerialize, function(response)
	{
	
		alert(response);

	},'JSON');
}