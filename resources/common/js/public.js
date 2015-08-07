/**
 * Coder: ZX
 * Date: 2015/8/6
 * Time: 17:18
 */
function getTime()
{
	var dateTime = new Date();
	
	var hh = dateTime.getHours();
	hh = addFrontZero(hh);

	var mm = dateTime.getMinutes();
	mm = addFrontZero(mm);

	var ss = dateTime.getSeconds();
	ss = addFrontZero(ss);

	var year = dateTime.getFullYear();
	
	var month = dateTime.getMonth() + 1;
	month = addFrontZero(month);
	
	var date = dateTime.getDate();
	date = addFrontZero(date);

	return '' + year + '-' + month + '-' + date + ' ' + hh + ':' + mm + ':' + ss;
}

function addFrontZero(num)
{
	return num < 10 ? '0' + num : num;
}

function strlen(str) {
	return str.length + (escape(str).split("%u").length - 1);
}

// function isDateStr(ds)
// {
// 	parts = ds.split(' ');
// 	if (parts.length == 2) {
// 		return isDatePart(parts[0]) && isTimePart(parts[1]);
// 	}else{
// 		return false;
// 	}
// }

// function isDatePart(dateStr)
// {
// 	parts = dateStr.split('/');
// 	if (parts.length == 3) {
// 		m = parts[0];
// 		d = parts[1];
// 		y = parts[2];

// 	}else {
// 		return false;
// 	}
// }