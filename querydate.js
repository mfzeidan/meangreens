var obj = {
	"tray": "1",
	"start_datetime": "",
	"current_datetime": "",
	"temp": "77",
	"moisture_lvl": "45",
	"plant_height": "0",
	"newgrow_flag":"0"
}


var currentdate = new Date();
var datetime = currentdate.getDate() + "/"+(currentdate.getMonth()+1) 
+ "/" + currentdate.getFullYear() + " "
+ currentdate.getHours() + ":" 
+ currentdate.getMinutes() + ":" + currentdate.getSeconds();
var newVal = "datetime";
//obj["current_datetime"] = datetime;
obj["current_datetime"] = datetime

///this is the varabile that is supposed to be
//db.soil_cond.find({"DeviceID":"1"},{"datetime":1,_id:0}).sort({"datetime":-1}).limit(1)
var start_date = "6/12/10 3:33:12"


if (obj["newgrow_flag"] == "1"){
    obj["start_datetime"] = datetime;
    obj["current_datetime"] = datetime;
    str = JSON.stringify(obj);
    alert(str);
}
else{
    obj["start_datetime"] = start_date;
    obj["current_datetime"] = datetime;
    str = JSON.stringify(obj);
    alert(str);
}
