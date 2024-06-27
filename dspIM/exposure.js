var site='http://x.com'; //站点域名
var schedule = require("node-schedule");
var request  = require('request');

function FormatNowDate(){
	var mDate = new Date();
	var Y = mDate.getFullYear();
	var M = mDate.getMonth()+1;
	var D = mDate.getDate();
	var H = mDate.getHours();
	var i = mDate.getMinutes();
	var s = mDate.getSeconds();
	return Y +'-' + M + '-' + D + ' ' + H + ':' + i + ':' + s;
}

//定时减曝光值
var rule = new schedule.RecurrenceRule();

var times = [];
　　for(var i=0; i<24; i++){
    times.push(i);
　　}
var lastid=0;
rule.hour = times;
rule.minute = 0;
rule.second = 0;
// console.log(times);

var j = schedule.scheduleJob(rule, function(){

    //time=FormatNowDate();
    // console.log("执行任务:"+time);
    setVal(lastid);


});


//定时返回上热门剩余钻石数
var pop_rule = new schedule.RecurrenceRule();

var pop_times = [];
var minutes=0;

for(var i=0; i<6; i++){
    minutes=i*10;
    pop_times.push(minutes);
}

var pop_lastid=0;
pop_rule.minute = pop_times;
pop_rule.second = 0;
//console.log(pop_times);

var j_pop = schedule.scheduleJob(pop_rule, function(){

    time=FormatNowDate();
    //console.log("执行任务:"+time);
    setPopular(pop_lastid);


});



//定时处理直播
var rule2 = new schedule.RecurrenceRule();

var times2 = [];

for(var i=0; i<60; i++){
    times2.push(i);
}

rule2.second = times2;
// console.log(times);

var j2 = schedule.scheduleJob(rule2, function(){
    // time=FormatNowDate();
    // console.log("执行任务:"+time);
    upLive();
});


//定时处理订单状态
var goodsorder_rule = new schedule.RecurrenceRule();

var goodsorder_times = [];
var goodsorder_minutes=0;

for(var i=0; i<12; i++){
    goodsorder_minutes=i*5;
    goodsorder_times.push(goodsorder_minutes);
}

var goodsorder_lastid=0;
goodsorder_rule.minute = goodsorder_times;
goodsorder_rule.second = 0;
// console.log(times);

var goodsorder_j = schedule.scheduleJob(goodsorder_rule, function(){
    // time=FormatNowDate();
    // console.log("执行任务:"+time);
    changeShopOrder(goodsorder_lastid);
});


//视频曝光值定时任务
function setVal(lastid){
    var time=FormatNowDate();
    // console.log("执行任务setVal"+lastid+'--'+time);
    request(site+"/Appapi/Video/updateshowval?lastid="+lastid,function(error, response, body){
    	//console.log(error);
        if(error) return;
        if(!body) return;
        // console.log('setVal-body-'+lastid+'--'+time);
        // console.log(body);
        if(body!='NO'){
            var strs=[];
            strs=body.split("-");
            
            // console.log(strs);
            if(strs[0]=='OK' && strs[1]!='0'){
                setVal(strs[1]);
            }
            
        }
    });
    
}

//上热门的视频未达到指定播放量 退还剩余钻石
function setPopular(lastid){
    var time=FormatNowDate();
    //console.log("执行任务setPopular"+lastid+'--'+time);
    request(site+"/Appapi/Video/updatePopular?lastid="+lastid,function(error, response, body){
        //console.log(error);
        if(error) return;
        if(!body) return;
         //console.log('setPopular-body-'+lastid+'--'+time);
         //console.log(body);
        if(body!='NO'){
            var strs=[];
            strs=body.split("-");
            
             //console.log(strs);
            if(strs[0]=='OK' && strs[1]!='0'){
                setPopular(strs[1]);
            }
            
        }
    });
    
}


//定期处理订单状态
function upLive(){
    // var time=FormatNowDate();
    // console.log("执行任务setVal"+lastid+'--'+time);
    request(site+"/appapi/liveback/uplive",function(error, response, body){
        //console.log(error);
        if(error) return;
        if(!body) return;
        // console.log('setVal-body-'+lastid+'--'+time);
        // console.log(body);
    });
    
}

//定期处理订单状态
function changeShopOrder(lastid){
    // var time=FormatNowDate();
    // console.log("执行任务setVal"+lastid+'--'+time);
    request(site+"/Appapi/Shoporder/checkOrder?lastid="+lastid,function(error, response, body){
        //console.log(error);
        if(error) return;
        if(!body) return;
        // console.log('setVal-body-'+lastid+'--'+time);
        // console.log(body);
        if(body!='NO'){
            var strs=[];
            strs=body.split("-");
            
            // console.log(strs);
            if(strs[0]=='OK' && strs[1]!='0'){
                changeShopOrder(strs[1]);
            }
            
        }
    });
    
}
