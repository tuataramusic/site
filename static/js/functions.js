function check(form)
{
	var s1, s2, s3, s4;
	s1=form.number.value;
	s2=form.amount.value;
	if (s1=="")
	{
	  alert("������� ����� �������.");
	  form.number.focus();
	  return false;
	}
	if (s2=="")
	{
	  alert("������� ����� ����������.");
	  form.amount.focus();
	  return false;
	}
	return true;
}

function openbox(id)
{
	display = document.getElementById(id).style.display;
	if(display=='none')
	{
		document.getElementById(id).style.display='block';
	}
	else
	{
		document.getElementById(id).style.display='none';
	}
}  
